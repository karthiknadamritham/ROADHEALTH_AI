<?php

namespace App\Http\Controllers;

use App\Models\RoadAnalysis;
use App\Models\User;
use App\Models\MaintenanceTask;
use App\Models\TaskActivity;
use App\Notifications\StaffAssignedNotification;
use App\Notifications\AccountStatusNotification;
use App\Notifications\TaskStatusChangedNotification;
use App\Notifications\TaskReviewedNotification;
use App\Notifications\WorkProgressUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    /**
     * Display the maintenance dashboard with staff and unassigned tasks.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->role, ['admin', 'officer', 'staff'])) {
            // For citizen/guest, retrieve citizen's dynamic tasks
            $reportedComplaints = [];
            if ($user && $user->role === 'citizen') {
                $reportedComplaints = RoadAnalysis::where('user_id', $user->id)
                    ->with(['maintenanceTask.taskActivities', 'maintenanceTask.assignedStaff', 'maintenanceTask.assignedBy'])
                    ->latest()
                    ->get();
            }
            return view('maintenance-citizen', compact('reportedComplaints'));
        }

        // Admin sees all, Officers see unassigned reports in their territory
        if ($user->role === 'admin') {
            $unassignedReports = RoadAnalysis::where(function($query) {
                $query->where('is_registered', true)
                      ->orWhere(function($q) {
                          $q->whereNotNull('user_id')
                            ->where('pci_score', '<', 75)
                            ->whereNotIn('condition', ['Invalid', 'invalid']);
                      });
            })
            ->whereNotIn('id', function($query) {
                $query->select('road_analysis_id')->from('maintenance_tasks');
            })->latest()->get();
        } else {
            $unassignedReports = RoadAnalysis::where('territory', $user->territory)
                ->where(function($query) {
                    $query->where('is_registered', true)
                          ->orWhere(function($q) {
                              $q->whereNotNull('user_id')
                                ->where('pci_score', '<', 75)
                                ->whereNotIn('condition', ['Invalid', 'invalid']);
                          });
                })
                ->whereNotIn('id', function($query) {
                    $query->select('road_analysis_id')->from('maintenance_tasks');
                })->latest()->get();
        }

        // Decode JSON for detections
        foreach ($unassignedReports as $r) {
            $r->detections_decoded = is_string($r->detections) ? json_decode($r->detections, true) : $r->detections;
            if(is_string($r->detections_decoded)) $r->detections_decoded = json_decode($r->detections_decoded, true);
        }

        $selectedId = $request->input('id');
        $selectedReport = $selectedId 
            ? $unassignedReports->where('id', $selectedId)->first() 
            : $unassignedReports->first();

        // Get approved staff members (Admin sees all, Officers see same territory)
        if ($user->role === 'admin') {
            $staffMembers = User::where('role', 'staff')
                ->where('status', 'approved')
                ->get();
        } else {
            $staffMembers = User::where('role', 'staff')
                ->where('status', 'approved')
                ->where('territory', $user->territory)
                ->get();
        }

        return view('maintenance-officer', compact('unassignedReports', 'staffMembers', 'selectedReport'));
    }

    /**
     * Assign a road analysis report to a staff member.
     */
    public function assign(Request $request)
    {
        $request->validate([
            'road_analysis_id' => 'required|exists:road_analyses,id',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:emergency,high,medium,low',
            'deadline' => 'required|date|after_or_equal:today',
        ]);

        $currentUser = auth()->user();
        $analysis = RoadAnalysis::findOrFail($request->road_analysis_id);
        $staff = User::findOrFail($request->assigned_to);

        // Scope validation: check territory (Officers are restricted to their territory, Admin ensures staff matches analysis territory)
        if ($currentUser->role === 'officer') {
            if ($analysis->territory !== $currentUser->territory || $staff->territory !== $currentUser->territory) {
                return redirect()->back()->withErrors(['territory' => 'Territory mismatch for assignment.']);
            }
        } else {
            if ($analysis->territory !== $staff->territory) {
                return redirect()->back()->withErrors(['territory' => 'The selected staff member does not belong to the territory of this report.']);
            }
        }

        $task = MaintenanceTask::create([
            'road_analysis_id' => $request->road_analysis_id,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => $currentUser->id,
            'priority' => $request->priority,
            'status' => 'assigned',
            'deadline' => $request->deadline,
        ]);

        // Log Task Activity
        TaskActivity::create([
            'maintenance_task_id' => $task->id,
            'user_id' => $currentUser->id,
            'action' => 'assigned',
            'description' => "Task assigned to {$staff->name} with priority {$request->priority} and deadline {$request->deadline} by Officer {$currentUser->name}.",
        ]);

        // Send Notification to the assigned staff member
        $staff->notify(new StaffAssignedNotification($task));

        return redirect()->back()->with('success', 'Staff Assigned Successfully! Notification sent to ' . $staff->name);
    }

    /**
     * Approve or reject a user (officer/staff) registration.
     */
    public function approveUser(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $targetUser = User::findOrFail($id);
        $currentUser = auth()->user();

        // Ensure current user has permission (Admin can approve Officer globally, Officer can approve Staff in same territory)
        if ($currentUser->role === 'admin' && $targetUser->role === 'officer') {
            // Valid: Admin is general/global and can approve officers from any territory
        } elseif ($currentUser->role === 'officer' && $targetUser->role === 'staff' && $targetUser->territory === $currentUser->territory) {
            // Valid: Officer must match territory with Staff
        } else {
            return redirect()->back()->withErrors(['auth' => 'Unauthorized approval action or territory mismatch.']);
        }

        $targetUser->status = $request->status;
        $targetUser->approval_remarks = $request->remarks;
        $targetUser->save();

        // Notify target user
        $targetUser->notify(new AccountStatusNotification($request->status, $request->remarks));

        return redirect()->back()->with('success', 'User status updated to ' . ucfirst($request->status));
    }

    /**
     * Update task status (for staff members).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:started,paused,completed',
            'repair_notes' => 'required_if:status,completed|nullable|string',
            'completion_report' => 'required_if:status,completed|nullable|string',
            'after_image' => 'required_if:status,completed|nullable|image|max:10240',
            'proof_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:10240',
        ]);

        $task = MaintenanceTask::findOrFail($id);
        $currentUser = auth()->user();

        // Check if assigned to this staff
        if ($task->assigned_to !== $currentUser->id) {
            return redirect()->back()->withErrors(['auth' => 'Unauthorized task access.']);
        }

        $oldStatus = $task->status;
        $task->status = $request->status;

        if ($request->status === 'started') {
            $task->started_at = now();
            $task->paused_at = null;
        } elseif ($request->status === 'paused') {
            $task->paused_at = now();
        } elseif ($request->status === 'completed') {
            $task->completed_at = now();
            $task->repair_notes = $request->repair_notes;
            $task->completion_report = $request->completion_report;

            if ($request->hasFile('after_image')) {
                $task->after_image_path = $request->file('after_image')->store('uploads/maintenance', 'public');
            }
            if ($request->hasFile('proof_document')) {
                $task->proof_document_path = $request->file('proof_document')->store('uploads/proofs', 'public');
            }
        }

        $task->save();

        // Log Task Activity
        TaskActivity::create([
            'maintenance_task_id' => $task->id,
            'user_id' => $currentUser->id,
            'action' => $request->status,
            'description' => "Staff {$currentUser->name} updated status from '{$oldStatus}' to '{$request->status}'." . ($request->status === 'completed' ? " Repair Notes: " . $request->repair_notes : ""),
        ]);

        // Notify assigning officer
        if ($task->assignedBy) {
            $task->assignedBy->notify(new TaskStatusChangedNotification($task, $request->status));
        }

        return redirect()->back()->with('success', 'Task status updated to ' . ucfirst($request->status) . ' successfully.');
    }

    /**
     * Verify task completion (for officers/admins).
     */
    public function verifyTask(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,correction',
            'remarks' => 'nullable|string',
        ]);

        $task = MaintenanceTask::findOrFail($id);
        $currentUser = auth()->user();

        // Check scope (must be officer or admin, and if officer, in same territory)
        if (!in_array($currentUser->role, ['officer', 'admin'])) {
            return redirect()->back()->withErrors(['auth' => 'Unauthorized verification action.']);
        }

        if ($currentUser->role === 'officer' && $task->roadAnalysis->territory !== $currentUser->territory) {
            return redirect()->back()->withErrors(['auth' => 'Territory mismatch for verification action.']);
        }

        $task->status = $request->status;
        $task->officer_remarks = $request->remarks;
        $task->save();

        // Log Task Activity
        TaskActivity::create([
            'maintenance_task_id' => $task->id,
            'user_id' => $currentUser->id,
            'action' => $request->status,
            'description' => "Officer {$currentUser->name} reviewed task and marked it as '{$request->status}'." . ($request->remarks ? " Remarks: " . $request->remarks : ""),
        ]);

        // Notify staff & citizen
        if ($task->assignedStaff) {
            $task->assignedStaff->notify(new TaskReviewedNotification($task, $request->status, $request->remarks));
        }
        if ($task->roadAnalysis && $task->roadAnalysis->user) {
            $task->roadAnalysis->user->notify(new TaskReviewedNotification($task, $request->status, $request->remarks));
        }

        return redirect()->back()->with('success', 'Task verified and marked as ' . ucfirst($request->status));
    }

    /**
     * Add progress update (for staff members).
     */
    public function addProgressUpdate(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'progress_image' => 'nullable|image|max:10240',
        ]);

        $task = MaintenanceTask::findOrFail($id);
        $currentUser = auth()->user();

        // Check if assigned to this staff
        if ($task->assigned_to !== $currentUser->id) {
            return redirect()->back()->withErrors(['auth' => 'Unauthorized task access.']);
        }

        $imagePath = null;
        if ($request->hasFile('progress_image')) {
            $imagePath = $request->file('progress_image')->store('uploads/progress', 'public');
        }

        // Create TaskActivity
        $activity = TaskActivity::create([
            'maintenance_task_id' => $task->id,
            'user_id' => $currentUser->id,
            'action' => 'progress_update',
            'description' => $request->message,
            'image_path' => $imagePath,
            'created_at' => now(),
        ]);

        // Notify assigning officer
        if ($task->assignedBy) {
            $task->assignedBy->notify(new WorkProgressUpdatedNotification($task, $activity));
        }

        // Notify citizen who reported
        if ($task->roadAnalysis && $task->roadAnalysis->user) {
            $task->roadAnalysis->user->notify(new WorkProgressUpdatedNotification($task, $activity));
        }

        return redirect()->back()->with('success', 'Progress update posted and notifications sent successfully.');
    }
}
