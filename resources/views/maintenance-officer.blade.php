@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'Maintenance Control Center')

@section('styles')
<style>
    body { background: #050505; overflow: hidden; font-family: 'Inter', sans-serif; }
    .maint-layout { display: flex; height: calc(100vh - 73px); }

    /* Sidebar (Reusing dashboard style for consistency) */
    .sidebar { width: 220px; min-width: 220px; background: #0a0a0a; border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-nav { padding: 12px 10px; flex-grow: 1; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; text-decoration: none; }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.12); color: #FFD500; font-weight: 700; border-left: 3px solid #FFD500; border-radius: 4px 8px 8px 4px; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 10px; }

    /* Main Content */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; background: #050505; }
    .topbar { height: 64px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; padding: 0 32px; background: #0a0a0a; flex-shrink: 0; }
    .topbar-title { font-size: 18px; font-weight: 800; color: white; display: flex; align-items: center; gap: 12px; }
    .topbar-title svg { color: #FFD500; width: 20px; height: 20px; }
    .topbar-badge { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 12px; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 6px; }
    .topbar-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 8px #ef4444; animation: pulseRed 2s infinite; }
    
    @keyframes pulseRed { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

    .content-scrollable { flex-grow: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 24px; }

    /* Session Alert */
    .alert-success { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.4); color: #10b981; padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; }

    /* Widgets */
    .kpi-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .kpi-card { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; }
    .kpi-card.critical { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.02); }
    .kpi-info { display: flex; flex-direction: column; }
    .kpi-lbl { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .kpi-val { font-size: 24px; font-weight: 800; color: white; line-height: 1.1; }
    .kpi-val.red { color: #ef4444; }
    .kpi-icon { width: 36px; height: 36px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; color: #9ca3af; }
    .kpi-icon.red { background: rgba(239,68,68,0.1); color: #ef4444; }

    /* Layout Grid */
    .cc-grid { display: grid; grid-template-columns: 320px 1.5fr 360px; gap: 20px; flex-grow: 1; }
    .cc-panel { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; display: flex; flex-direction: column; overflow: hidden; }
    .cc-header { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.01); }
    .cc-title { font-size: 13px; font-weight: 800; color: white; display: flex; align-items: center; gap: 8px; }
    
    /* Request Queue */
    .req-list { display: flex; flex-direction: column; overflow-y: auto; flex-grow: 1; height: 500px; }
    .req-item { padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.03); cursor: pointer; transition: background 0.2s; display: flex; flex-direction: column; gap: 8px; border-left: 3px solid transparent; }
    .req-item:hover { background: rgba(255,255,255,0.02); }
    .req-item.active { background: rgba(255,213,0,0.03); border-left-color: #FFD500; }
    .req-item.critical { border-left-color: #ef4444; }
    .req-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .req-id { font-size: 12px; font-weight: 700; color: white; }
    .req-badge { font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; }
    .badge-critical { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
    .badge-high { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .badge-medium { background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3); }
    
    .req-loc { font-size: 11px; color: #9ca3af; display: flex; align-items: center; gap: 4px; }
    .req-loc svg { width: 12px; height: 12px; }
    .req-source { font-size: 10px; color: #6b7280; font-weight: 600; display: flex; align-items: center; gap: 4px; margin-top: 4px; }
    .req-source.ai { color: #FFD500; }

    /* Middle: Action Center */
    .action-center { display: flex; flex-direction: column; flex-grow: 1; padding: 24px; gap: 24px; overflow-y: auto; }
    
    .ac-header { display: flex; justify-content: space-between; align-items: flex-start; }
    .ac-title { font-size: 20px; font-weight: 800; color: white; margin-bottom: 4px; }
    .ac-loc { font-size: 13px; color: #9ca3af; display: flex; align-items: center; gap: 6px; }
    
    .insight-grid { display: grid; grid-template-columns: 1fr 1fr 1.5fr; gap: 16px; }
    .insight-box { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 16px; display: flex; flex-direction: column; align-items: center; text-align: center; justify-content: center; }
    .insight-box.warning { border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.02); }
    .in-lbl { font-size: 9px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 6px; }
    .in-val { font-size: 22px; font-weight: 900; color: white; }
    .in-val.red { color: #ef4444; }
    
    .defect-tags { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-top: 8px; }
    .defect-tag { font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 4px; background: rgba(255,255,255,0.05); color: #d1d5db; border: 1px solid rgba(255,255,255,0.1); }
    .defect-tag.severe { background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.3); }

    .control-group { display: flex; flex-direction: column; gap: 8px; }
    .control-lbl { font-size: 11px; font-weight: 700; color: white; }
    .select-staff { background: #050505; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 16px; color: white; font-size: 13px; outline: none; width: 100%; cursor: pointer; }
    .select-staff:focus { border-color: #FFD500; }
    
    .btn-action-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 8px; }
    .btn-assign { background: #FFD500; color: black; border: none; border-radius: 8px; padding: 14px 0; font-size: 13px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; }
    .btn-assign:hover { background: #facc15; }
    .btn-dispatch { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.4); color: #ef4444; border-radius: 8px; padding: 14px 0; font-size: 13px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; }
    .btn-dispatch:hover { background: rgba(239,68,68,0.2); }

    /* Right: Map & Tracking */
    .map-container { height: 260px; background: #050505; position: relative; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .map-img { width: 100%; height: 100%; object-fit: cover; opacity: 0.6; }
    .map-overlay { position: absolute; inset: 0; background: linear-gradient(0deg, #0a0a0a 0%, transparent 40%); }
    .map-pin { position: absolute; left: 50%; top: 40%; width: 24px; height: 24px; transform: translate(-50%, -50%); color: #ef4444; filter: drop-shadow(0 0 10px rgba(239,68,68,0.8)); }
    .staff-pin { position: absolute; left: 60%; top: 60%; width: 28px; height: 28px; background: #3b82f6; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800; color: white; box-shadow: 0 0 10px rgba(59,130,246,0.6); }

    .staff-list { display: flex; flex-direction: column; padding: 16px; gap: 12px; overflow-y: auto; flex-grow: 1; }
    .staff-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; }
    .staff-info { display: flex; align-items: center; gap: 12px; }
    .staff-avatar { width: 32px; height: 32px; border-radius: 50%; background: rgba(59,130,246,0.2); border: 1px solid rgba(59,130,246,0.5); display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 12px; font-weight: 800; }
    .staff-avatar.active { border-color: #10b981; color: #10b981; background: rgba(16,185,129,0.1); }
    .staff-name { font-size: 12px; font-weight: 700; color: white; }
    .staff-task { font-size: 10px; color: #9ca3af; margin-top: 2px; }
    .staff-dist { font-size: 11px; font-weight: 600; color: #FFD500; }

</style>
@endsection

@section('content')
<div class="maint-layout">
    @include('partials.sidebar')

    <!-- Main Content Area -->
    <main class="main-area">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                Maintenance Control Center
            </div>
            <div class="topbar-badge">
                Live Emergency System Active
            </div>
        </header>

        <div class="content-scrollable">
            @if(session('success'))
            <div class="alert-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
            @endif

            <!-- Top KPI Widgets -->
            <div class="kpi-row">
                <div class="kpi-card critical">
                    <div class="kpi-info">
                        <span class="kpi-lbl">Critical Requests</span>
                        <span class="kpi-val red">12</span>
                    </div>
                    <div class="kpi-icon red">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-info">
                        <span class="kpi-lbl">Pending Requests</span>
                        <span class="kpi-val">45</span>
                    </div>
                    <div class="kpi-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-info">
                        <span class="kpi-lbl">Active Staff</span>
                        <span class="kpi-val">18</span>
                    </div>
                    <div class="kpi-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <div class="kpi-card">
                    <div class="kpi-info">
                        <span class="kpi-lbl">Roads Repaired Today</span>
                        <span class="kpi-val" style="color:#10b981;">9</span>
                    </div>
                    <div class="kpi-icon" style="background:rgba(16,185,129,0.1); color:#10b981;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Command Center Grid -->
            <div class="cc-grid">
                
                <!-- Left: Request Queue -->
                <div class="cc-panel">
                    <div class="cc-header">
                        <div class="cc-title">Incoming Requests</div>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;color:#6b7280;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    <div class="req-list">
                        @forelse($unassignedReports as $r)
                        @php
                            $badgeClass = 'badge-medium';
                            $priorityText = 'Medium';
                            if ($r->pci_score < 35) { $badgeClass = 'badge-critical'; $priorityText = 'Critical'; }
                            elseif ($r->pci_score < 55) { $badgeClass = 'badge-high'; $priorityText = 'High'; }
                        @endphp
                        <div class="req-item {{ (isset($selectedReport) && $selectedReport->id === $r->id) ? 'active' : '' }} {{ $priorityText === 'Critical' ? 'critical' : '' }}" onclick="window.location.href='?id={{ $r->id }}'" style="cursor: pointer;">
                            <div class="req-top">
                                <span class="req-id">{{ $r->scan_id }}</span>
                                <span class="req-badge {{ $badgeClass }}">{{ $priorityText }}</span>
                            </div>
                            <div class="req-loc">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                {{ $r->location ?? 'Unknown Location' }}
                            </div>
                            <div class="req-source ai">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:10px;height:10px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                AI Survey Scan
                            </div>
                        </div>
                        @empty
                        <div style="padding: 20px; text-align: center; color: #6b7280; font-size: 13px;">
                            No unassigned reports found.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Center: Action & Insights -->
                <div class="cc-panel">
                    <div class="action-center">
                        @if($selectedReport)
                        @php
                            $badgeClass = 'badge-medium';
                            $priorityText = 'Medium Priority';
                            if ($selectedReport->pci_score < 35) { $badgeClass = 'badge-critical'; $priorityText = 'CRITICAL EMERGENCY'; }
                            elseif ($selectedReport->pci_score < 55) { $badgeClass = 'badge-high'; $priorityText = 'HIGH PRIORITY'; }
                        @endphp
                        <div class="ac-header">
                            <div>
                                <div class="ac-title">Request {{ $selectedReport->scan_id }}</div>
                                <div class="ac-loc">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                    {{ $selectedReport->location ?? 'Unknown' }} (Lat: {{ $selectedReport->latitude }}, Lng: {{ $selectedReport->longitude }})
                                </div>
                            </div>
                            <span class="req-badge {{ $badgeClass }}" style="font-size: 11px; padding: 6px 12px;">{{ strtoupper($priorityText) }}</span>
                        </div>
                        
                        <!-- AI Insights -->
                        <div>
                            <div style="font-size: 11px; font-weight: 700; color: white; margin-bottom: 12px;">AI Severity Insights</div>
                            <div class="insight-grid">
                                <div class="insight-box {{ strtolower($selectedReport->condition) === 'poor' ? 'warning' : '' }}">
                                    <span class="in-lbl">Condition</span>
                                    <span class="in-val {{ strtolower($selectedReport->condition) === 'poor' ? 'red' : '' }}">{{ ucfirst($selectedReport->condition) }}</span>
                                </div>
                                <div class="insight-box">
                                    <span class="in-lbl">PCI Score</span>
                                    <span class="in-val">{{ $selectedReport->pci_score }}<span style="font-size:12px;color:#6b7280;">/100</span></span>
                                </div>
                                <div class="insight-box">
                                    <span class="in-lbl">Total Defects</span>
                                    <span class="in-val" style="color:#10b981;">{{ $selectedReport->total_defects }}</span>
                                </div>
                            </div>
                            <div class="defect-tags">
                                @if(is_array($selectedReport->detections_decoded))
                                    @foreach(array_slice($selectedReport->detections_decoded, 0, 3) as $det)
                                    <span class="defect-tag {{ in_array($det['label'], ['pothole', 'alligator_crack']) ? 'severe' : '' }}">
                                        {{ str_replace('_', ' ', ucwords($det['label'])) }}
                                    </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        
                        <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.05); margin: 0;">

                        <!-- Controls -->
                        <form action="{{ route('maintenance.assign') }}" method="POST">
                            @csrf
                            <input type="hidden" name="road_analysis_id" value="{{ $selectedReport->id }}">
                            
                            <div class="control-group">
                                <span class="control-lbl">Assign to Field Staff / Team</span>
                                <select name="assigned_to" class="select-staff" required>
                                    <option value="">Select Staff Member...</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="control-group">
                                <span class="control-lbl">Modify Priority</span>
                                <select name="priority" class="select-staff">
                                    <option value="emergency" {{ $selectedReport->pci_score < 35 ? 'selected' : '' }}>Emergency Response</option>
                                    <option value="high" {{ ($selectedReport->pci_score >= 35 && $selectedReport->pci_score < 55) ? 'selected' : '' }}>High Priority</option>
                                    <option value="medium" {{ ($selectedReport->pci_score >= 55 && $selectedReport->pci_score < 75) ? 'selected' : '' }}>Medium Priority</option>
                                    <option value="low" {{ $selectedReport->pci_score >= 75 ? 'selected' : '' }}>Low Priority</option>
                                </select>
                            </div>

                            <div class="btn-action-row">
                                <button type="submit" class="btn-assign">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Assign Staff
                                </button>
                            </div>
                        </form>
                        @else
                        <div style="padding: 40px; text-align: center; color: #9ca3af;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;margin:0 auto 16px;opacity:0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3>All Caught Up!</h3>
                            <p>There are no unassigned reports requiring maintenance at this time.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Right: Spatial Tracking -->
                <div class="cc-panel">
                    <div class="cc-header">
                        <div class="cc-title">Live Staff Tracking</div>
                        <span style="font-size: 10px; color: #10b981; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                            <span style="width:6px;height:6px;border-radius:50%;background:#10b981;"></span> Online
                        </span>
                    </div>
                    
                    <div class="map-container">
                        <!-- Futuristic dark map visual representation -->
                        <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Map" class="map-img" style="filter: grayscale(100%) invert(100%) contrast(120%); mix-blend-mode: screen;">
                        <div class="map-overlay"></div>
                        
                        <!-- Damage Pin -->
                        <svg class="map-pin" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        
                        <!-- Staff Pin -->
                        <div class="staff-pin">A</div>
                    </div>

                    <div class="staff-list">
                        <div class="staff-item">
                            <div class="staff-info">
                                <div class="staff-avatar active">A</div>
                                <div>
                                    <div class="staff-name">Team Alpha</div>
                                    <div class="staff-task">Waiting for assignment</div>
                                </div>
                            </div>
                            <div class="staff-dist">2.1 km</div>
                        </div>
                        <div class="staff-item">
                            <div class="staff-info">
                                <div class="staff-avatar active">B</div>
                                <div>
                                    <div class="staff-name">Team Beta</div>
                                    <div class="staff-task">Fixing #RH-2041</div>
                                </div>
                            </div>
                            <div class="staff-dist">5.5 km</div>
                        </div>
                        <div class="staff-item">
                            <div class="staff-info">
                                <div class="staff-avatar">G</div>
                                <div>
                                    <div class="staff-name">Team Gamma</div>
                                    <div class="staff-task">Offline / Break</div>
                                </div>
                            </div>
                            <div class="staff-dist" style="color:#6b7280;">--</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection
