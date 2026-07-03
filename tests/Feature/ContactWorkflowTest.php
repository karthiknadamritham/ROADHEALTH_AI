<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\NewContactMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a public visitor or citizen can submit a message targeting a specific territory.
     */
    public function test_public_guest_or_citizen_can_submit_message_targeting_a_territory()
    {
        // 1. Create an approved officer in Delhi Municipal to receive notifications
        $officer = User::create([
            'name' => 'Delhi Officer',
            'email' => 'delhi_off@roadhealth.ai',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'status' => 'approved',
            'territory' => 'Delhi Municipal',
        ]);

        // 2. Post a contact message as a guest/citizen
        $response = $this->postJson(route('contact.store'), [
            'name' => 'John Citizen',
            'email' => 'john@citizen.com',
            'phone' => '9876543210',
            'subject' => 'Pothole near central park',
            'message' => 'There is a severe pothole near the central park entrance.',
            'territory' => 'Delhi Municipal',
        ]);

        // 3. Assert success response
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        // 4. Assert message was written to DB
        $this->assertDatabaseHas('contact_messages', [
            'name' => 'John Citizen',
            'email' => 'john@citizen.com',
            'subject' => 'Pothole near central park',
            'territory' => 'Delhi Municipal',
            'type' => 'citizen_to_officer',
            'status' => 'pending',
        ]);

        // 5. Assert officer received notification
        $this->assertEquals(1, $officer->unreadNotifications->count());
        $notification = $officer->unreadNotifications->first();
        $this->assertEquals('New Contact Message: Pothole near central park', $notification->data['title']);
    }

    /**
     * Test territory-based isolation of messages for officers, and the admin global bypass.
     */
    public function test_territory_based_scoping_for_officers_and_admin_bypass()
    {
        // 1. Create Delhi Officer, Bengaluru Officer, and System Admin
        $delhiOfficer = User::create([
            'name' => 'Delhi Officer',
            'email' => 'delhi_off@roadhealth.ai',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'status' => 'approved',
            'territory' => 'Delhi Municipal',
        ]);

        $bengaluruOfficer = User::create([
            'name' => 'Bengaluru Officer',
            'email' => 'blr_off@roadhealth.ai',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'status' => 'approved',
            'territory' => 'Bengaluru Municipal',
        ]);

        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@roadhealth.ai',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'approved',
            'territory' => 'Delhi Municipal',
        ]);

        // 2. Create messages targeting Delhi
        $delhiMessage = ContactMessage::create([
            'name' => 'Delhi Citizen',
            'email' => 'delhi_c@citizen.com',
            'subject' => 'Delhi Issue',
            'message' => 'Inquiry for Delhi.',
            'territory' => 'Delhi Municipal',
            'type' => 'citizen_to_officer',
            'status' => 'pending',
        ]);

        // 3. Create messages targeting Bengaluru
        $blrMessage = ContactMessage::create([
            'name' => 'Bengaluru Citizen',
            'email' => 'blr_c@citizen.com',
            'subject' => 'Bengaluru Issue',
            'message' => 'Inquiry for Bengaluru.',
            'territory' => 'Bengaluru Municipal',
            'type' => 'citizen_to_officer',
            'status' => 'pending',
        ]);

        // 4. Log in as Delhi Officer and assert they only see Delhi messages
        $response = $this->actingAs($delhiOfficer)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('incomingCitizenMessages', function ($messages) use ($delhiMessage, $blrMessage) {
            return $messages->contains($delhiMessage) && !$messages->contains($blrMessage);
        });

        // 5. Log in as Bengaluru Officer and assert they only see Bengaluru messages
        $response = $this->actingAs($bengaluruOfficer)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('incomingCitizenMessages', function ($messages) use ($delhiMessage, $blrMessage) {
            return !$messages->contains($delhiMessage) && $messages->contains($blrMessage);
        });

        // 6. Log in as Admin and assert they see BOTH messages (global bypass)
        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertViewHas('incomingCitizenMessages', function ($messages) use ($delhiMessage, $blrMessage) {
            return $messages->contains($delhiMessage) && $messages->contains($blrMessage);
        });
    }

    /**
     * Test that an officer can reply to contact messages targeting their own territory,
     * and that the user is notified if they were authenticated when sending the message.
     */
    public function test_officer_can_reply_to_message_in_their_own_territory()
    {
        // 1. Create Delhi Officer
        $officer = User::create([
            'name' => 'Delhi Officer',
            'email' => 'delhi_off@roadhealth.ai',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'status' => 'approved',
            'territory' => 'Delhi Municipal',
        ]);

        // 2. Create Citizen who is registered in the system
        $citizen = User::create([
            'name' => 'John Citizen',
            'email' => 'john@citizen.com',
            'password' => Hash::make('password'),
            'role' => 'citizen',
            'status' => 'approved',
            'territory' => 'Delhi Municipal',
        ]);

        // 3. Create a message from the registered citizen targeting Delhi
        $message = ContactMessage::create([
            'sender_id' => $citizen->id,
            'name' => $citizen->name,
            'email' => $citizen->email,
            'subject' => 'Delhi Pothole',
            'message' => 'Fix please.',
            'territory' => 'Delhi Municipal',
            'type' => 'citizen_to_officer',
            'status' => 'pending',
        ]);

        // 4. Act as officer and reply to this message
        $response = $this->actingAs($officer)->post(route('contact.reply', $message->id), [
            'reply' => 'We are working on fixing it tomorrow.',
        ]);

        // 5. Assert successful redirect back
        $response->assertStatus(302);

        // 6. Assert DB message was updated with reply metadata
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'replied',
            'reply' => 'We are working on fixing it tomorrow.',
            'replied_by' => $officer->id,
        ]);

        // 7. Assert citizen was notified of the reply/remarks
        $this->assertEquals(1, $citizen->unreadNotifications->count());
        $notification = $citizen->unreadNotifications->first();
        $this->assertEquals('Remarks/Reply Received', $notification->data['title']);
        $this->assertStringContainsString('An officer from Delhi Municipal has posted remarks/reply', $notification->data['description']);
    }

    /**
     * Test that an officer is blocked from replying to contact messages targeting a different territory.
     */
    public function test_officer_cannot_reply_to_message_in_another_territory()
    {
        // 1. Create Delhi Officer
        $officer = User::create([
            'name' => 'Delhi Officer',
            'email' => 'delhi_off@roadhealth.ai',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'status' => 'approved',
            'territory' => 'Delhi Municipal',
        ]);

        // 2. Create Bengaluru message
        $message = ContactMessage::create([
            'name' => 'Bengaluru Citizen',
            'email' => 'blr_c@citizen.com',
            'subject' => 'Bengaluru Issue',
            'message' => 'Fix please.',
            'territory' => 'Bengaluru Municipal',
            'type' => 'citizen_to_officer',
            'status' => 'pending',
        ]);

        // 3. Act as Delhi Officer and try to reply to Bengaluru message
        $response = $this->actingAs($officer)->post(route('contact.reply', $message->id), [
            'reply' => 'I cannot reply to this.',
        ]);

        // 4. Assert redirect back with session authorization error
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['auth']);

        // 5. Assert DB message was NOT modified
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'pending',
            'reply' => null,
            'replied_by' => null,
        ]);
    }

    /**
     * Test inter-municipal communications between officers of different territories.
     */
    public function test_inter_municipal_communication_workflow()
    {
        // 1. Create Delhi Officer and Bengaluru Officer
        $delhiOfficer = User::create([
            'name' => 'Delhi Officer',
            'email' => 'delhi_off@roadhealth.ai',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'status' => 'approved',
            'territory' => 'Delhi Municipal',
        ]);

        $bengaluruOfficer = User::create([
            'name' => 'Bengaluru Officer',
            'email' => 'blr_off@roadhealth.ai',
            'password' => Hash::make('password'),
            'role' => 'officer',
            'status' => 'approved',
            'territory' => 'Bengaluru Municipal',
        ]);

        // 2. Delhi officer tries to send dispatch to their own territory (Delhi Municipal) - must fail
        $response = $this->actingAs($delhiOfficer)->post(route('contact.officer.store'), [
            'subject' => 'Internal talk',
            'message' => 'This should fail.',
            'territory' => 'Delhi Municipal',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['territory']);

        // 3. Delhi officer sends dispatch to Bengaluru Municipal - must succeed
        $response = $this->actingAs($delhiOfficer)->post(route('contact.officer.store'), [
            'subject' => 'Inter-Municipal Request',
            'message' => 'Seeking insights on road asphalt standard.',
            'territory' => 'Bengaluru Municipal',
        ]);
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // 4. Assert dispatch stored in DB
        $this->assertDatabaseHas('contact_messages', [
            'sender_id' => $delhiOfficer->id,
            'subject' => 'Inter-Municipal Request',
            'territory' => 'Bengaluru Municipal',
            'type' => 'officer_to_officer',
            'sender_territory' => 'Delhi Municipal',
            'status' => 'pending',
        ]);

        // 5. Assert Bengaluru officer received notification
        $this->assertEquals(1, $bengaluruOfficer->unreadNotifications->count());
        $notification = $bengaluruOfficer->unreadNotifications->first();
        $this->assertEquals('New Contact Message: Inter-Municipal Request', $notification->data['title']);
        $this->assertStringContainsString('Received an inquiry from Officer from Delhi Municipal', $notification->data['description']);
    }
}
