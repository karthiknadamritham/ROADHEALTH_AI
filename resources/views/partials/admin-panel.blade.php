<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Total Inspections</span>
            <span class="stat-value">{{ $totalAnalyses }}</span>
            <span class="stat-trend trend-up">All Territories</span>
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
            <span class="stat-title">Pending Officers</span>
            <span class="stat-value" style="{{ $pendingOfficers->count() > 0 ? 'color: #FFD500;' : '' }}">{{ $pendingOfficers->count() }}</span>
            <span class="stat-trend trend-up">Awaiting Approval</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        </div>
    </div>
</div>

<div class="main-grid" style="margin-bottom: 24px;">
    <!-- Pending Officer Approvals -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Pending Officer Registrations</div>
            <span style="font-size: 12px; color: #6b7280;">Global Jurisdiction (All Territories)</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Officer Details</th>
                        <th>Dept / ID</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingOfficers as $officer)
                    <tr>
                        <td>
                            <div class="td-scan">
                                @if($officer->profile_photo_path)
                                    <img src="{{ asset('storage/' . $officer->profile_photo_path) }}" alt="Profile" class="td-img" style="border-radius: 50%;">
                                @else
                                    <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #6b7280; font-weight: bold;">{{ substr($officer->name, 0, 1) }}</div>
                                @endif
                                <div>
                                    <div style="color: white; font-weight: 600;">{{ $officer->name }}</div>
                                    <div style="font-size: 11px; color: #6b7280;">{{ $officer->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ $officer->department }}</div>
                            <div style="font-size: 11px; color: #6b7280;">ID: {{ $officer->employee_id }}</div>
                        </td>
                        <td>{{ $officer->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <button class="action-btn" onclick="openApprovalModal({{ json_encode($officer) }})" style="color: #FFD500;">Review</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6b7280; padding: 24px;">No pending officer registrations.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Issues by Zone -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Inspection Coverage by Zone</div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 16px; padding-top: 10px;">
            @forelse($zoneCounts as $zc)
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                    <span style="color: white; font-weight: 600;">Zone: {{ $zc->zone }}</span>
                    <span style="color: #FFD500; font-weight: 700;">{{ $zc->total }} scan(s)</span>
                </div>
                <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden;">
                    <div style="width: {{ $totalAnalyses > 0 ? min(100, ($zc->total / $totalAnalyses) * 100) : 0 }}%; height: 100%; background: #FFD500; border-radius: 4px;"></div>
                </div>
            </div>
            @empty
            <div style="text-align: center; color: #6b7280; padding: 24px;">No zone distribution statistics available.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="main-grid">
    <!-- Escalated tasks -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title" style="color: #ef4444; display: flex; align-items: center; gap: 8px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Escalated Field Works (Overdue)
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Scan ID &amp; Location</th>
                        <th>Assigned Staff</th>
                        <th>Deadline</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($escalatedTasks as $task)
                    <tr>
                        <td>
                            <div style="color: white; font-weight: 600;">{{ $task->roadAnalysis->scan_id }}</div>
                            <div style="font-size: 11px; color: #6b7280;">{{ $task->roadAnalysis->location }}</div>
                        </td>
                        <td>{{ $task->assignedStaff ? $task->assignedStaff->name : 'N/A' }}</td>
                        <td style="color: #ef4444; font-weight: 600;">{{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</td>
                        <td>
                            <span class="badge-status badge-poor">{{ strtoupper($task->priority) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #6b7280; padding: 24px;">No overdue or escalated tasks.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Live activity feed -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Activity Feed (All Systems Logs)</div>
        </div>
        <div style="display: flex; flex-direction: column; gap: 16px; max-height: 250px; overflow-y: auto; padding-right: 8px;">
            @forelse($recentActivities as $activity)
            <div style="display: flex; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 12px;">
                <div style="width: 8px; height: 8px; border-radius: 50%; background: #FFD500; margin-top: 4px; flex-shrink:0;"></div>
                <div style="display:flex; flex-direction:column; gap:2px;">
                    <span style="color: white; font-size:12px; font-weight: 600;">{{ $activity->description }}</span>
                    <span style="color: #6b7280; font-size: 10px;">By: {{ $activity->user ? $activity->user->name : 'System' }} &bull; {{ $activity->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @empty
            <div style="text-align: center; color: #6b7280; padding: 24px;">No activities logged globally.</div>
            @endforelse
        </div>
    </div>
</div>
