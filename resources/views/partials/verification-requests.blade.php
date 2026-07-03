<!-- Header Panel -->
<div class="panel" style="margin-bottom: 24px; background: rgba(18,18,18,0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px;">
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 16px;">
        <div>
            <h3 class="panel-title" style="font-size: 20px; color: white; font-weight: 800; letter-spacing: -0.02em; display: flex; align-items: center; gap: 8px;">
                <span style="color: #FFD500;">🛡️</span> Account Verification &amp; Log Center
            </h3>
            <p style="font-size: 11px; color: #6b7280; margin-top: 4px;">Manage pending registrations and audit historical verification actions for municipal officers and field staff.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <span style="background: rgba(255,213,0,0.05); color: #FFD500; border: 1px solid rgba(255,213,0,0.2); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                ⏳ {{ $pendingUsers->count() }} Reviews Pending
            </span>
            <span style="background: rgba(16,185,129,0.05); color: #10b981; border: 1px solid rgba(16,185,129,0.2); padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                📜 {{ $processedUsers->count() }} Historical Logs
            </span>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-top: 20px;">
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(255,213,0,0.05); display: flex; align-items: center; justify-content: center; color: #FFD500; font-size: 18px;">👮</div>
            <div>
                <span style="font-size: 10px; color: #6b7280; font-weight: bold; text-transform: uppercase; display: block;">Pending Review</span>
                <span style="font-size: 18px; font-weight: 800; color: white;">{{ $pendingUsers->count() }}</span>
            </div>
        </div>
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(16,185,129,0.05); display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 18px;">✓</div>
            <div>
                <span style="font-size: 10px; color: #6b7280; font-weight: bold; text-transform: uppercase; display: block;">Accepted Accounts</span>
                <span style="font-size: 18px; font-weight: 800; color: #10b981;">{{ $processedUsers->where('status', 'approved')->count() }}</span>
            </div>
        </div>
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(239,68,68,0.05); display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 18px;">✕</div>
            <div>
                <span style="font-size: 10px; color: #6b7280; font-weight: bold; text-transform: uppercase; display: block;">Rejected Accounts</span>
                <span style="font-size: 18px; font-weight: 800; color: #ef4444;">{{ $processedUsers->where('status', 'rejected')->count() }}</span>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 1: Pending Review Queue -->
<div class="panel" style="background: rgba(18,18,18,0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden; padding: 0; margin-bottom: 32px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.01);">
        <h4 style="font-size: 14px; font-weight: 800; color: #FFD500; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
            <span>⏳</span> Pending Review Queue
        </h4>
    </div>
    
    @if($pendingUsers->isEmpty())
        <div style="text-align: center; color: #6b7280; padding: 48px 24px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(16,185,129,0.03); display: inline-flex; align-items: center; justify-content: center; color: #10b981; font-size: 24px; margin-bottom: 16px;">✓</div>
            <h4 style="font-size: 15px; font-weight: 700; color: white; margin-bottom: 4px;">No Pending Reviews</h4>
            <p style="font-size: 11px; max-width: 380px; margin: 0 auto; line-height: 1.5; color: #6b7280;">All incoming municipal registration applications have been successfully reviewed.</p>
        </div>
    @else
        <div class="table-container" style="margin: 0;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Employee / Role</th>
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Geography &amp; Territory</th>
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Employment Info</th>
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Verification Docs</th>
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.02); transition: all 0.2s ease; background: rgba(255,255,255,0.005);" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='rgba(255,255,255,0.005)'">
                        <td style="padding: 16px 24px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid {{ $user->role === 'officer' ? '#FFD500' : '#3b82f6' }};">
                                @else
                                    <div style="width: 38px; height: 38px; background: {{ $user->role === 'officer' ? 'rgba(255,213,0,0.1)' : 'rgba(59,130,246,0.1)' }}; color: {{ $user->role === 'officer' ? '#FFD500' : '#3b82f6' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; border: 1px solid {{ $user->role === 'officer' ? 'rgba(255,213,0,0.3)' : 'rgba(59,130,246,0.3)' }};">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="color: white; font-weight: 700; font-size: 13px;">{{ $user->name }}</div>
                                    <div style="font-size: 11px; color: #6b7280; margin-top: 2px; display: flex; align-items: center; gap: 6px;">
                                        <span>{{ $user->email }}</span>
                                        @if($user->phone)
                                        <span style="color: rgba(255,255,255,0.1);">&bull;</span>
                                        <span>{{ $user->phone }}</span>
                                        @endif
                                    </div>
                                    <div style="margin-top: 6px;">
                                        @if($user->role === 'officer')
                                            <span style="font-size: 8px; padding: 2px 6px; background: rgba(255,213,0,0.1); color: #FFD500; border: 1px solid rgba(255,213,0,0.2); border-radius: 4px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">👮 Officer</span>
                                        @else
                                            <span style="font-size: 8px; padding: 2px 6px; background: rgba(59,130,246,0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); border-radius: 4px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">🛠️ Field Staff</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px 24px;">
                            <div style="font-size: 12px; color: white; font-weight: 600;">{{ $user->territory }}</div>
                            <div style="font-size: 10px; color: #6b7280; margin-top: 4px;">Zone: {{ $user->zone }} &bull; Ward: {{ $user->ward }}</div>
                        </td>
                        <td style="padding: 16px 24px;">
                            <div>
                                <span style="font-size: 9px; color: #6b7280; font-weight: bold; text-transform: uppercase; display: block; letter-spacing: 0.03em;">Department</span>
                                <span style="color: #d1d5db; font-size: 11px; font-weight: 600;">{{ $user->department ?: 'N/A' }}</span>
                            </div>
                            <div style="margin-top: 4px;">
                                <span style="font-size: 9px; color: #6b7280; font-weight: bold; text-transform: uppercase; display: block; letter-spacing: 0.03em;">Employee ID</span>
                                <span style="color: #d1d5db; font-size: 11px; font-weight: 600;">{{ $user->employee_id ?: 'N/A' }}</span>
                            </div>
                        </td>
                        <td style="padding: 16px 24px;">
                            @if($user->government_id_path)
                                <a href="{{ asset('storage/' . $user->government_id_path) }}" target="_blank" style="color: #FFD500; font-weight: 700; font-size: 11px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; background: rgba(255,213,0,0.05); padding: 6px 12px; border-radius: 6px; border: 1px solid rgba(255,213,0,0.15); transition: all 0.2s;" onmouseover="this.style.background='rgba(255,213,0,0.1)'" onmouseout="this.style.background='rgba(255,213,0,0.05)'">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 12px; height: 12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    View Govt ID File
                                </a>
                            @else
                                <span style="color: #6b7280; font-size: 11px; font-style: italic;">No document uploaded</span>
                            @endif
                        </td>
                        <td style="padding: 16px 24px; text-align: right;">
                            <button onclick="openApprovalModal({{ json_encode($user) }})" style="background: linear-gradient(135deg, #FFD500, #ffae00); color: black; border: none; padding: 6px 14px; font-size: 11px; font-weight: 800; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(255, 213, 0, 0.15); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(255, 213, 0, 0.25)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(255, 213, 0, 0.15)';">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 12px; height: 12px; stroke-width: 2.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Review Request
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- SECTION 2: Processed Account Logs -->
<div class="panel" style="background: rgba(18,18,18,0.6); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; overflow: hidden; padding: 0;">
    <div style="padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.01); display: flex; justify-content: space-between; align-items: center;">
        <h4 style="font-size: 14px; font-weight: 800; color: #10b981; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
            <span>📜</span> Processed Audit Logs
        </h4>
        <span style="font-size: 11px; color: #6b7280;">Archived review history</span>
    </div>
    
    @if($processedUsers->isEmpty())
        <div style="text-align: center; color: #6b7280; padding: 48px 24px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(255,255,255,0.03); display: inline-flex; align-items: center; justify-content: center; color: #6b7280; font-size: 24px; margin-bottom: 16px;">📁</div>
            <h4 style="font-size: 15px; font-weight: 700; color: white; margin-bottom: 4px;">No Audit Logs Found</h4>
            <p style="font-size: 11px; max-width: 380px; margin: 0 auto; line-height: 1.5; color: #6b7280;">There are no previously processed (approved or rejected) accounts in the audit log history.</p>
        </div>
    @else
        <div class="table-container" style="margin: 0;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Employee Details</th>
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Geography / Region</th>
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Verification Status</th>
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Action Date</th>
                        <th style="padding: 14px 24px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: #6b7280; letter-spacing: 0.05em;">Log Notes / Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($processedUsers as $user)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.02); transition: all 0.2s ease; background: rgba(255,255,255,0.002);" onmouseover="this.style.background='rgba(255,255,255,0.015)'" onmouseout="this.style.background='rgba(255,255,255,0.002)'">
                        <td style="padding: 16px 24px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.05); color: #d1d5db; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; border: 1px solid rgba(255,255,255,0.1);">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="color: white; font-weight: 600; font-size: 13px;">{{ $user->name }}</div>
                                    <div style="font-size: 11px; color: #9ca3af; margin-top: 1px;">
                                        @if($user->role === 'officer')
                                            <span style="color: #FFD500; font-weight: bold; font-size: 9px; text-transform: uppercase;">👮 Officer</span>
                                        @else
                                            <span style="color: #3b82f6; font-weight: bold; font-size: 9px; text-transform: uppercase;">🛠️ Field Staff</span>
                                        @endif
                                        &bull; {{ $user->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px 24px;">
                            <div style="font-size: 12px; color: #e5e7eb;">{{ $user->territory }}</div>
                            <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">Zone: {{ $user->zone }}</div>
                        </td>
                        <td style="padding: 16px 24px;">
                            @if($user->status === 'approved')
                                <span style="font-size: 9px; padding: 4px 8px; background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); border-radius: 6px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:10px; height:10px; stroke-width: 3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    ACCEPTED
                                </span>
                            @else
                                <span style="font-size: 9px; padding: 4px 8px; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); border-radius: 6px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 4px;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:10px; height:10px; stroke-width: 3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    REJECTED
                                </span>
                            @endif
                        </td>
                        <td style="padding: 16px 24px; font-size: 11px; color: #9ca3af;">
                            {{ $user->updated_at->format('d M Y, H:i') }}
                        </td>
                        <td style="padding: 16px 24px;">
                            @if($user->approval_remarks)
                                <div style="color: #d1d5db; font-size: 11px; font-style: italic; max-width: 300px; line-height: 1.4; white-space: normal; word-break: break-word;">
                                    "{{ $user->approval_remarks }}"
                                </div>
                            @else
                                <span style="color: #4b5563; font-size: 12px;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
