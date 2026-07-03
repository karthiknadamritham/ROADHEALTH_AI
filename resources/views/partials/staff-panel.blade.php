<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Assigned Tasks</span>
            <span class="stat-value">{{ $myTasks->count() }}</span>
            <span class="stat-trend trend-up">All assignments</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">In Progress</span>
            <span class="stat-value" style="color: #FFD500;">{{ $myTasks->where('status', 'started')->count() }}</span>
            <span class="stat-trend trend-up">Currently working</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Completions</span>
            <span class="stat-value" style="color: #10b981;">{{ $myTasks->whereIn('status', ['completed', 'approved'])->count() }}</span>
            <span class="stat-trend trend-up">Resolved issues</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Correction Pending</span>
            <span class="stat-value" style="{{ $myTasks->where('status', 'correction')->count() > 0 ? 'color: #ef4444;' : '' }}">{{ $myTasks->where('status', 'correction')->count() }}</span>
            <span class="stat-trend trend-down">Needs Attention</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
    </div>
</div>

<div class="main-grid" style="grid-template-columns: 1.2fr 1.8fr; gap: 24px;">
    <!-- Left Panel: My Task Queue -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">My Assigned Repairs Queue</div>
        </div>
        
        <div style="display: flex; flex-direction: column; gap: 12px; max-height: 500px; overflow-y: auto; padding-right: 4px;">
            @forelse($myTasks as $index => $task)
                @php
                    $isCritical = $task->priority === 'emergency';
                    $badgeStyle = 'background: rgba(255,255,255,0.05); color: #9ca3af; border: 1px solid rgba(255,255,255,0.1);';
                    if($task->priority === 'emergency') $badgeStyle = 'background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3);';
                    elseif($task->priority === 'high') $badgeStyle = 'background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3);';
                    elseif($task->priority === 'medium') $badgeStyle = 'background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3);';
                    
                    $statusBadgeStyle = 'background: rgba(255,213,0,0.15); color: #FFD500; border: 1px solid rgba(255,213,0,0.3);';
                    if($task->status === 'started') $statusBadgeStyle = 'background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3);';
                    elseif($task->status === 'paused') $statusBadgeStyle = 'background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3);';
                    elseif($task->status === 'completed') $statusBadgeStyle = 'background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3);';
                    elseif($task->status === 'approved') $statusBadgeStyle = 'background: rgba(16,185,129,0.25); color: #10b981; border: 1px solid #10b981;';
                    elseif($task->status === 'correction') $statusBadgeStyle = 'background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid #ef4444;';
                @endphp
                
                <div class="task-card-item" onclick="selectTask({{ json_encode($task->load(['roadAnalysis', 'taskActivities.user'])) }})" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-left: 4px solid {{ $task->priority === 'emergency' ? '#ef4444' : ($task->priority === 'high' ? '#f59e0b' : '#3b82f6') }}; border-radius: 8px; padding: 16px; cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 800; color: white; font-size: 13px;">{{ $task->roadAnalysis->title ?? $task->roadAnalysis->scan_id }}</span>
                        <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; {{ $badgeStyle }}">{{ $task->priority }}</span>
                    </div>
                    
                    <div style="font-size: 11px; color: #d1d5db; display: flex; align-items: center; gap: 4px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;color:#9ca3af;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        {{ Str::limit($task->roadAnalysis->location, 30) }}
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px; margin-top: 4px;">
                        <span style="color: #9ca3af;">Due: {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</span>
                        <span style="font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; {{ $statusBadgeStyle }}">{{ $task->status }}</span>
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #6b7280; padding: 40px;">No repair tasks currently assigned to you.</div>
            @endforelse
        </div>
    </div>

    <!-- Right Panel: Task Details & Workspace -->
    <div class="panel" id="task-workspace" style="display: none;">
        <div class="panel-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px; margin-bottom: 16px;">
            <div style="display: flex; flex-direction: column;">
                <div class="panel-title" id="work-scan-id" style="font-size: 18px; font-weight: 800;">Scan ID</div>
                <div id="work-location" style="font-size: 12px; color: #9ca3af; margin-top: 4px;">Location Details</div>
            </div>
            <div style="text-align: right;">
                <span id="work-status-badge" style="font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 4px; text-transform: uppercase;">STATUS</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
            <div>
                <span style="font-size: 10px; color: #6b7280; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px;">Reported Issue Photo</span>
                <img id="work-before-img" src="" style="width: 100%; height: 160px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05);">
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div>
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; text-transform: uppercase; display: block;">Priority Level</span>
                    <div id="work-priority" style="font-size: 14px; font-weight: 800; color: white; margin-top: 2px;">HIGH</div>
                </div>
                <div>
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; text-transform: uppercase; display: block;">Deadline Date</span>
                    <div id="work-deadline" style="font-size: 14px; font-weight: 800; color: white; margin-top: 2px;">28 May 2026</div>
                </div>
                <div>
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; text-transform: uppercase; display: block;">Officer Instructions / Remarks</span>
                    <div id="work-officer-remarks" style="font-size: 12px; color: #9ca3af; margin-top: 2px; line-height: 1.4; font-style: italic;">No specific instructions.</div>
                </div>
                <div id="work-reporter-container" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px;">
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; text-transform: uppercase; display: block;">Reporter Info</span>
                    <div id="work-reporter-info" style="font-size: 12px; color: white; margin-top: 2px;">Reporter Name</div>
                </div>
                <div id="work-landmark-container">
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; text-transform: uppercase; display: block;">Landmark</span>
                    <div id="work-landmark" style="font-size: 12px; color: white; margin-top: 2px;">Landmark</div>
                </div>
                <div id="work-reporter-remarks-container">
                    <span style="font-size: 10px; color: #6b7280; font-weight: 700; text-transform: uppercase; display: block;">Reporter Remarks</span>
                    <div id="work-reporter-remarks" style="font-size: 12px; color: #9ca3af; margin-top: 2px; line-height: 1.4; font-style: italic;">Remarks</div>
                </div>
            </div>
        </div>

        <!-- Task Actions Controls -->
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; margin-bottom: 20px;" id="action-controls-section">
            <div style="font-size: 11px; font-weight: 700; color: white; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">Work Progress Controls</div>
            
            <div style="display: flex; gap: 12px;" id="work-status-buttons">
                <!-- Action Forms dynamically triggered via javascript -->
                <form id="started-status-form" method="POST" action="" style="margin: 0;">
                    @csrf
                    <input type="hidden" name="status" value="started">
                    <button type="submit" class="btn" style="background: #10b981; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        ▶️ Start Work
                    </button>
                </form>
                
                <form id="paused-status-form" method="POST" action="" style="margin: 0;">
                    @csrf
                    <input type="hidden" name="status" value="paused">
                    <button type="submit" class="btn" style="background: #f59e0b; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        ⏸️ Pause Work
                    </button>
                </form>

                <button onclick="openCompletionForm()" class="btn" style="background: #FFD500; color: black; border: none; padding: 10px 20px; font-weight: bold; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    ✔️ Mark Completed
                </button>
            </div>
        </div>

        <!-- Completion Form Expansion Section -->
        <div id="completion-form-section" style="display: none; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,213,0,0.2); border-radius: 8px; padding: 20px; margin-bottom: 20px;">
            <div style="font-size: 13px; font-weight: 800; color: #FFD500; text-transform: uppercase; margin-bottom: 16px; border-bottom: 1px solid rgba(255,213,0,0.1); padding-bottom: 8px;">
                Submit Work Completion Report
            </div>
            
            <form id="completed-status-form" method="POST" action="" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px; margin: 0;">
                @csrf
                <input type="hidden" name="status" value="completed">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="font-size: 11px; color: #9ca3af; font-weight: 700; display: block; margin-bottom: 6px;">Upload After-Repair Photo <span style="color: #ef4444;">*</span></label>
                        <input type="file" name="after_image" required accept="image/*" style="width: 100%; font-size: 12px; color: #9ca3af; background: #0c0c0c; padding: 8px; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px;">
                    </div>
                    <div>
                        <label style="font-size: 11px; color: #9ca3af; font-weight: 700; display: block; margin-bottom: 6px;">Upload Proof Document (PDF/Photo)</label>
                        <input type="file" name="proof_document" accept="image/*,application/pdf" style="width: 100%; font-size: 12px; color: #9ca3af; background: #0c0c0c; padding: 8px; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px;">
                    </div>
                </div>

                <div>
                    <label style="font-size: 11px; color: #9ca3af; font-weight: 700; display: block; margin-bottom: 6px;">Repair Notes <span style="color: #ef4444;">*</span></label>
                    <textarea name="repair_notes" required placeholder="Describe what actions were taken to fix the road..." style="width: 100%; height: 60px; background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; padding: 8px; font-size: 12px; font-family: 'Inter', sans-serif; resize: none; outline: none;"></textarea>
                </div>

                <div>
                    <label style="font-size: 11px; color: #9ca3af; font-weight: 700; display: block; margin-bottom: 6px;">Final Completion Report <span style="color: #ef4444;">*</span></label>
                    <textarea name="completion_report" required placeholder="Write official completion summary details for officer verification..." style="width: 100%; height: 60px; background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; padding: 8px; font-size: 12px; font-family: 'Inter', sans-serif; resize: none; outline: none;"></textarea>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="cancelCompletionForm()" class="btn btn-outline" style="border-color: #6b7280; color: #9ca3af; background: transparent; padding: 10px 20px; font-weight: bold; border-radius: 6px; cursor: pointer;">Cancel</button>
                    <button type="submit" class="btn" style="background: #10b981; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 6px; cursor: pointer;">Submit to Officer</button>
                </div>
            </form>
        </div>

        <!-- Progress Update Form Section -->
        <div id="progress-update-section" style="display: none; background: rgba(255,255,255,0.02); border: 1px solid rgba(59,130,246,0.2); border-radius: 8px; padding: 20px; margin-bottom: 20px;">
            <div style="font-size: 13px; font-weight: 800; color: #3b82f6; text-transform: uppercase; margin-bottom: 16px; border-bottom: 1px solid rgba(59,130,246,0.1); padding-bottom: 8px;">
                Post Work Progress Update
            </div>
            
            <form id="progress-update-form" method="POST" action="" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 16px; margin: 0;">
                @csrf
                <div>
                    <label style="font-size: 11px; color: #9ca3af; font-weight: 700; display: block; margin-bottom: 6px;">Update Message (Sent to Citizen &amp; Officer) <span style="color: #ef4444;">*</span></label>
                    <textarea name="message" required placeholder="Describe the current progress of the repair work..." style="width: 100%; height: 60px; background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: white; padding: 8px; font-size: 12px; font-family: 'Inter', sans-serif; resize: none; outline: none;"></textarea>
                </div>

                <div>
                    <label style="font-size: 11px; color: #9ca3af; font-weight: 700; display: block; margin-bottom: 6px;">Upload Progress Photo (Optional)</label>
                    <input type="file" name="progress_image" accept="image/*" style="width: 100%; font-size: 12px; color: #9ca3af; background: #0c0c0c; padding: 8px; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="submit" class="btn" style="background: #3b82f6; color: white; border: none; padding: 10px 20px; font-weight: bold; border-radius: 6px; cursor: pointer;">Post Update</button>
                </div>
            </form>
        </div>

        <!-- Task Timeline Log -->
        <div>
            <div style="font-size: 11px; font-weight: 700; color: white; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">Task Progression Timeline</div>
            <div id="work-timeline" style="display: flex; flex-direction: column; gap: 14px; max-height: 180px; overflow-y: auto; padding-right: 6px;">
                <!-- Timeline items rendered dynamically -->
            </div>
        </div>
    </div>

    <!-- Empty Workspace State -->
    <div class="panel" id="task-workspace-empty" style="display: flex; align-items: center; justify-content: center; text-align: center; padding: 80px 20px;">
        <div>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 64px; height: 64px; color: #6b7280; margin-bottom: 16px; opacity: 0.6;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <h3 style="color: white; margin-bottom: 6px; font-size: 16px;">Select a Task to Begin</h3>
            <p style="color: #9ca3af; font-size: 13px; max-width: 320px; margin: 0 auto;">Choose any assigned work order from the left sidebar queue to inspect instructions, track status, and upload repair logs.</p>
        </div>
    </div>
</div>

<script>
    let activeTask = null;

    function selectTask(task) {
        activeTask = task;
        
        // Hide empty workspace and show detailed work zone
        document.getElementById('task-workspace-empty').style.display = 'none';
        document.getElementById('task-workspace').style.display = 'flex';
        
        // Reset completion form
        cancelCompletionForm();

        // Populate fields
        document.getElementById('work-scan-id').textContent = task.road_analysis.title || task.road_analysis.scan_id;
        document.getElementById('work-location').textContent = task.road_analysis.location;
        document.getElementById('work-before-img').src = '/storage/' + task.road_analysis.image_path;
        document.getElementById('work-priority').textContent = task.priority.toUpperCase();
        document.getElementById('work-deadline').textContent = new Date(task.deadline).toLocaleDateString('en-GB', {day: 'numeric', month: 'short', year: 'numeric'});
        document.getElementById('work-officer-remarks').textContent = task.officer_remarks || 'No remarks provided.';

        // Populate reporter info
        if (task.road_analysis.user) {
            document.getElementById('work-reporter-info').textContent = 
                task.road_analysis.user.name + 
                (task.road_analysis.user.phone ? ' (' + task.road_analysis.user.phone + ')' : '') + 
                ' - ' + task.road_analysis.user.email;
            document.getElementById('work-reporter-container').style.display = 'block';
        } else {
            document.getElementById('work-reporter-info').textContent = 'Guest Reporter';
            document.getElementById('work-reporter-container').style.display = 'block';
        }
        
        // Populate landmark
        if (task.road_analysis.landmark) {
            document.getElementById('work-landmark').textContent = task.road_analysis.landmark;
            document.getElementById('work-landmark-container').style.display = 'block';
        } else {
            document.getElementById('work-landmark-container').style.display = 'none';
        }

        // Populate reporter remarks
        if (task.road_analysis.remarks) {
            document.getElementById('work-reporter-remarks').textContent = task.road_analysis.remarks;
            document.getElementById('work-reporter-remarks-container').style.display = 'block';
        } else {
            document.getElementById('work-reporter-remarks-container').style.display = 'none';
        }

        // Configure action paths
        const actionUrl = '/dashboard/maintenance/update-status/' + task.id;
        document.getElementById('started-status-form').action = actionUrl;
        document.getElementById('paused-status-form').action = actionUrl;
        document.getElementById('completed-status-form').action = actionUrl;

        // Configure status badge styling and controls visibility
        const badge = document.getElementById('work-status-badge');
        badge.textContent = task.status.toUpperCase();
        
        // Setup status styles
        badge.className = ''; // clear classes
        let badgeStyle = "font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 4px; text-transform: uppercase;";
        if(task.status === 'assigned') {
            badge.style = badgeStyle + "background: rgba(255,213,0,0.15); color: #FFD500; border: 1px solid rgba(255,213,0,0.3);";
        } else if(task.status === 'started') {
            badge.style = badgeStyle + "background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3);";
        } else if(task.status === 'paused') {
            badge.style = badgeStyle + "background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3);";
        } else if(task.status === 'completed') {
            badge.style = badgeStyle + "background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3);";
        } else if(task.status === 'approved') {
            badge.style = badgeStyle + "background: rgba(16,185,129,0.25); color: #10b981; border: 1px solid #10b981;";
        } else if(task.status === 'correction') {
            badge.style = badgeStyle + "background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid #ef4444;";
        }

        // Show/hide action controls based on status
        const controlsSection = document.getElementById('action-controls-section');
        const progressSection = document.getElementById('progress-update-section');
        const startForm = document.getElementById('started-status-form');
        const pauseForm = document.getElementById('paused-status-form');
        const completeBtn = document.querySelector('button[onclick="openCompletionForm()"]');

        if (task.status === 'completed' || task.status === 'approved') {
            controlsSection.style.display = 'none';
            progressSection.style.display = 'none';
        } else {
            controlsSection.style.display = 'block';
            progressSection.style.display = 'block';
            document.getElementById('progress-update-form').action = '/dashboard/maintenance/progress/' + task.id;
            
            // Adjust buttons based on current sub-states
            if (task.status === 'assigned' || task.status === 'paused') {
                startForm.style.display = 'inline-block';
                pauseForm.style.display = 'none';
                completeBtn.style.display = 'none';
            } else if (task.status === 'started') {
                startForm.style.display = 'none';
                pauseForm.style.display = 'inline-block';
                completeBtn.style.display = 'inline-block';
            } else if (task.status === 'correction') {
                startForm.style.display = 'inline-block';
                pauseForm.style.display = 'none';
                completeBtn.style.display = 'inline-block';
            }
        }

        // Populate Timeline Log
        const timelineList = document.getElementById('work-timeline');
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
                    activityTitle = '<span style="color: #3b82f6; font-size: 11px; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 2px;">Work Progress Update</span>';
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
    }

    function openCompletionForm() {
        document.getElementById('completion-form-section').style.display = 'block';
        document.getElementById('work-status-buttons').style.display = 'none';
    }

    function cancelCompletionForm() {
        document.getElementById('completion-form-section').style.display = 'none';
        document.getElementById('work-status-buttons').style.display = 'flex';
    }
</script>
