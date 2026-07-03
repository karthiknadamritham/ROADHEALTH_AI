<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\RoadAnalysis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TerritoryIsolationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that pending users are redirected to pending-verification.
     */
    public function test_pending_users_are_redirected_to_verification_page(): void
    {
        $pendingUser = User::create([
            'name' => 'Pending Staff',
            'email' => 'pending@roadhealth.ai',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'territory' => 'Delhi Municipal',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($pendingUser)->get('/dashboard');

        $response->assertRedirect('/pending-verification');
    }

    /**
     * Test that Delhi Officer cannot see Mumbai complaints.
     */
    public function test_delhi_officer_cannot_see_mumbai_complaints(): void
    {
        $delhiOfficer = User::create([
            'name' => 'Delhi Officer',
            'email' => 'delhi_officer@roadhealth.ai',
            'password' => bcrypt('password'),
            'role' => 'officer',
            'territory' => 'Delhi Municipal',
            'status' => 'approved'
        ]);

        // Complaint in Delhi
        RoadAnalysis::create([
            'scan_id' => '#RH-2026-0001',
            'image_path' => 'road_images/delhi.jpg',
            'original_filename' => 'delhi.jpg',
            'location' => 'Connaught Place, Delhi',
            'territory' => 'Delhi Municipal',
            'pci_score' => 45,
            'condition' => 'poor',
            'severity' => 'Medium'
        ]);

        // Complaint in Mumbai
        RoadAnalysis::create([
            'scan_id' => '#RH-2026-0002',
            'image_path' => 'road_images/mumbai.jpg',
            'original_filename' => 'mumbai.jpg',
            'location' => 'Gateway of India, Mumbai',
            'territory' => 'Mumbai Municipal',
            'pci_score' => 30,
            'condition' => 'poor',
            'severity' => 'High'
        ]);

        $response = $this->actingAs($delhiOfficer)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee('Connaught Place');
        $response->assertDontSee('Gateway of India');
    }

    /**
     * Test that Admin is general/global and can see complaints from all territories.
     */
    public function test_admin_is_general_and_sees_all_complaints(): void
    {
        $admin = User::create([
            'name' => 'General Admin',
            'email' => 'admin@roadhealth.ai',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'territory' => 'Delhi Municipal',
            'status' => 'approved'
        ]);

        // Complaint in Delhi
        RoadAnalysis::create([
            'scan_id' => '#RH-2026-0001',
            'image_path' => 'road_images/delhi.jpg',
            'original_filename' => 'delhi.jpg',
            'location' => 'Connaught Place, Delhi',
            'territory' => 'Delhi Municipal',
            'pci_score' => 45,
            'condition' => 'poor',
            'severity' => 'Medium'
        ]);

        // Complaint in Mumbai
        RoadAnalysis::create([
            'scan_id' => '#RH-2026-0002',
            'image_path' => 'road_images/mumbai.jpg',
            'original_filename' => 'mumbai.jpg',
            'location' => 'Gateway of India, Mumbai',
            'territory' => 'Mumbai Municipal',
            'pci_score' => 30,
            'condition' => 'poor',
            'severity' => 'High'
        ]);

        $response = $this->actingAs($admin)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee('Connaught Place');
        $response->assertSee('Gateway of India');
    }

    /**
     * Test that Delhi Officer cannot approve Mumbai Staff.
     */
    public function test_delhi_officer_cannot_approve_mumbai_staff(): void
    {
        $delhiOfficer = User::create([
            'name' => 'Delhi Officer',
            'email' => 'delhi_officer@roadhealth.ai',
            'password' => bcrypt('password'),
            'role' => 'officer',
            'territory' => 'Delhi Municipal',
            'status' => 'approved'
        ]);

        $mumbaiStaff = User::create([
            'name' => 'Mumbai Staff',
            'email' => 'mumbai_staff@roadhealth.ai',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'territory' => 'Mumbai Municipal',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($delhiOfficer)->post("/dashboard/approve-user/{$mumbaiStaff->id}", [
            'status' => 'approved',
            'remarks' => 'Approved'
        ]);

        $response->assertSessionHasErrors(['auth']);
        $this->assertEquals('pending', $mumbaiStaff->fresh()->status);
    }

    /**
     * Test that Admin can approve Officer from any territory.
     */
    public function test_admin_can_approve_officer_from_any_territory(): void
    {
        $admin = User::create([
            'name' => 'General Admin',
            'email' => 'admin@roadhealth.ai',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'territory' => 'Delhi Municipal',
            'status' => 'approved'
        ]);

        $mumbaiOfficer = User::create([
            'name' => 'Mumbai Officer',
            'email' => 'mumbai_officer@roadhealth.ai',
            'password' => bcrypt('password'),
            'role' => 'officer',
            'territory' => 'Mumbai Municipal',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($admin)->post("/dashboard/approve-user/{$mumbaiOfficer->id}", [
            'status' => 'approved',
            'remarks' => 'Approved'
        ]);

        $response->assertStatus(302); // Redirect back on success
        $this->assertEquals('approved', $mumbaiOfficer->fresh()->status);
    }
}
