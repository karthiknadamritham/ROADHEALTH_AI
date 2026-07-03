@php $hideFooter = true; @endphp
@extends('layouts.app')
@section('title', 'Analysis Result — ' . $analysis->scan_id)

@section('styles')
<style>
    body { background: #050505; overflow: hidden; }
    .result-layout { display: flex; height: calc(100vh - 73px); }

    /* Sidebar */
    .sidebar { width: 220px; min-width: 220px; background: #0a0a0a; border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-nav { padding: 12px 10px; flex-grow: 1; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.12); color: #FFD500; font-weight: 700; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-badge { margin-left: auto; background: #FFD500; color: black; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 10px; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 10px; }

    /* Main */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
    .topbar { height: 64px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: space-between; padding: 0 32px; background: #0a0a0a; flex-shrink: 0; }
    .topbar-left { display: flex; align-items: center; gap: 12px; }
    .topbar h2 { color: white; font-size: 18px; font-weight: 700; }
    .topbar-sub { color: #6b7280; font-size: 13px; }
    .topbar-actions { display: flex; gap: 10px; }
    .btn-new { background: #FFD500; color: black; border: none; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; }
    .btn-reports { background: transparent; color: #9ca3af; border: 1px solid rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; }
    .btn-reports:hover { color: white; border-color: rgba(255,255,255,0.2); }

    .result-content { flex-grow: 1; overflow-y: auto; padding: 32px; display: grid; grid-template-columns: 1fr 1.1fr; gap: 24px; }

    /* Cards */
    .card { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 24px; }
    .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .card-title { font-size: 15px; font-weight: 700; color: white; }
    .card-sub { font-size: 12px; color: #6b7280; }

    /* PCI Gauge */
    .pci-gauge-wrap { display: flex; flex-direction: column; align-items: center; gap: 8px; margin: 8px 0 20px; }
    .gauge-svg { position: relative; }
    .pci-number { font-size: 48px; font-weight: 900; text-align: center; }
    .pci-label { font-size: 12px; color: #6b7280; text-align: center; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }

    /* Condition chips */
    .result-chips { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
    .chip { border-radius: 8px; padding: 14px; display: flex; flex-direction: column; gap: 4px; }
    .chip-label { font-size: 10px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7280; }
    .chip-value { font-size: 18px; font-weight: 800; }

    /* Condition badges */
    .badge { padding: 5px 12px; border-radius: 5px; font-size: 12px; font-weight: 800; letter-spacing: 0.05em; display: inline-block; }
    .badge-poor      { background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
    .badge-fair      { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .badge-good      { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
    .badge-excellent { background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3); }
    .badge-invalid   { background: rgba(107,114,128,0.15); color: #9ca3af; border: 1px solid rgba(107,114,128,0.3); }

    /* Recommended action */
    .recom-box { background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; padding: 14px 16px; display: flex; gap: 10px; }
    .recom-box svg { width: 18px; height: 18px; color: #10b981; flex-shrink: 0; margin-top: 1px; }
    .recom-box p { font-size: 13px; color: #d1d5db; line-height: 1.5; }

    /* Detections list */
    .detection-list { display: flex; flex-direction: column; gap: 10px; }
    .detection-item { display: flex; align-items: center; gap: 12px; padding: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; }
    .det-icon { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .det-label { font-size: 13px; font-weight: 700; color: white; text-transform: capitalize; flex-grow: 1; }
    .det-count { font-size: 11px; color: #6b7280; margin-top: 2px; }
    .det-confidence { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .conf-bar-wrap { width: 80px; height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; overflow: hidden; }
    .conf-bar { height: 100%; border-radius: 2px; }
    .conf-pct { font-size: 11px; color: #9ca3af; font-weight: 600; }

    /* Image */
    .img-display { width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); }
    .no-img { width: 100%; height: 180px; background: rgba(255,255,255,0.02); border: 1px dashed rgba(255,255,255,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #4b5563; font-size: 13px; }

    /* Meta info */
    .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .meta-item { display: flex; flex-direction: column; gap: 3px; }
    .meta-label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; }
    .meta-value { font-size: 13px; color: white; font-weight: 600; }

    /* Success animation */
    @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .card { animation: slideIn 0.4s ease both; }
    .card:nth-child(2) { animation-delay: 0.1s; }
</style>
@endsection

@section('content')
<div class="result-layout">
    @include('partials.sidebar')

    <!-- Main -->
    <main class="main-area">
        <div class="topbar">
            <div class="topbar-left">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;color:#10b981;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h2>{{ $analysis->title ?? 'Analysis Complete' }}</h2>
                <span class="topbar-sub">— {{ $analysis->scan_id }}</span>
            </div>
            <div class="topbar-actions">
                <button class="btn-reports" onclick="window.location='/reports'">View All Reports</button>
                <button class="btn-new" onclick="window.location='/upload'">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Analysis
                </button>
            </div>
        </div>

        <div class="result-content">
            <!-- LEFT: PCI Score + Details -->
            <div style="display:flex;flex-direction:column;gap:20px;">
                @if(!$analysis->is_registered)
                <!-- Registration Form Card -->
                <div class="card" style="border-color: rgba(255,213,0,0.4); box-shadow: 0 0 15px rgba(255,213,0,0.05);">
                    <div class="card-header">
                        <div class="card-title" style="color:#FFD500; display:flex; align-items:center; gap:8px;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;color:#FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Register &amp; Report Problem
                        </div>
                    </div>
                    <form action="/reports/{{ $analysis->id }}/register" method="POST" style="display:flex; flex-direction:column; gap:14px;">
                        @csrf
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <label style="color:#9ca3af; font-size:11px; font-weight:700; text-transform:uppercase;">Problem Title *</label>
                            <input type="text" name="title" required placeholder="E.g., Severe Potholes near Sector 5 crossing" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:6px; padding:10px; color:white; font-size:13px; outline:none; font-family:inherit;">
                        </div>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <label style="color:#9ca3af; font-size:11px; font-weight:700; text-transform:uppercase;">Municipal Jurisdiction / Territory *</label>
                            <select name="territory" required style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:6px; padding:10px; color:white; font-size:13px; outline:none; font-family:inherit;">
                                @php
                                    $defaultTerritory = $analysis->territory ?: (auth()->check() ? auth()->user()->territory : '');
                                @endphp
                                <option value="" disabled {{ empty($defaultTerritory) ? 'selected' : '' }}>Select municipal agency...</option>
                                <option value="Delhi Municipal" {{ $defaultTerritory === 'Delhi Municipal' ? 'selected' : '' }}>Delhi Municipal</option>
                                <option value="Bengaluru Municipal" {{ $defaultTerritory === 'Bengaluru Municipal' ? 'selected' : '' }}>Bengaluru Municipal</option>
                                <option value="Mumbai Municipal" {{ $defaultTerritory === 'Mumbai Municipal' ? 'selected' : '' }}>Mumbai Municipal</option>
                                <option value="Chennai Municipal" {{ $defaultTerritory === 'Chennai Municipal' ? 'selected' : '' }}>Chennai Municipal</option>
                                <option value="Other Municipal" {{ $defaultTerritory === 'Other Municipal' ? 'selected' : '' }}>Other State Municipal Directorate</option>
                            </select>
                        </div>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <label style="color:#9ca3af; font-size:11px; font-weight:700; text-transform:uppercase;">Location / Address *</label>
                            <input type="text" name="location" required value="{{ $analysis->location }}" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:6px; padding:10px; color:white; font-size:13px; outline:none; font-family:inherit;">
                        </div>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <label style="color:#9ca3af; font-size:11px; font-weight:700; text-transform:uppercase;">Landmark</label>
                            <input type="text" name="landmark" placeholder="E.g., Near Metro Station Pillar 124" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:6px; padding:10px; color:white; font-size:13px; outline:none; font-family:inherit;">
                        </div>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <label style="color:#9ca3af; font-size:11px; font-weight:700; text-transform:uppercase;">Remarks / Safety Warning</label>
                            <textarea name="remarks" placeholder="Provide extra description or safety details..." style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.1); border-radius:6px; padding:10px; color:white; font-size:13px; outline:none; height:70px; resize:none; font-family:inherit;"></textarea>
                        </div>
                        <button type="submit" class="btn" style="background:#FFD500; color:black; border:none; padding:12px; font-weight:800; border-radius:6px; cursor:pointer;">
                            Register &amp; Forward to Officers
                        </button>
                    </form>
                </div>
                @else
                <!-- Registration & Reporter Info Card -->
                <div class="card">
                    <div class="card-header" style="margin-bottom:16px;">
                        <div class="card-title">Registration &amp; Reporter Details</div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        <div>
                            <span style="font-size:10px; color:#6b7280; font-weight:700; text-transform:uppercase; display:block;">Problem Title</span>
                            <div style="font-size:13px; color:white; font-weight:700; margin-top:2px;">{{ $analysis->title }}</div>
                        </div>
                        @if($analysis->landmark)
                        <div>
                            <span style="font-size:10px; color:#6b7280; font-weight:700; text-transform:uppercase; display:block;">Landmark</span>
                            <div style="font-size:13px; color:white; font-weight:600; margin-top:2px;">{{ $analysis->landmark }}</div>
                        </div>
                        @endif
                        @if($analysis->remarks)
                        <div>
                            <span style="font-size:10px; color:#6b7280; font-weight:700; text-transform:uppercase; display:block;">Reporter Remarks</span>
                            <div style="font-size:13px; color:#9ca3af; line-height:1.4; font-style:italic; margin-top:2px;">"{{ $analysis->remarks }}"</div>
                        </div>
                        @endif
                        <div style="border-top:1px solid rgba(255,255,255,0.05); padding-top:10px; margin-top:4px;">
                            <span style="font-size:10px; color:#6b7280; font-weight:700; text-transform:uppercase; display:block; margin-bottom:4px;">Reporter Information</span>
                            @if($analysis->user)
                                <div style="font-size:13px; color:white; font-weight:600;">{{ $analysis->user->name }}</div>
                                <div style="font-size:11px; color:#9ca3af; margin-top:2px;">Phone: {{ $analysis->user->phone ?? 'N/A' }}</div>
                                <div style="font-size:11px; color:#9ca3af;">Email: {{ $analysis->user->email }}</div>
                            @else
                                <div style="font-size:13px; color:white; font-weight:600;">Guest Reporter (Public Submission)</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- PCI Score Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Pavement Condition Index</div>
                        <span class="badge badge-{{ strtolower($analysis->condition) }}">{{ strtoupper($analysis->condition) }}</span>
                    </div>

                    <!-- SVG Gauge -->
                    <div class="pci-gauge-wrap">
                        <svg width="180" height="100" viewBox="0 0 180 100">
                            <path d="M 20 90 A 70 70 0 0 1 160 90" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="12" stroke-linecap="round"/>
                            @php
                                $pci    = $analysis->pci_score;
                                $color  = $analysis->pci_color;
                                $angle  = ($pci / 100) * 180;
                                $rad    = deg2rad($angle - 180);
                                $x2     = 90 + 70 * cos($rad);
                                $y2     = 90 + 70 * sin($rad);
                                $large  = $angle > 180 ? 1 : 0;
                            @endphp
                            <path d="M 20 90 A 70 70 0 {{ $large }} 1 {{ round($x2,2) }} {{ round($y2,2) }}"
                                  fill="none" stroke="{{ $color }}" stroke-width="12" stroke-linecap="round"/>
                            <text x="90" y="82" text-anchor="middle" fill="{{ $color }}" font-size="34" font-weight="900" font-family="Inter, sans-serif">{{ strtolower($analysis->condition) === 'invalid' ? 'N/A' : $pci }}</text>
                            <text x="90" y="98" text-anchor="middle" fill="#6b7280" font-size="11" font-family="Inter, sans-serif">{{ strtolower($analysis->condition) === 'invalid' ? 'NOT A ROAD' : '/100' }}</text>
                        </svg>
                    </div>

                    <!-- Chips -->
                    <div class="result-chips">
                        <div class="chip" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                            <span class="chip-label">Severity</span>
                            <span class="chip-value" style="color:{{ $analysis->pci_score < 55 ? '#ef4444' : ($analysis->pci_score < 75 ? '#f59e0b' : '#10b981') }};">
                                {{ $analysis->severity }}
                            </span>
                        </div>
                        <div class="chip" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);">
                            <span class="chip-label">Defects Found</span>
                            <span class="chip-value" style="color:white;">{{ $analysis->total_defects }}</span>
                        </div>
                    </div>

                    <!-- Recommended Action -->
                    <div class="recom-box">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p>{{ $analysis->recommended_action }}</p>
                    </div>
                </div>

                <!-- Meta Info -->
                <div class="card">
                    <div class="card-title" style="margin-bottom:16px;">Scan Details</div>
                    <div class="meta-grid">
                        <div class="meta-item">
                            <span class="meta-label">Scan ID</span>
                            <span class="meta-value">{{ $analysis->scan_id }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Date & Time</span>
                            <span class="meta-value">{{ $analysis->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Location</span>
                            <span class="meta-value">{{ $analysis->location }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Mode</span>
                            <span class="meta-value" style="color:#FFD500;text-transform:uppercase;">{{ $analysis->api_mode }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">File</span>
                            <span class="meta-value" style="font-size:11px;color:#9ca3af;">{{ $analysis->original_filename }}</span>
                        </div>
                    </div>
                </div>

                @if($analysis->maintenanceTask)
                <!-- Maintenance Progress Tracker -->
                <div class="card" style="margin-top: 20px;">
                    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                        <div class="card-title" style="color: #3b82f6;">Maintenance Progress Tracker</div>
                        @php
                            $statusStyle = 'background: rgba(255,213,0,0.15); color: #FFD500; border: 1px solid rgba(255,213,0,0.3);';
                            if($analysis->maintenanceTask->status === 'started') $statusStyle = 'background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3);';
                            elseif($analysis->maintenanceTask->status === 'paused') $statusStyle = 'background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3);';
                            elseif($analysis->maintenanceTask->status === 'completed') $statusStyle = 'background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.3);';
                            elseif($analysis->maintenanceTask->status === 'approved') $statusStyle = 'background: rgba(16,185,129,0.25); color: #10b981; border: 1px solid #10b981;';
                            elseif($analysis->maintenanceTask->status === 'correction') $statusStyle = 'background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid #ef4444;';
                        @endphp
                        <span class="badge" style="text-transform:uppercase; font-size:10px; font-weight:800; padding:4px 8px; border-radius:4px; {{ $statusStyle }}">
                            {{ $analysis->maintenanceTask->status }}
                        </span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px; text-align: left;">
                        <div style="font-size: 13px; color: #9ca3af;">
                            <strong>Assigned Crew:</strong> <span style="color: white; font-weight: 600;">{{ $analysis->maintenanceTask->assignedStaff->name ?? 'Awaiting Assignment' }}</span>
                        </div>
                        <div style="font-size: 13px; color: #9ca3af; margin-top: 4px;">
                            <strong>Priority Level:</strong> <span style="color: white; font-weight: 600;">{{ ucfirst($analysis->maintenanceTask->priority) }}</span>
                        </div>
                        @if($analysis->maintenanceTask->deadline)
                        <div style="font-size: 13px; color: #9ca3af; margin-top: 4px;">
                            <strong>Target Deadline:</strong> <span style="color: white; font-weight: 600;">{{ \Carbon\Carbon::parse($analysis->maintenanceTask->deadline)->format('d M Y') }} ({{ \Carbon\Carbon::parse($analysis->maintenanceTask->deadline)->diffForHumans() }})</span>
                        </div>
                        @endif
                    </div>

                    <!-- Timeline -->
                    <div style="display: flex; flex-direction: column; gap: 18px; text-align: left;">
                        @forelse($analysis->maintenanceTask->taskActivities as $act)
                            @php
                                $activityDotColor = '#FFD500';
                                $activityTitle = '';
                                $progressImageHtml = '';
                                
                                if($act->action === 'started' || $act->action === 'approved') $activityDotColor = '#10b981';
                                elseif($act->action === 'paused') $activityDotColor = '#f59e0b';
                                elseif($act->action === 'correction') $activityDotColor = '#ef4444';
                                elseif($act->action === 'progress_update') {
                                    $activityDotColor = '#3b82f6';
                                    $activityTitle = 'Work Progress Update';
                                    if ($act->image_path) {
                                        $progressImageHtml = '<img src="/storage/' . $act->image_path . '" style="max-width: 100%; height: auto; max-height: 120px; object-fit: cover; border-radius: 6px; margin-top: 6px; border: 1px solid rgba(255,255,255,0.1);">';
                                    }
                                }
                            @endphp
                            <div style="display: flex; gap: 12px; border-left: 2px solid rgba(255,255,255,0.05); padding-left: 14px; position: relative;">
                                <div style="position: absolute; left: -5px; top: 4px; width: 8px; height: 8px; border-radius: 50%; background: {{ $activityDotColor }}; box-shadow: 0 0 6px {{ $activityDotColor }};"></div>
                                <div style="display: flex; flex-direction: column; gap: 2px; align-items: flex-start; text-align: left;">
                                    @if($activityTitle)
                                        <span style="color: #3b82f6; font-size: 10px; font-weight: 800; text-transform: uppercase; display: block; margin-bottom: 2px;">{{ $activityTitle }}</span>
                                    @endif
                                    <span style="color: white; font-size: 13px; font-weight: 600;">{{ $act->description }}</span>
                                    {!! $progressImageHtml !!}
                                    <span style="color: #6b7280; font-size: 10.5px; margin-top: 2px;">
                                        By: {{ $act->user->name ?? 'System' }} &bull; {{ $act->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div style="color: #6b7280; font-size: 12px; text-align: center; padding: 12px;">No work activities recorded yet.</div>
                        @endforelse
                    </div>
                </div>
                @endif
            </div>

            <!-- RIGHT: Image + Detections -->
            <div style="display:flex;flex-direction:column;gap:20px;">
                <!-- Road Image -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Uploaded Road Image</div>
                        <span class="card-sub">AI annotated scan</span>
                    </div>
                    @if($analysis->image_path)
                        <img src="{{ asset('storage/' . $analysis->image_path) }}" alt="Road scan" class="img-display">
                    @else
                        <div class="no-img">No image stored</div>
                    @endif
                </div>

                <!-- Detections -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">AI Detection Breakdown</div>
                        <span class="card-sub">{{ $analysis->total_defects }} defects identified</span>
                    </div>

                    @php
                        $detectionsList = is_string($analysis->detections) ? json_decode($analysis->detections, true) : $analysis->detections;
                        // Handle double encoding fallback
                        if (is_string($detectionsList)) {
                            $detectionsList = json_decode($detectionsList, true);
                        }
                    @endphp
                    @if(!empty($detectionsList) && is_array($detectionsList))
                        <div class="detection-list">
                            @foreach($detectionsList as $det)
                            @php
                                $colors = [
                                    'pothole'            => ['bg' => 'rgba(239,68,68,0.15)',  'dot' => '#ef4444'],
                                    'alligator_crack'    => ['bg' => 'rgba(245,158,11,0.15)', 'dot' => '#f59e0b'],
                                    'longitudinal_crack' => ['bg' => 'rgba(245,158,11,0.15)', 'dot' => '#f59e0b'],
                                    'transverse_crack'   => ['bg' => 'rgba(156,163,175,0.15)','dot' => '#9ca3af'],
                                    'minor_crack'        => ['bg' => 'rgba(156,163,175,0.1)', 'dot' => '#6b7280'],
                                    'surface_wear'       => ['bg' => 'rgba(59,130,246,0.15)', 'dot' => '#3b82f6'],
                                    'patch'              => ['bg' => 'rgba(139,92,246,0.15)', 'dot' => '#8b5cf6'],
                                ];
                                $c = $colors[$det['label']] ?? ['bg' => 'rgba(255,255,255,0.05)', 'dot' => '#6b7280'];
                                $confPct = (int)($det['confidence'] * 100);
                            @endphp
                            <div class="detection-item">
                                <div class="det-icon" style="background:{{ $c['bg'] }};">
                                    <div style="width:10px;height:10px;border-radius:50%;background:{{ $c['dot'] }};"></div>
                                </div>
                                <div style="flex-grow:1;">
                                    <div class="det-label">{{ str_replace('_', ' ', $det['label']) }}</div>
                                    <div class="det-count">Count: {{ $det['count'] }}</div>
                                </div>
                                <div class="det-confidence">
                                    <div class="conf-bar-wrap">
                                        <div class="conf-bar" style="width:{{ $confPct }}%;background:{{ $c['dot'] }};"></div>
                                    </div>
                                    <span class="conf-pct">{{ $confPct }}%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align:center;padding:32px;color:#6b7280;font-size:14px;">
                            ✅ No significant defects detected
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
