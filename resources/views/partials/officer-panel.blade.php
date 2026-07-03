<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Total Complaints</span>
            <span class="stat-value">{{ $totalAnalyses }}</span>
            <span class="stat-trend trend-up">Active Territory</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Avg PCI Score</span>
            <span class="stat-value">{{ $avgPciScore }} <span style="font-size: 14px; color: #6b7280;">/100</span></span>
            <span class="stat-trend {{ $avgPciScore >= 55 ? 'trend-up' : 'trend-down' }}">{{ $avgPciScore >= 55 ? 'Overall Fair' : 'Overall Poor' }}</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Critical Issues</span>
            <span class="stat-value" style="{{ $highRiskCount > 0 ? 'color: #ef4444;' : '' }}">{{ $highRiskCount }}</span>
            <span class="stat-trend trend-down">Urgent Action</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Pending Staff</span>
            <span class="stat-value" style="{{ $pendingStaff->count() > 0 ? 'color: #FFD500;' : '' }}">{{ $pendingStaff->count() }}</span>
            <span class="stat-trend trend-up">Awaiting Verification</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        </div>
    </div>
</div>

<div class="main-grid" style="margin-bottom: 24px;">
    <!-- Pending Staff Approvals -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Pending Staff Registrations</div>
            <span style="font-size: 12px; color: #6b7280;">Territory: {{ auth()->user()->territory }}</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Staff Details</th>
                        <th>Dept / ID</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingStaff as $staff)
                    <tr>
                        <td>
                            <div class="td-scan">
                                @if($staff->profile_photo_path)
                                    <img src="{{ asset('storage/' . $staff->profile_photo_path) }}" alt="Profile" class="td-img" style="border-radius: 50%;">
                                @else
                                    <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #6b7280; font-weight: bold;">{{ substr($staff->name, 0, 1) }}</div>
                                @endif
                                <div>
                                    <div style="color: white; font-weight: 600;">{{ $staff->name }}</div>
                                    <div style="font-size: 11px; color: #6b7280;">{{ $staff->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ $staff->department }}</div>
                            <div style="font-size: 11px; color: #6b7280;">ID: {{ $staff->employee_id }}</div>
                        </td>
                        <td>{{ $staff->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <button class="action-btn" onclick="openStaffApprovalModal({{ json_encode($staff) }})" style="color: #FFD500;">Review</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6b7280; padding: 24px;">No pending staff registrations.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Operations Quick Actions -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Operations Menu</div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px; padding-top: 10px;">
            <a href="/dashboard/maintenance" class="btn" style="text-decoration: none; padding: 12px; border-radius: 8px; color: black; background: #FFD500; font-weight: 700; text-align: center; display: block; font-size: 13px;">
                📂 Open Maintenance Control Center
            </a>
            <a href="/upload" class="btn" style="text-decoration: none; padding: 12px; border-radius: 8px; color: white; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); font-weight: 600; text-align: center; display: block; font-size: 13px; transition: background 0.2s;">
                📸 Upload & Analyze Road Images
            </a>
            <a href="/network" class="btn" style="text-decoration: none; padding: 12px; border-radius: 8px; color: white; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); font-weight: 600; text-align: center; display: block; font-size: 13px; transition: background 0.2s;">
                🗺️ Road Network Mapping
            </a>
        </div>
    </div>
</div>

<div class="main-grid" style="margin-bottom: 24px;">
    <!-- Unassigned complaints queue -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Unassigned Issues (Awaiting Dispatch)</div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Complaint details</th>
                        <th>Location</th>
                        <th>Reporter &amp; Remarks</th>
                        <th>PCI Score / Condition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unassignedComplaints as $complaint)
                    <tr>
                        <td>
                            <div class="td-scan">
                                @if($complaint->image_path)
                                    <img src="{{ asset('storage/' . $complaint->image_path) }}" alt="Scan" class="td-img">
                                @else
                                    <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 4px;"></div>
                                @endif
                                <div>
                                    <div style="color: white; font-weight: 600;">{{ $complaint->title ?? $complaint->scan_id }}</div>
                                    <div style="font-size: 11px; color: #6b7280;">Scan: {{ $complaint->scan_id }} &bull; {{ $complaint->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ Str::limit($complaint->location, 35) }}</div>
                            <div style="font-size: 11px; color: #6b7280;">Lat: {{ $complaint->latitude }} Lng: {{ $complaint->longitude }}</div>
                        </td>
                        <td>
                            @if($complaint->user)
                                <div style="color: white; font-weight: 600;">{{ $complaint->user->name }}</div>
                                <div style="font-size: 11px; color: #9ca3af;">{{ $complaint->user->phone ?? 'No Phone' }} &bull; {{ $complaint->user->email }}</div>
                            @else
                                <div style="color: white; font-weight: 600;">Guest Reporter</div>
                            @endif
                            @if($complaint->remarks)
                                <div style="font-size: 11px; color: #FFD500; margin-top: 4px; font-style: italic; max-width: 250px; white-space: normal;">"{{ Str::limit($complaint->remarks, 100) }}"</div>
                            @endif
                            @if($complaint->landmark)
                                <div style="font-size: 11px; color: #9ca3af; margin-top: 2px;">Landmark: {{ $complaint->landmark }}</div>
                            @endif
                        </td>
                        <td>
                            <div style="color: white; font-weight: 700;">{{ $complaint->pci_score }}/100</div>
                            <div>
                                @if($complaint->pci_score >= 75)
                                    <span class="badge-status badge-good">GOOD</span>
                                @elseif($complaint->pci_score >= 55)
                                    <span class="badge-status badge-fair">FAIR</span>
                                @else
                                    <span class="badge-status badge-poor">POOR</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <button class="action-btn" onclick="openAssignmentModal({{ json_encode($complaint) }})" style="color: #FFD500;">Assign Work</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6b7280; padding: 24px;">No unassigned complaints in your territory.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Completed Works awaiting verification -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title" style="color: #10b981;">Completed Works (Awaiting Approval)</div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Task / Staff</th>
                        <th>Completed At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completedTasks as $task)
                    <tr>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ $task->roadAnalysis->title ?? $task->roadAnalysis->scan_id }}</div>
                            <div style="font-size: 11px; color: #6b7280;">Staff: {{ $task->assignedStaff->name }}</div>
                        </td>
                        <td>
                            <div style="color: white;">{{ \Carbon\Carbon::parse($task->completed_at)->format('d M Y, H:i') }}</div>
                            <div style="font-size: 11px; color: #6b7280;">{{ \Carbon\Carbon::parse($task->completed_at)->diffForHumans() }}</div>
                        </td>
                        <td>
                            <button class="action-btn" onclick="openVerificationModal({{ json_encode($task->load(['roadAnalysis', 'assignedStaff'])) }})" style="color: #10b981;">Verify Proof</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #6b7280; padding: 24px;">No pending completions to verify.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Grid 3: Active Tasks in Progress -->
<div class="main-grid" style="margin-bottom: 24px; grid-template-columns: 1fr;">
    <div class="panel" style="grid-column: span 1;">
        <div class="panel-header">
            <div class="panel-title" style="color: #3b82f6;">Active Maintenance Tasks (In Progress)</div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Task / Scan ID</th>
                        <th>Location</th>
                        <th>Assigned Staff</th>
                        <th>Status</th>
                        <th>Deadline</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeTasks as $task)
                    @php
                        $statusBadgeStyle = 'background: rgba(255,213,0,0.15); color: #FFD500; border: 1px solid rgba(255,213,0,0.3);';
                        if($task->status === 'started') $statusBadgeStyle = 'background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3);';
                        elseif($task->status === 'paused') $statusBadgeStyle = 'background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3);';
                        elseif($task->status === 'correction') $statusBadgeStyle = 'background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid #ef4444;';
                    @endphp
                    <tr>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ $task->roadAnalysis->title ?? $task->roadAnalysis->scan_id }}</div>
                            <div style="font-size: 11px; color: #6b7280;">Priority: {{ ucfirst($task->priority) }}</div>
                        </td>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ Str::limit($task->roadAnalysis->location, 40) }}</div>
                            <div style="font-size: 11px; color: #6b7280;">Lat: {{ $task->roadAnalysis->latitude }} Lng: {{ $task->roadAnalysis->longitude }}</div>
                        </td>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ $task->assignedStaff->name }}</div>
                            <div style="font-size: 11px; color: #6b7280;">{{ $task->assignedStaff->email }}</div>
                        </td>
                        <td>
                            <span style="font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; {{ $statusBadgeStyle }}">{{ $task->status }}</span>
                        </td>
                        <td>
                            <div style="color: white;">{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</div>
                            <div style="font-size: 11px; color: #6b7280;">{{ \Carbon\Carbon::parse($task->deadline)->diffForHumans() }}</div>
                        </td>
                        <td>
                            <button class="action-btn" onclick="openProgressModal({{ json_encode($task->load('taskActivities.user')) }})" style="color: #3b82f6; font-weight: 700; background: transparent; border: none; cursor: pointer;">View Progress</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #6b7280; padding: 24px;">No active tasks in progress in your territory.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal 1: Staff Approval Modal -->
<div class="modal-overlay" id="modal-approve-staff">
    <div class="modal-box" style="width: 550px;">
        <button class="modal-close" onclick="closeStaffApprovalModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title">
            Review Staff Registration
        </div>
        
        <div style="display: flex; gap: 16px; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 16px;">
            <img id="staff-rev-photo" src="" alt="Profile Photo" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #FFD500;">
            <div>
                <h4 style="color: white; font-size: 16px; font-weight: bold;" id="staff-rev-name">Staff Name</h4>
                <p style="color: #9ca3af; font-size: 12px;" id="staff-rev-email">email@example.com</p>
                <p style="color: #6b7280; font-size: 11px; margin-top: 4px;" id="staff-rev-geography">Territory &bull; Zone</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div>
                <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase;">Employee ID</span>
                <div style="color: white; font-weight: bold; font-size: 13px;" id="staff-rev-id">EMP-001</div>
            </div>
            <div>
                <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase;">Department</span>
                <div style="color: white; font-weight: bold; font-size: 13px;" id="staff-rev-dept">Road Maintenance</div>
            </div>
        </div>

        <div>
            <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px;">Government ID Document</span>
            <div id="staff-rev-id-doc-container" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px; text-align: center;">
                <a id="staff-rev-id-link" href="" target="_blank" style="color: #FFD500; font-weight: bold; font-size: 12px; display: inline-flex; align-items: center; gap: 8px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download / View Government ID File
                </a>
            </div>
        </div>

        <form id="staff-approval-form" action="" method="POST" style="display:flex; flex-direction:column; gap:16px; margin:0;">
            @csrf
            <div>
                <label style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px;">Remarks / Reason (Optional)</label>
                <textarea name="remarks" placeholder="Enter reason for approval or rejection..." style="width:100%; height:80px; background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; padding: 10px; font-size: 13px; font-family:'Inter',sans-serif; outline:none; resize: none;"></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="submit" name="status" value="rejected" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444; background: transparent; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Reject Staff</button>
                <button type="submit" name="status" value="approved" class="btn" style="background: #10b981; color: white; border: none; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Approve Staff</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Assign Task Modal -->
<div class="modal-overlay" id="modal-assign-task">
    <div class="modal-box" style="width: 500px;">
        <button class="modal-close" onclick="closeAssignmentModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title">
            Assign Maintenance Task
        </div>
        <p style="color: #9ca3af; font-size: 13px; margin-top: -8px; white-space: pre-line;" id="assign-scan-details">Dispatching repair order for Scan</p>
        
        <form action="{{ route('maintenance.assign') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px; margin: 0;">
            @csrf
            <input type="hidden" name="road_analysis_id" id="assign-analysis-id" value="">
            
            <div class="control-group">
                <span class="control-lbl">Assign to Field Staff</span>
                <select name="assigned_to" class="select-staff" required style="background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); color: white; padding: 10px; border-radius: 8px; font-size: 13px; width: 100%;">
                    <option value="">Select Staff Member...</option>
                    @foreach($activeStaff as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="control-group">
                <span class="control-lbl">Priority Level</span>
                <select name="priority" class="select-staff" style="background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); color: white; padding: 10px; border-radius: 8px; font-size: 13px; width: 100%;">
                    <option value="emergency">Emergency Response</option>
                    <option value="high">High Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>
            </div>

            <div class="control-group">
                <span class="control-lbl">Deadline</span>
                <input type="date" name="deadline" required min="{{ date('Y-m-d') }}" style="background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); color: white; padding: 10px; border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif;">
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                <button type="submit" class="btn" style="background: #FFD500; color: black; border: none; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Dispatch Staff
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 3: Verify Task Modal -->
<div class="modal-overlay" id="modal-verify-task">
    <div class="modal-box" style="width: 600px; max-height: 90vh; overflow-y: auto;">
        <button class="modal-close" onclick="closeVerificationModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title" style="color: #10b981;">
            Verify Field Work Completion
        </div>
        <p style="color: #9ca3af; font-size: 13px; margin-top: -8px;" id="verify-task-scan-id">Reviewing work for scan</p>
        
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px;">
            <div style="font-weight: 700; color: white; font-size: 12px; text-transform: uppercase;">Repair Proofs</div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; display: block; margin-bottom: 4px;">Before Scan Image</span>
                    <img id="verify-img-before" src="" style="width:100%; height:140px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1);">
                </div>
                <div>
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; display: block; margin-bottom: 4px;">After Repair Image</span>
                    <img id="verify-img-after" src="" style="width:100%; height:140px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1);">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 8px;">
                <div>
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; display: block;">Repair Notes</span>
                    <div style="font-size: 12px; color: white; padding-top: 4px;" id="verify-notes">Staff notes here...</div>
                </div>
                <div>
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; display: block;">Completion Report</span>
                    <div style="font-size: 12px; color: white; padding-top: 4px;" id="verify-report">Completion report here...</div>
                </div>
            </div>

            <div id="verify-proof-doc-container" style="display: none; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px; margin-top: 8px;">
                <a id="verify-proof-doc-link" href="" target="_blank" style="color: #FFD500; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download Repair Proof Document (PDF/Photo)
                </a>
            </div>
        </div>

        <form id="verification-form" action="" method="POST" style="display: flex; flex-direction: column; gap: 16px; margin: 0;">
            @csrf
            <div>
                <label style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px;">Rejection / Correction Notes (Optional)</label>
                <textarea name="remarks" placeholder="Provide instructions if returning for correction..." style="width:100%; height:80px; background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; padding: 10px; font-size: 13px; font-family:'Inter',sans-serif; outline:none; resize: none;"></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="submit" name="status" value="correction" class="btn btn-outline" style="border-color: #f59e0b; color: #f59e0b; background: transparent; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Return for Correction</button>
                <button type="submit" name="status" value="approved" class="btn" style="background: #10b981; color: white; border: none; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Verify &amp; Approve Repair</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal 5: View Work Progress Modal -->
<div class="modal-overlay" id="modal-view-progress">
    <div class="modal-box" style="width: 550px; max-height: 90vh; overflow-y: auto;">
        <button class="modal-close" onclick="closeProgressModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title" style="color: #3b82f6;">
            Work Progress Log
        </div>
        <p style="color: #9ca3af; font-size: 13px; margin-top: -8px;" id="progress-task-scan-id">Reviewing progress for Scan ID</p>
        
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; text-align: left;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase;">Location</span>
                    <div style="color: white; font-weight: 600; font-size: 12px; margin-top: 2px;" id="progress-location">Location details...</div>
                </div>
                <div>
                    <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase;">Assigned Staff</span>
                    <div style="color: white; font-weight: 600; font-size: 12px; margin-top: 2px;" id="progress-staff">Staff name...</div>
                </div>
            </div>
        </div>

        <div style="text-align: left; margin-bottom: 12px;">
            <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 10px;">Repair Work Timeline</span>
            <div id="progress-timeline-list" style="display: flex; flex-direction: column; gap: 16px; max-height: 300px; overflow-y: auto; padding-right: 6px;">
                <!-- Filled dynamically -->
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 16px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
            <button class="btn btn-outline" onclick="closeProgressModal()" style="border-color: rgba(255,255,255,0.1); color: #9ca3af; background: transparent; padding: 10px 20px; font-weight: bold; border-radius: 8px; cursor: pointer;">Close Logs</button>
        </div>
    </div>
</div>

<script>
    // Staff Approval Modal Functions
    function openStaffApprovalModal(staff) {
        document.getElementById('staff-rev-name').textContent = staff.name;
        document.getElementById('staff-rev-email').textContent = staff.email;
        document.getElementById('staff-rev-geography').textContent = staff.territory + ' • ' + staff.zone + ' • ' + staff.ward;
        document.getElementById('staff-rev-id').textContent = staff.employee_id || 'N/A';
        document.getElementById('staff-rev-dept').textContent = staff.department || 'N/A';
        
        const photoUrl = staff.profile_photo_path ? '/storage/' + staff.profile_photo_path : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(staff.name);
        document.getElementById('staff-rev-photo').src = photoUrl;

        if (staff.government_id_path) {
            document.getElementById('staff-rev-id-link').href = '/storage/' + staff.government_id_path;
            document.getElementById('staff-rev-id-doc-container').style.display = 'block';
        } else {
            document.getElementById('staff-rev-id-doc-container').style.display = 'none';
        }

        document.getElementById('staff-approval-form').action = '/dashboard/approve-user/' + staff.id;
        document.getElementById('modal-approve-staff').classList.add('active');
    }

    function closeStaffApprovalModal() {
        document.getElementById('modal-approve-staff').classList.remove('active');
    }

    // Task Assignment Modal Functions
    function openAssignmentModal(complaint) {
        let detailsText = "Dispatching repair order for: " + (complaint.title || complaint.scan_id) + " - " + complaint.location;
        if (complaint.remarks) {
            detailsText += "\n\nReporter Remarks: \"" + complaint.remarks + "\"";
        }
        document.getElementById('assign-scan-details').textContent = detailsText;
        document.getElementById('assign-analysis-id').value = complaint.id;
        document.getElementById('modal-assign-task').classList.add('active');
    }

    function closeAssignmentModal() {
        document.getElementById('modal-assign-task').classList.remove('active');
    }

    // Task Verification Modal Functions
    function openVerificationModal(task) {
        document.getElementById('verify-task-scan-id').textContent = "Reviewing completed work for Scan ID: " + task.road_analysis.scan_id;
        document.getElementById('verify-notes').textContent = task.repair_notes || 'N/A';
        document.getElementById('verify-report').textContent = task.completion_report || 'N/A';
        
        const imgBefore = task.road_analysis.image_path ? '/storage/' + task.road_analysis.image_path : '';
        const imgAfter = task.after_image_path ? '/storage/' + task.after_image_path : '';
        
        document.getElementById('verify-img-before').src = imgBefore;
        document.getElementById('verify-img-after').src = imgAfter;

        if (task.proof_document_path) {
            document.getElementById('verify-proof-doc-link').href = '/storage/' + task.proof_document_path;
            document.getElementById('verify-proof-doc-container').style.display = 'block';
        } else {
            document.getElementById('verify-proof-doc-container').style.display = 'none';
        }

        document.getElementById('verification-form').action = '/dashboard/maintenance/verify/' + task.id;
        document.getElementById('modal-verify-task').classList.add('active');
    }

    function closeVerificationModal() {
        document.getElementById('modal-verify-task').classList.remove('active');
    }


    function openProgressModal(task) {
        document.getElementById('progress-task-scan-id').textContent = "Reviewing progress for Scan ID: " + task.road_analysis.scan_id;
        document.getElementById('progress-location').textContent = task.road_analysis.location;
        document.getElementById('progress-staff').textContent = task.assigned_staff.name + ' (' + task.assigned_staff.email + ')';
        
        const timelineList = document.getElementById('progress-timeline-list');
        timelineList.innerHTML = '';
        
        if (task.task_activities && task.task_activities.length > 0) {
            task.task_activities.forEach(activity => {
                const date = new Date(activity.created_at).toLocaleString('en-GB', {day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'});
                const username = activity.user ? activity.user.name : 'System';
                
                let activityDotColor = '#FFD500';
                let activityTitle = '';
                let progressImageHtml = '';
                
                if(activity.action === 'started' || activity.action === 'approved') activityDotColor = '#10b981';
                else if(activity.action === 'paused') activityDotColor = '#f59e0b';
                else if(activity.action === 'correction') activityDotColor = '#ef4444';
                else if(activity.action === 'progress_update') {
                    activityDotColor = '#3b82f6';
                    activityTitle = '<span style="color: #3b82f6; font-size: 10px; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 2px;">Work Progress Update</span>';
                    if (activity.image_path) {
                        progressImageHtml = `<img src="/storage/${activity.image_path}" style="max-width: 100%; height: auto; max-height: 120px; object-fit: cover; border-radius: 6px; margin-top: 6px; border: 1px solid rgba(255,255,255,0.1);">`;
                    }
                }

                timelineList.innerHTML += `
                    <div style="display: flex; gap: 12px; border-left: 2px solid rgba(255,255,255,0.05); padding-left: 14px; position: relative;">
                        <div style="position: absolute; left: -5px; top: 4px; width: 8px; height: 8px; border-radius: 50%; background: ${activityDotColor}; box-shadow: 0 0 6px ${activityDotColor};"></div>
                        <div style="display: flex; flex-direction: column; gap: 2px; text-align: left; align-items: flex-start;">
                            ${activityTitle}
                            <span style="color: white; font-size: 12px; font-weight: 600; text-align: left;">${activity.description}</span>
                            ${progressImageHtml}
                            <span style="color: #6b7280; font-size: 10px; margin-top: 2px;">By: ${username} • ${date}</span>
                        </div>
                    </div>
                `;
            });
        } else {
            timelineList.innerHTML = '<div style="color: #6b7280; font-size: 11px;">No logs recorded yet.</div>';
        }
        
        document.getElementById('modal-view-progress').classList.add('active');
    }

    function closeProgressModal() {
        document.getElementById('modal-view-progress').classList.remove('active');
    }
</script>
