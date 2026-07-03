@php
    $groupedOfficers = $allOfficers->groupBy('territory');
    $groupedStaff = $allStaff->groupBy('territory');
    
    // Get all unique territories from both officers and staff, sorted alphabetically
    $allTerritories = $allOfficers->pluck('territory')
        ->merge($allStaff->pluck('territory'))
        ->unique()
        ->filter()
        ->sort();
@endphp

<!-- Header and Directory Info -->
<div class="panel" style="margin-bottom: 24px;">
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 16px;">
        <div>
            <h3 class="panel-title" style="font-size: 18px; color: white;">Municipal Directory &amp; Hierarchy</h3>
            <p style="font-size: 11px; color: #6b7280; margin-top: 4px;">Structural listing of all registered officers and the field staff operating under their respective territories.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <span style="background: rgba(255,213,0,0.05); color: #FFD500; border: 1px solid rgba(255,213,0,0.2); padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold;">
                👮 {{ $allOfficers->count() }} Officers
            </span>
            <span style="background: rgba(59,130,246,0.05); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: bold;">
                🛠️ {{ $allStaff->count() }} Deployed Staff
            </span>
        </div>
    </div>
</div>

<!-- Territories Directory Grid -->
<div style="display: flex; flex-direction: column; gap: 24px;">
    @forelse($allTerritories as $territory)
        @php
            $officers = $groupedOfficers->get($territory, collect());
            $staffMembers = $groupedStaff->get($territory, collect());
        @endphp

        <div class="panel" style="border: 1px solid rgba(255,255,255,0.05); overflow: hidden; background: rgba(18,18,18,0.6); backdrop-filter: blur(10px); transition: all 0.3s ease;">
            <!-- Territory Header Badge -->
            <div style="background: rgba(255,255,255,0.02); border-bottom: 1px solid rgba(255,255,255,0.05); padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #FFD500; box-shadow: 0 0 10px rgba(255,213,0,0.5);"></div>
                    <span style="font-size: 14px; font-weight: 800; color: white; text-transform: uppercase; letter-spacing: 0.05em;">
                        {{ $territory }} Municipal Jurisdiction
                    </span>
                </div>
                <div style="font-size: 11px; color: #6b7280;">
                    <span style="color: #d1d5db; font-weight: bold;">{{ $officers->count() }}</span> Officer(s) &bull; 
                    <span style="color: #d1d5db; font-weight: bold;">{{ $staffMembers->count() }}</span> Staff member(s)
                </div>
            </div>

            <div style="padding: 20px; display: flex; flex-direction: column; gap: 20px;">
                <!-- 1. Officers Subsection -->
                <div>
                    <h4 style="font-size: 11px; font-weight: 800; color: #FFD500; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                        <span>👮</span> Designated Municipal Officers
                    </h4>
                    
                    @if($officers->isEmpty())
                        <div style="background: rgba(239,68,68,0.02); border: 1px dashed rgba(239,68,68,0.15); border-radius: 8px; padding: 16px; text-align: center; color: #ef4444; font-size: 12px;">
                            ⚠ No officers registered or assigned to this territory.
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
                            @foreach($officers as $officer)
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; padding: 16px; position: relative;">
                                    <div style="display: flex; gap: 12px; align-items: center;">
                                        @if($officer->profile_photo_path)
                                            <img src="{{ asset('storage/' . $officer->profile_photo_path) }}" alt="Profile" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #FFD500;">
                                        @else
                                            <div style="width: 48px; height: 48px; background: rgba(255,213,0,0.1); color: #FFD500; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; border: 1px solid rgba(255,213,0,0.3);">
                                                {{ substr($officer->name, 0, 1) }}
                                            </div>
                                        @endif
                                        
                                        <div style="flex-grow: 1;">
                                            <div style="color: white; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                                                {{ $officer->name }}
                                                @if($officer->status === 'approved')
                                                    <span style="font-size: 9px; padding: 2px 6px; background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); border-radius: 4px; font-weight: 800; text-transform: uppercase;">Approved</span>
                                                @elseif($officer->status === 'pending')
                                                    <span style="font-size: 9px; padding: 2px 6px; background: rgba(255,213,0,0.1); color: #FFD500; border: 1px solid rgba(255,213,0,0.2); border-radius: 4px; font-weight: 800; text-transform: uppercase; cursor: pointer;" onclick="openApprovalModal({{ json_encode($officer) }})">Review Pending</span>
                                                @else
                                                    <span style="font-size: 9px; padding: 2px 6px; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); border-radius: 4px; font-weight: 800; text-transform: uppercase;">Rejected</span>
                                                @endif
                                            </div>
                                            <div style="font-size: 12px; color: #9ca3af; margin-top: 2px;">{{ $officer->email }}</div>
                                        </div>
                                    </div>
                                    
                                    <div style="border-top: 1px solid rgba(255,255,255,0.03); margin-top: 12px; padding-top: 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 11px;">
                                        <div>
                                            <span style="color: #6b7280; display: block; text-transform: uppercase; font-size: 9px; font-weight: bold;">Department</span>
                                            <span style="color: #e5e7eb; font-weight: 600;">{{ $officer->department ?: 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span style="color: #6b7280; display: block; text-transform: uppercase; font-size: 9px; font-weight: bold;">Employee ID</span>
                                            <span style="color: #e5e7eb; font-weight: 600;">{{ $officer->employee_id ?: 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- 2. Staff Subsection -->
                <div style="border-top: 1px solid rgba(255,255,255,0.03); padding-top: 16px;">
                    <h4 style="font-size: 11px; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                        <span>🛠️</span> Deployed Field Staff (Under Officers)
                    </h4>
                    
                    @if($staffMembers->isEmpty())
                        <div style="background: rgba(255,255,255,0.01); border: 1px dashed rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; text-align: center; color: #6b7280; font-size: 12px;">
                            No deployed field staff operating in this territory.
                        </div>
                    @else
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 16px;">
                            @foreach($staffMembers as $staff)
                                <div style="background: rgba(59,130,246,0.01); border: 1px solid rgba(59,130,246,0.05); border-radius: 10px; padding: 16px; position: relative;">
                                    <div style="display: flex; gap: 12px; align-items: center;">
                                        @if($staff->profile_photo_path)
                                            <img src="{{ asset('storage/' . $staff->profile_photo_path) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #3b82f6;">
                                        @else
                                            <div style="width: 40px; height: 40px; background: rgba(59,130,246,0.1); color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; border: 1px solid rgba(59,130,246,0.3);">
                                                {{ substr($staff->name, 0, 1) }}
                                            </div>
                                        @endif
                                        
                                        <div style="flex-grow: 1;">
                                            <div style="color: white; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                                                {{ $staff->name }}
                                                @if($staff->status === 'approved')
                                                    <span style="font-size: 8px; padding: 1px 4px; background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); border-radius: 3px; font-weight: 800; text-transform: uppercase;">Active</span>
                                                @elseif($staff->status === 'pending')
                                                    <span style="font-size: 8px; padding: 1px 4px; background: rgba(255,213,0,0.1); color: #FFD500; border: 1px solid rgba(255,213,0,0.2); border-radius: 3px; font-weight: 800; text-transform: uppercase;">Pending</span>
                                                @else
                                                    <span style="font-size: 8px; padding: 1px 4px; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); border-radius: 3px; font-weight: 800; text-transform: uppercase;">Inactive</span>
                                                @endif
                                            </div>
                                            <div style="font-size: 11px; color: #9ca3af; margin-top: 1px;">{{ $staff->email }}</div>
                                        </div>
                                    </div>
                                    
                                    <div style="border-top: 1px solid rgba(255,255,255,0.03); margin-top: 10px; padding-top: 10px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-size: 10px;">
                                        <div>
                                            <span style="color: #6b7280; display: block; text-transform: uppercase; font-size: 8px; font-weight: bold;">Department</span>
                                            <span style="color: #d1d5db; font-weight: 600;">{{ $staff->department ?: 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span style="color: #6b7280; display: block; text-transform: uppercase; font-size: 8px; font-weight: bold;">Employee ID</span>
                                            <span style="color: #d1d5db; font-weight: 600;">{{ $staff->employee_id ?: 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="panel" style="text-align: center; color: #6b7280; padding: 48px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; margin-bottom: 16px; color: #4b5563;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <div style="font-size: 16px; font-weight: bold; color: white;">No Municipal Accounts Found</div>
            <p style="font-size: 12px; margin-top: 8px; max-width: 320px; margin-left: auto; margin-right: auto;">There are no registered officers or staff members under any territories in the system directory.</p>
        </div>
    @endforelse
</div>
