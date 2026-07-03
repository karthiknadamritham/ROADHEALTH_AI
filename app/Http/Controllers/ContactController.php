<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\NewContactMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * Store a contact message sent by a citizen/guest.
     */
    public function storeCitizenMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'territory' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $contactMessage = ContactMessage::create([
            'sender_id' => $user ? $user->id : null,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'territory' => $validated['territory'],
            'type' => 'citizen_to_officer',
            'sender_territory' => $user ? $user->territory : null,
            'status' => 'pending',
        ]);

        // Send notifications to all active/approved officers and admins in the target territory
        try {
            $officers = User::whereIn('role', ['officer', 'admin'])
                ->where('status', 'approved')
                ->where('territory', $validated['territory'])
                ->get();

            if ($officers->count() > 0) {
                Notification::send($officers, new NewContactMessageNotification($contactMessage));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to notify officers about new contact message: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => "Message Sent Successfully! Your message has been routed to the {$validated['territory']} officers."
        ]);
    }

    /**
     * Store an inter-municipal message sent by an officer to officers of another territory.
     */
    public function storeOfficerMessage(Request $request)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['officer', 'admin'])) {
            return back()->withErrors(['auth' => 'Only officers and admins can send inter-municipal messages.']);
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'territory' => 'required|string|max:255', // Recipient territory
        ]);

        if ($user->territory === $validated['territory']) {
            return back()->withErrors(['territory' => 'You cannot send an inter-municipal dispatch to your own territory.']);
        }

        $contactMessage = ContactMessage::create([
            'sender_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'territory' => $validated['territory'],
            'type' => 'officer_to_officer',
            'sender_territory' => $user->territory,
            'status' => 'pending',
        ]);

        // Send notifications to all active/approved officers and admins in the targeted recipient territory
        try {
            $officers = User::whereIn('role', ['officer', 'admin'])
                ->where('status', 'approved')
                ->where('territory', $validated['territory'])
                ->get();

            if ($officers->count() > 0) {
                Notification::send($officers, new NewContactMessageNotification($contactMessage));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to notify target officers of inter-municipal dispatch: ' . $e->getMessage());
        }

        return back()->with('success', 'Inter-Municipal dispatch has been logged and sent successfully.');
    }

    /**
     * Submit officer reply or remarks for a message.
     */
    public function replyMessage(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['officer', 'admin'])) {
            return back()->withErrors(['auth' => 'Only authorized officers and admins can reply to contact messages.']);
        }

        $validated = $request->validate([
            'reply' => 'required|string',
        ]);

        $contactMessage = ContactMessage::findOrFail($id);

        // Security check: Officers can only reply to messages targeting their own territory.
        // Admins are system-wide general managers and can reply to any message.
        if ($user->role !== 'admin' && $contactMessage->territory !== $user->territory) {
            return back()->withErrors(['auth' => 'Unauthorized: This message belongs to a different municipal territory.']);
        }

        $contactMessage->update([
            'reply' => $validated['reply'],
            'replied_by' => $user->id,
            'replied_at' => now(),
            'status' => 'replied',
        ]);

        // Optionally send a notification back to the original sender if they are an active user
        if ($contactMessage->sender_id) {
            try {
                $senderUser = User::find($contactMessage->sender_id);
                if ($senderUser) {
                    $desc = "An officer from {$contactMessage->territory} has posted remarks/reply to your inquiry: \"{$contactMessage->subject}\"";
                    $senderUser->notify(new class($contactMessage, $desc) extends \Illuminate\Notifications\Notification {
                        protected $msg;
                        protected $desc;
                        public function __construct($msg, $desc) { $this->msg = $msg; $this->desc = $desc; }
                        public function via($notifiable) { return ['database']; }
                        public function toArray($notifiable) {
                            return [
                                'contact_message_id' => $this->msg->id,
                                'title' => 'Remarks/Reply Received',
                                'description' => $this->desc,
                                'severity_class' => 'info'
                            ];
                        }
                    });
                }
            } catch (\Exception $e) {
                \Log::error('Failed to notify contact sender of officer reply: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Your reply and remarks have been successfully posted and logged.');
    }
}
