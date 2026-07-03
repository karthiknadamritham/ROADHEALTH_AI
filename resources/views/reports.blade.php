@php $hideFooter = true; @endphp
@extends('layouts.app')
@section('title', 'Analysis History')

@section('styles')
<style>
    /* Global Layout & Main Scroll Reset */
    body { background: #050505; overflow: hidden; font-family: 'Inter', sans-serif; }
    .reports-layout { display: flex; height: calc(100vh - 73px); }

    /* Sidebar Navigation Styles */
    .sidebar { width: 240px; min-width: 240px; background: #080808; border-right: 1px solid rgba(255,255,255,0.05); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-nav { padding: 16px 12px; flex-grow: 1; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; text-decoration: none; }
    .nav-item:hover { background: rgba(255,255,255,0.04); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.1); color: #FFD500; font-weight: 700; border-left: 3px solid #FFD500; border-radius: 4px 8px 8px 4px; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-badge-ai { margin-left: auto; background: #FFD500; color: black; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 10px; text-transform: uppercase; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 8px 12px; }

    /* Go Premium & Language */
    .sidebar-premium { margin: 12px; background: linear-gradient(135deg, rgba(255,213,0,0.12) 0%, rgba(255,213,0,0.02) 100%); border: 1px solid rgba(255,213,0,0.15); border-radius: 12px; padding: 16px; position: relative; }
    .sidebar-premium .crown { font-size: 18px; margin-bottom: 8px; display: block; }
    .sidebar-premium h4 { color: white; font-size: 13px; font-weight: 700; margin-bottom: 4px; }
    .sidebar-premium p { color: #9ca3af; font-size: 10.5px; line-height: 1.4; margin-bottom: 12px; }
    .sidebar-premium .btn-upgrade { width: 100%; background: #FFD500; color: black; border: none; border-radius: 6px; padding: 8px 0; font-size: 12px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.2s; }
    .sidebar-premium .btn-upgrade:hover { background: #facc15; }
    .sidebar-lang { padding: 12px 16px 16px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 8px; color: #9ca3af; font-size: 13px; cursor: pointer; transition: color 0.2s; }
    .sidebar-lang:hover { color: white; }
    .sidebar-lang svg { width: 16px; height: 16px; }

    /* Main Area Framework */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; background: #050505; }
    .main-scrollable { flex-grow: 1; overflow-y: auto; padding: 32px; display: grid; grid-template-columns: 1fr 340px; gap: 32px; }

    /* Title Block */
    .page-title-wrap { grid-column: span 2; display: flex; justify-content: space-between; align-items: flex-start; }
    .title-left h1 { display: flex; align-items: center; gap: 12px; font-size: 26px; font-weight: 800; color: white; letter-spacing: -0.02em; margin-bottom: 6px; }
    .title-left h1 svg { width: 28px; height: 28px; color: #FFD500; }
    .title-left p { font-size: 13px; color: #9ca3af; }
    .btn-new-analysis { background: #FFD500; color: black; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: background 0.2s; text-decoration: none; }
    .btn-new-analysis:hover { background: #facc15; }

    /* Metrics Grid styling */
    .metrics-row { grid-column: span 2; display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .metric-box { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.04); border-radius: 12px; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
    .metric-info { display: flex; flex-direction: column; }
    .metric-lbl { font-size: 9px; font-weight: 800; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; }
    .metric-val { font-size: 26px; font-weight: 900; color: white; display: flex; align-items: baseline; gap: 4px; }
    .metric-val span { font-size: 13px; color: #6b7280; font-weight: 500; }
    .metric-sub { font-size: 11px; margin-top: 4px; font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .metric-sub.up { color: #10b981; }
    .metric-sub.down { color: #ef4444; }
    .metric-sub.neutral { color: #9ca3af; }
    .metric-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .metric-icon.gold { background: rgba(255,213,0,0.1); border: 1px solid rgba(255,213,0,0.2); color: #FFD500; }
    .metric-icon.green { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #10b981; }
    .metric-icon.red { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; }
    .metric-icon.yellow { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); color: #f59e0b; }
    .metric-icon svg { width: 20px; height: 20px; }

    /* Filters Panel */
    .filters-row { grid-column: span 2; display: flex; gap: 12px; align-items: center; }
    .search-input-wrap { flex-grow: 1; position: relative; }
    .search-input-wrap input { width: 100%; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 11px 16px 11px 40px; color: white; font-size: 13px; outline: none; transition: all 0.2s; }
    .search-input-wrap input:focus { border-color: rgba(255,213,0,0.4); }
    .search-input-wrap svg { position: absolute; left: 14px; top: 12px; width: 16px; height: 16px; color: #4b5563; }
    .filter-select { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 11px 16px; color: #9ca3af; font-size: 13px; outline: none; cursor: pointer; min-width: 140px; transition: all 0.2s; }
    .filter-select:hover { border-color: rgba(255,255,255,0.1); color: white; }
    .date-picker-btn { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 11px 16px; color: #9ca3af; font-size: 13px; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s; }
    .date-picker-btn:hover { border-color: rgba(255,255,255,0.1); color: white; }
    .date-picker-btn svg { width: 16px; height: 16px; color: #4b5563; }

    /* Left Column Timeline & Cards */
    .timeline-container { display: flex; flex-direction: column; position: relative; padding-left: 20px; }
    .timeline-line { position: absolute; left: 6px; top: 0; bottom: 0; width: 1px; background: rgba(255,255,255,0.05); }

    /* Timeline Row Entry */
    .timeline-item { display: flex; gap: 24px; position: relative; margin-bottom: 24px; }
    .timeline-marker { position: absolute; left: -20px; top: 22px; width: 12px; height: 12px; border-radius: 50%; border: 3px solid #050505; z-index: 10; }
    
    .timeline-time-col { min-width: 85px; max-width: 85px; display: flex; flex-direction: column; justify-content: center; font-size: 11px; color: #6b7280; font-weight: 700; line-height: 1.5; padding-top: 14px; text-transform: uppercase; }
    .timeline-time-col span.time { color: #4b5563; font-weight: 500; font-size: 10px; margin-top: 2px; }

    /* Timeline horizontal card box */
    .analysis-card { flex-grow: 1; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.04); border-radius: 16px; padding: 18px 24px; display: grid; grid-template-columns: 100px 1.5fr 1fr 1fr 1.2fr auto; gap: 20px; align-items: center; transition: all 0.3s ease; }
    .analysis-card:hover { border-color: rgba(255,255,255,0.08); background: #0e0e0e; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
    
    /* Thumbnail with hover-zoom */
    .card-thumb-wrap { width: 100px; height: 68px; border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); flex-shrink: 0; position: relative; }
    .card-thumb { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
    .analysis-card:hover .card-thumb { transform: scale(1.1); }
    
    /* Details block */
    .card-details { display: flex; flex-direction: column; gap: 4px; }
    .card-details h3 { color: white; font-size: 15px; font-weight: 800; letter-spacing: -0.01em; }
    .card-details .loc-row { display: flex; align-items: center; gap: 4px; color: #6b7280; font-size: 11.5px; font-weight: 500; }
    .card-details .loc-row svg { width: 12px; height: 12px; color: #4b5563; }

    /* PCI Circle Gauge */
    .pci-circle-col { display: flex; justify-content: center; }
    .pci-circle-svg { position: relative; }
    
    /* Condition & Severity badge column */
    .cond-col { display: flex; flex-direction: column; gap: 6px; }
    .cond-chip { border-radius: 6px; padding: 4px 10px; font-size: 10.5px; font-weight: 800; letter-spacing: 0.05em; display: inline-block; text-align: center; width: max-content; }
    .cond-chip.good      { background: rgba(16,185,129,0.08); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
    .cond-chip.fair      { background: rgba(245,158,11,0.08); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
    .cond-chip.poor      { background: rgba(239,68,68,0.08); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
    .cond-chip.critical  { background: rgba(127,29,29,0.15); color: #ef4444; border: 1px solid rgba(127,29,29,0.3); }
    .cond-chip.invalid   { background: rgba(107,114,128,0.15); color: #9ca3af; border: 1px solid rgba(107,114,128,0.3); }
    
    .sev-lbl { font-size: 9px; color: #4b5563; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; display: flex; flex-direction: column; gap: 1px; }
    .sev-lbl span { font-size: 11px; font-weight: 800; text-transform: uppercase; margin-top: 1px; }

    /* Detected Issues badge tags styling */
    .issues-col { display: flex; flex-direction: column; gap: 6px; }
    .issues-title { font-size: 9px; color: #4b5563; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; }
    .issues-list { display: flex; flex-wrap: wrap; gap: 4px; }
    .issue-badge { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 4px; padding: 2px 6px; font-size: 10px; font-weight: 600; color: #9ca3af; text-transform: capitalize; }
    .issue-badge.more { background: rgba(255,213,0,0.08); border-color: rgba(255,213,0,0.15); color: #FFD500; }

    /* Card Action vertical icons */
    .card-actions-col { display: flex; flex-direction: column; gap: 6px; border-left: 1px solid rgba(255,255,255,0.05); padding-left: 16px; }
    .action-icon-btn { background: transparent; border: 1px solid rgba(255,255,255,0.05); color: #6b7280; width: 30px; height: 30px; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
    .action-icon-btn:hover { background: rgba(255,255,255,0.04); color: white; border-color: rgba(255,255,255,0.15); }
    .action-icon-btn.view-btn:hover { color: #FFD500; border-color: rgba(255,213,0,0.3); }
    .action-icon-btn.delete-btn:hover { color: #ef4444; border-color: rgba(239,68,68,0.3); }
    .action-icon-btn svg { width: 14px; height: 14px; }

    /* Right Sidebar Widgets Grid */
    .right-sidebar { display: flex; flex-direction: column; gap: 24px; }
    .panel { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.04); border-radius: 16px; padding: 20px; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .panel-title { font-size: 13.5px; font-weight: 800; color: white; letter-spacing: -0.01em; }

    /* Widget 1: Condition Distribution styling */
    .donut-container { display: flex; align-items: center; gap: 20px; margin: 8px 0; }
    
    .donut-ring {
        width: 104px;
        height: 104px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        flex-shrink: 0;
    }
    .donut-hole {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #0a0a0a;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .donut-hole .total-num { font-size: 20px; font-weight: 900; color: white; line-height: 1.1; }
    .donut-hole .total-lbl { font-size: 9px; color: #4b5563; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    
    .donut-legend { display: flex; flex-direction: column; gap: 8px; flex-grow: 1; }
    .legend-item { display: flex; align-items: center; justify-content: space-between; font-size: 11.5px; font-weight: 600; color: #9ca3af; }
    .legend-left { display: flex; align-items: center; gap: 6px; }
    .legend-dot { width: 7px; height: 7px; border-radius: 50%; }
    .legend-dot.good { background: #10b981; }
    .legend-dot.fair { background: #f59e0b; }
    .legend-dot.poor { background: #ef4444; }
    .legend-dot.critical { background: #7f1d1d; }
    .legend-pct { color: #4b5563; font-size: 11px; font-weight: 500; }

    /* Widget 2: Analysis Map Overview Vector Illustration */
    .map-box { position: relative; border-radius: 12px; overflow: hidden; height: 180px; border: 1px solid rgba(255,255,255,0.03); background: #050505; }
    .map-vector { width: 100%; height: 100%; opacity: 0.4; }
    .map-glow-pin { position: absolute; width: 14px; height: 14px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 7px; font-weight: 800; color: black; box-shadow: 0 0 10px rgba(0,0,0,0.5); }
    .map-glow-pin::after { content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%; animation: pinPulse 2s infinite ease-out; }
    
    @keyframes pinPulse {
        0% { transform: scale(1); opacity: 1; box-shadow: 0 0 0 0 currentColor; }
        100% { transform: scale(2.4); opacity: 0; box-shadow: 0 0 0 10px currentColor; }
    }
    
    .map-expand-btn { position: absolute; right: 10px; top: 10px; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.05); color: #9ca3af; width: 24px; height: 24px; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
    .map-expand-btn:hover { color: white; background: rgba(0,0,0,0.8); }
    .map-expand-btn svg { width: 12px; height: 12px; }

    /* Widget 3: Recent Activity Feed */
    .activity-list { display: flex; flex-direction: column; gap: 14px; }
    .activity-item { display: flex; gap: 10px; font-size: 11.5px; line-height: 1.5; }
    .activity-indicator { width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
    .activity-indicator.completed { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #10b981; }
    .activity-indicator.alert { background: rgba(245,158,11,0.1); border: 1px solid rgba(245,158,11,0.2); color: #f59e0b; }
    .activity-indicator.export { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); color: #10b981; }
    .activity-indicator svg { width: 8px; height: 8px; }
    .activity-details { display: flex; flex-direction: column; flex-grow: 1; }
    .activity-desc { color: #9ca3af; font-weight: 500; }
    .activity-desc span { color: white; font-weight: 700; }
    .activity-time { color: #4b5563; font-size: 10px; font-weight: 600; margin-top: 2px; }
    .view-all-act-btn { width: 100%; border: 1px solid rgba(255,255,255,0.05); color: #9ca3af; background: transparent; padding: 10px 0; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: all 0.2s; text-align: center; display: block; text-decoration: none; margin-top: 4px; }
    .view-all-act-btn:hover { color: white; background: rgba(255,255,255,0.02); border-color: rgba(255,255,255,0.1); }

    /* Custom Premium Deletion Modal Overlay */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
        z-index: 9999;
    }
    .custom-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }
    /* Modal Box */
    .custom-modal-content {
        background: rgba(15, 15, 15, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 32px;
        width: 90%;
        max-width: 420px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        transform: scale(0.9);
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .custom-modal-overlay.active .custom-modal-content {
        transform: scale(1);
    }
    /* Warning Icon */
    .modal-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .modal-icon svg {
        width: 28px;
        height: 28px;
        color: #ef4444;
    }
    /* Title & Text */
    .custom-modal-content h3 {
        color: white;
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .custom-modal-content p {
        color: #9ca3af;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    /* Action Buttons */
    .modal-actions {
        display: flex;
        gap: 12px;
    }
    .modal-btn-cancel {
        flex: 1;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.1);
        color: #9ca3af;
        border-radius: 8px;
        padding: 10px 0;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .modal-btn-cancel:hover {
        background: rgba(255,255,255,0.05);
        color: white;
    }
    .modal-btn-confirm {
        flex: 1;
        background: #ef4444;
        border: none;
        color: white;
        border-radius: 8px;
        padding: 10px 0;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }
    .modal-btn-confirm:hover {
        background: #dc2626;
    }

    /* Premium Theme Pagination */
    .paging-container { display: flex; justify-content: center; padding-top: 12px; margin-top: 8px; }
    .custom-pagination { display: flex; align-items: center; gap: 6px; }
    .custom-pagination a, .custom-pagination span {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 700;
        color: #9ca3af;
        background: #0a0a0a;
        border: 1px solid rgba(255,255,255,0.04);
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .custom-pagination a:hover {
        border-color: rgba(255,255,255,0.12);
        color: white;
        background: rgba(255,255,255,0.02);
    }
    .custom-pagination span.active {
        background: #FFD500;
        color: black;
        border-color: #FFD500;
    }
    .custom-pagination span.dots {
        background: transparent;
        border: none;
        cursor: default;
    }
</style>
@endsection

@section('content')
<div class="reports-layout">
    <!-- Sidebar Left -->
    @include('partials.sidebar')

    <!-- Main Content Area -->
    <main class="main-area">
        <div class="main-scrollable">
            
            <!-- Dynamic Success Banner -->
            @if(session('success'))
            <div style="grid-column: span 2; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.25); color: #10b981; padding: 12px 16px; border-radius: 8px; font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px; height:18px; flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('success') }}
            </div>
            @endif

            <!-- 1. Header Row -->
            <div class="page-title-wrap">
                <div class="title-left">
                    <h1>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Analysis History
                    </h1>
                    <p>Track all your road analyses and monitor pavement conditions over time.</p>
                </div>
                <a href="/upload" class="btn-new-analysis">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Analysis
                </a>
            </div>

            <!-- 2. Metrics Cards Row -->
            <div class="metrics-row">
                <!-- Total Analyses -->
                <div class="metric-box">
                    <div class="metric-info">
                        <span class="metric-lbl">Total Analyses</span>
                        <span class="metric-val">{{ $totalAnalyses }}</span>
                        <span class="metric-sub up">
                            &uarr; 12.5% this month
                        </span>
                    </div>
                    <div class="metric-icon gold">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>

                <!-- Average PCI Score -->
                <div class="metric-box">
                    <div class="metric-info">
                        <span class="metric-lbl">Average PCI Score</span>
                        <span class="metric-val">{{ $avgPciScore }} <span>/100</span></span>
                        <span class="metric-sub up">
                            &uarr; 5% from last month
                        </span>
                    </div>
                    <div class="metric-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </div>

                <!-- Worst Condition -->
                <div class="metric-box">
                    <div class="metric-info">
                        <span class="metric-lbl">Worst Condition</span>
                        <span class="metric-val" style="color:{{ $worstCondition == 'GOOD' ? '#10b981' : ($worstCondition == 'FAIR' ? '#f59e0b' : '#ef4444') }}; font-size:22px; padding:3px 0;">{{ $worstCondition }}</span>
                        <span class="metric-sub neutral">
                            {{ $worstDate }}
                        </span>
                    </div>
                    <div class="metric-icon red">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>

                <!-- Critical Alerts -->
                <div class="metric-box">
                    <div class="metric-info">
                        <span class="metric-lbl">Critical Alerts</span>
                        <span class="metric-val" style="color:{{ $criticalAlerts > 0 ? '#ef4444' : 'white' }}">{{ $criticalAlerts }}</span>
                        <span class="metric-sub {{ $criticalAlerts > 0 ? 'down' : 'neutral' }}">
                            {{ $criticalAlerts > 0 ? 'Requires attention' : 'All systems clear' }}
                        </span>
                    </div>
                    <div class="metric-icon yellow">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                </div>
            </div>

            <!-- 3. Search & Filter Bar -->
            <div class="filters-row">
                <div class="search-input-wrap">
                    <input type="text" id="search-input" placeholder="Search by Report ID or Location..." oninput="applyFilters()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <select id="condition-filter" class="filter-select" onchange="applyFilters()">
                    <option value="">All Conditions</option>
                    <option value="GOOD">Good</option>
                    <option value="FAIR">Fair</option>
                    <option value="POOR">Poor</option>
                    <option value="CRITICAL">Critical</option>
                </select>
                <select id="severity-filter" class="filter-select" onchange="applyFilters()">
                    <option value="">All Severities</option>
                    <option value="LOW">Low</option>
                    <option value="MEDIUM">Medium</option>
                    <option value="HIGH">High</option>
                </select>
                <div class="date-picker-btn" onclick="alert('Date range picker coming soon!')">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>14 May 2026 - 21 May 2026</span>
                </div>
            </div>

            <!-- 4. Left Column: Timeline List -->
            <div class="timeline-container">
                <div class="timeline-line"></div>

                @if($analyses->count() > 0)
                <div id="timeline-cards-wrap">
                    @foreach($analyses as $a)
                    @php
                        // Determine severe level and color
                        $pciColor = strtolower($a->condition) === 'invalid' ? '#6b7280' : ($a->pci_score >= 75 ? '#10b981' : ($a->pci_score >= 55 ? '#f59e0b' : '#ef4444'));
                        $sevColor = strtolower($a->condition) === 'invalid' ? '#6b7280' : ($a->pci_score >= 75 ? '#10b981' : ($a->pci_score >= 55 ? '#f59e0b' : '#ef4444'));
                        
                        // Parse issue tag items
                        $issues = [];
                        if($a->detections) {
                            $decoded = is_string($a->detections) ? json_decode($a->detections, true) : $a->detections;
                            // Fallback for double-encoding
                            if(is_string($decoded)) {
                                $decoded = json_decode($decoded, true);
                            }
                            if(is_array($decoded)) {
                                foreach($decoded as $issueItem) {
                                    if(isset($issueItem['label'])) {
                                        $issues[] = str_replace('_', ' ', $issueItem['label']);
                                    }
                                }
                            }
                        }
                        if(empty($issues) && $a->total_defects > 0) {
                            $issues = ['Pothole', 'Crack'];
                        }
                        
                        // Limits issues rendered to 2 with "+X" badge
                        $renderedIssues = array_slice(array_unique($issues), 0, 2);
                        $remainingCount = count(array_unique($issues)) - count($renderedIssues);
                    @endphp
                    
                    <div class="timeline-item filterable-card" data-scan-id="{{ strtolower($a->scan_id) }}" data-location="{{ strtolower($a->location) }}" data-condition="{{ strtoupper($a->condition) }}" data-severity="{{ strtoupper($a->severity) }}">
                        <!-- Colored timeline pin indicator -->
                        <div class="timeline-marker" style="background:{{ $pciColor }}; border-color:#050505;"></div>
                        
                        <!-- Timeline Time Node -->
                        <div class="timeline-time-col">
                            {{ $a->created_at->format('d M Y') }}
                            <span class="time">{{ $a->created_at->format('h:i A') }}</span>
                        </div>
                        
                        <!-- Main Card -->
                        <div class="analysis-card">
                            <!-- Thumbnail -->
                            <div class="card-thumb-wrap">
                                @if($a->image_path)
                                    <img src="{{ asset('storage/' . $a->image_path) }}" alt="Road scan" class="card-thumb">
                                @else
                                    <div style="background:#151515;width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#4b5563;font-size:10px;">No scan</div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="card-details">
                                <h3>{{ $a->title ?? $a->scan_id }}</h3>
                                @if($a->title)
                                    <div style="font-size:11px; color:#FFD500; font-weight:600; margin-bottom: 2px;">Scan: {{ $a->scan_id }}</div>
                                @endif
                                <div class="loc-row">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ Str::limit($a->location ?: 'Unknown Location', 18) }}
                                </div>
                            </div>

                            <!-- PCI Circle Gauge column -->
                            <div class="pci-circle-col">
                                <svg class="pci-circle-svg" width="56" height="56" viewBox="0 0 64 64">
                                    <circle class="pci-circle-bg" cx="32" cy="32" r="26" fill="none" stroke="rgba(255,255,255,0.04)" stroke-width="4.5"/>
                                    <circle class="pci-circle-fill" cx="32" cy="32" r="26" fill="none" stroke="{{ $pciColor }}" stroke-width="4.5" 
                                            stroke-dasharray="163.36" stroke-dashoffset="{{ 163.36 - (163.36 * $a->pci_score) / 100 }}" stroke-linecap="round" transform="rotate(-90 32 32)"/>
                                    <text x="32" y="30" text-anchor="middle" fill="white" font-size="15" font-weight="900" font-family="Inter">{{ strtolower($a->condition) === 'invalid' ? 'N/A' : $a->pci_score }}</text>
                                    <text x="32" y="44" text-anchor="middle" fill="#6b7280" font-size="8.5" font-weight="700" font-family="Inter">PCI Score</text>
                                </svg>
                            </div>

                            <!-- Condition badge -->
                            <div class="cond-col">
                                <span class="cond-chip {{ strtolower($a->condition) }}">{{ strtoupper($a->condition) }}</span>
                                <div class="sev-lbl">
                                    Severity
                                    <span style="color:{{ $sevColor }}">{{ strtoupper($a->severity) }}</span>
                                </div>
                            </div>

                            <!-- Detected Issues tags -->
                            <div class="issues-col">
                                <span class="issues-title">Detected Issues</span>
                                <div class="issues-list">
                                    @foreach($renderedIssues as $issue)
                                        <span class="issue-badge">{{ $issue }}</span>
                                    @endforeach
                                    @if($remainingCount > 0)
                                        <span class="issue-badge more">+{{ $remainingCount }}</span>
                                    @endif
                                    @if(empty($renderedIssues))
                                        <span style="color:#4b5563;font-size:11px;font-weight:500;">No defects</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Action icons -->
                            <div class="card-actions-col">
                                @if(!$a->is_registered)
                                    <button class="action-icon-btn register-btn" onclick="openRegisterModal('{{ $a->id }}', '{{ addslashes($a->location) }}')" title="Register Problem" style="color: #10b981; border-color: rgba(16,185,129,0.3);">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                @endif
                                <button class="action-icon-btn view-btn" onclick="window.location='/dashboard/report-export?id={{ $a->id }}'" title="View Full Report">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </button>
                                <button class="action-icon-btn delete-btn" onclick="openDeleteModal('{{ $a->id }}')" title="Delete Analysis">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                
                                <form id="delete-form-{{ $a->id }}" action="/reports/{{ $a->id }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Custom styled Pagination matching image bar -->
                @if($analyses->hasPages())
                <div class="paging-container">
                    <div class="custom-pagination">
                        {{-- Previous Page Link --}}
                        @if ($analyses->onFirstPage())
                            <span class="dots">&larr;</span>
                        @else
                            <a href="{{ $analyses->previousPageUrl() }}">&larr;</a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($analyses->links()->elements[0] as $page => $url)
                            @if ($page == $analyses->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($analyses->hasMorePages())
                            <a href="{{ $analyses->nextPageUrl() }}">&rarr;</a>
                        @else
                            <span class="dots">&rarr;</span>
                        @endif
                    </div>
                </div>
                @endif

                @else
                <div class="empty-state" style="grid-column: span 2; background:#0a0a0a; border: 1px dashed rgba(255,255,255,0.06); border-radius:16px; padding:64px; text-align:center;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:48px;height:48px;color:#374151;margin-bottom:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <h3 style="color:white;font-size:16px;font-weight:700;margin-bottom:6px;">No road analyses captured</h3>
                    <p style="color:#6b7280;font-size:12.5px;margin-bottom:16px;">Perform your first AI-annotated street inspection to generate report history logs.</p>
                    <a href="/upload" style="background:#FFD500;color:black;padding:10px 20px;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;">Inspect Pavement Now</a>
                </div>
                @endif
            </div>

            <!-- 5. Right Sidebar: Widgets stacked vertically -->
            <div class="right-sidebar">
                
                <!-- Widget 1: Condition Distribution Donut chart -->
                @php
                    $totalVal = $totalAnalyses > 0 ? $totalAnalyses : 1;
                    $goodPct = round(($goodCount / $totalVal) * 100);
                    $fairPct = round(($fairCount / $totalVal) * 100);
                    $poorPct = round(($poorCount / $totalVal) * 100);
                    $criticalPct = round(($criticalCount / $totalVal) * 100);
                @endphp
                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-title">Condition Distribution</span>
                    </div>
                    <div class="donut-container">
                        <!-- Custom CSS Conic gradient donut representation -->
                        <div class="donut-ring" style="background: conic-gradient(#10b981 0% {{ $goodPct }}%, #f59e0b {{ $goodPct }}% {{ $goodPct + $fairPct }}%, #ef4444 {{ $goodPct + $fairPct }}% {{ $goodPct + $fairPct + $poorPct }}%, #7f1d1d {{ $goodPct + $fairPct + $poorPct }}% 100%);">
                            <div class="donut-hole">
                                <span class="total-num">{{ $totalAnalyses }}</span>
                                <span class="total-lbl">Total</span>
                            </div>
                        </div>
                        <div class="donut-legend">
                            <div class="legend-item">
                                <div class="legend-left">
                                    <div class="legend-dot good"></div>
                                    Good
                                </div>
                                <span>{{ $goodCount }} <span class="legend-pct">({{ $goodPct }}%)</span></span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-left">
                                    <div class="legend-dot fair"></div>
                                    Fair
                                </div>
                                <span>{{ $fairCount }} <span class="legend-pct">({{ $fairPct }}%)</span></span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-left">
                                    <div class="legend-dot poor"></div>
                                    Poor
                                </div>
                                <span>{{ $poorCount }} <span class="legend-pct">({{ $poorPct }}%)</span></span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-left">
                                    <div class="legend-dot critical"></div>
                                    Critical
                                </div>
                                <span>{{ $criticalCount }} <span class="legend-pct">({{ $criticalPct }}%)</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widget 2: Analysis Map Overview -->
                <div class="panel" style="padding: 18px 20px;">
                    <div class="panel-header" style="margin-bottom:12px;">
                        <span class="panel-title">Analysis Map Overview</span>
                    </div>
                    <div class="map-box" id="mini-map-container" style="border: 1px solid rgba(255,255,255,0.05); position: relative; z-index: 1;">
                        @if($totalAnalyses == 0)
                            <div style="position: absolute; top:0; left:0; width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; background:rgba(5,5,5,0.8); z-index:1000; font-size:11px; color:#6b7280; font-weight:600;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;margin-bottom:8px;opacity:0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                No Analyses Yet
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Widget 3: Recent Activity dynamic logger feed -->
                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-title">Recent Activity</span>
                    </div>
                    <div class="activity-list">
                        @foreach($recentActivities as $act)
                        @php
                            $actPci = $act->pci_score;
                            $iconType = $actPci >= 75 ? 'completed' : ($actPci >= 55 ? 'export' : 'alert');
                        @endphp
                        <div class="activity-item">
                            <div class="activity-indicator {{ $iconType }}">
                                @if($iconType == 'completed')
                                    <svg fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                @elseif($iconType == 'export')
                                    <svg fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                @else
                                    <svg fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                @endif
                            </div>
                            <div class="activity-details">
                                <div class="activity-desc">
                                    @if($iconType == 'completed')
                                        Pavement distress analyzed for <span>{{ $act->scan_id }}</span>
                                    @elseif($iconType == 'export')
                                        Assessment report exported for <span>{{ $act->scan_id }}</span>
                                    @else
                                        Critical issue marked on scan <span>{{ $act->scan_id }}</span>
                                    @endif
                                </div>
                                <span class="activity-time">{{ $act->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($recentActivities->isEmpty())
                        <div style="color:#4b5563;font-size:12px;text-align:center;padding:12px 0;">No activities logged yet</div>
                        @endif
                    </div>
                    <a href="/dashboard" class="view-all-act-btn">View All Activity &rarr;</a>
                </div>

            </div>

        </div>
    </main>
</div>

<!-- Custom Premium Deletion Modal Overlay -->
<div id="delete-modal" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <div class="modal-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3>Delete Analysis Report?</h3>
        <p>This action is permanent and cannot be undone. The corresponding database record and analyzed image will be removed from disk.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="modal-btn-confirm" id="confirm-delete-btn">Delete Report</button>
        </div>
    </div>
</div>

<!-- Modal: Register Problem -->
<div class="modal-overlay" id="modal-register-problem" style="position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 10000;">
    <div class="modal-box" style="background: #0a0a0a; border: 1px solid rgba(255,213,0,0.3); border-radius: 16px; padding: 32px; width: 500px; max-width: 90%; box-shadow: 0 0 30px rgba(255,213,0,0.15); display: flex; flex-direction: column; gap: 20px; position: relative;">
        <button class="modal-close" onclick="closeRegisterModal()" style="position: absolute; top: 20px; right: 20px; color: #6b7280; cursor: pointer; background: none; border: none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px; height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title" style="font-size: 20px; font-weight: 800; color: white; display: flex; align-items: center; gap: 12px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: #FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Register Road Problem
        </div>
        <p style="color: #9ca3af; font-size: 13px; line-height: 1.4; margin-top: -8px;">Provide registration details. Registered issues are forwarded immediately to municipal officers.</p>
        
        <form id="reports-register-form" style="display: flex; flex-direction: column; gap: 16px;">
            <input type="hidden" id="reg-analysis-id">
            
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Problem Title *</label>
                <input type="text" id="reg-title" placeholder="E.g., Deep potholes near intersection" required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Location / Address *</label>
                <input type="text" id="reg-location" placeholder="E.g., MG Road, Sector 4" required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Landmark</label>
                <input type="text" id="reg-landmark" placeholder="E.g., Near metro station pillar 42" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Remarks / Message</label>
                <textarea id="reg-remarks" placeholder="Provide extra context, severity or safety warnings for the repair team..." style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; padding: 12px; font-size: 13px; font-family:'Inter',sans-serif; height: 100px; resize: none; outline: none;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px;">
                <button type="button" class="btn btn-outline" onclick="closeRegisterModal()" style="padding: 10px 20px; border-radius: 8px; color: white; border: 1px solid rgba(255,255,255,0.1); background: transparent; cursor: pointer; font-weight: 600;">Cancel</button>
                <button type="submit" class="btn" style="padding: 10px 20px; border-radius: 8px; color: black; background: #FFD500; border: none; font-weight: 700; cursor: pointer;">Register &amp; Report</button>
            </div>
        </form>
    </div>
</div>

<script>
    let activeDeleteFormId = null;

    function openDeleteModal(id) {
        activeDeleteFormId = id;
        document.getElementById('delete-modal').classList.add('active');
    }

    function closeDeleteModal() {
        activeDeleteFormId = null;
        document.getElementById('delete-modal').classList.remove('active');
    }

    // Close modal on clicking outside content area
    document.getElementById('delete-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        if (activeDeleteFormId) {
            document.getElementById('delete-form-' + activeDeleteFormId).submit();
        }
    });

    function openRegisterModal(id, location) {
        document.getElementById('reg-analysis-id').value = id;
        document.getElementById('reg-location').value = location;
        document.getElementById('reg-title').value = '';
        document.getElementById('reg-landmark').value = '';
        document.getElementById('reg-remarks').value = '';
        document.getElementById('modal-register-problem').style.display = 'flex';
    }

    function closeRegisterModal() {
        document.getElementById('modal-register-problem').style.display = 'none';
    }

    document.getElementById('reports-register-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('reg-analysis-id').value;
        const title = document.getElementById('reg-title').value;
        const location = document.getElementById('reg-location').value;
        const landmark = document.getElementById('reg-landmark').value;
        const remarks = document.getElementById('reg-remarks').value;

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.textContent = 'Registering...';
        submitBtn.disabled = true;

        const fd = new FormData();
        fd.append('title', title);
        fd.append('location', location);
        fd.append('landmark', landmark);
        fd.append('remarks', remarks);
        fd.append('json', '1');
        fd.append('_token', '{{ csrf_token() }}');

        fetch('/reports/' + id + '/register', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: fd
        })
        .then(r => {
            if(!r.ok) throw new Error('Failed to register');
            return r.json();
        })
        .then(data => {
            closeRegisterModal();
            alert('Success! Problem has been registered and forwarded to municipal officers.');
            window.location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Registration failed. Please try again.');
            submitBtn.textContent = 'Register & Report';
            submitBtn.disabled = false;
        });
    });

    // Real-time client-side listing filtration for ultra-smooth instantaneous UX!
    function applyFilters() {
        const query = document.getElementById('search-input').value.toLowerCase().trim();
        const condition = document.getElementById('condition-filter').value.toUpperCase();
        const severity = document.getElementById('severity-filter').value.toUpperCase();
        
        const cards = document.querySelectorAll('.filterable-card');
        
        cards.forEach(card => {
            const cardScanId = card.getAttribute('data-scan-id');
            const cardLocation = card.getAttribute('data-location');
            const cardCondition = card.getAttribute('data-condition');
            const cardSeverity = card.getAttribute('data-severity');
            
            const matchesSearch = !query || cardScanId.includes(query) || cardLocation.includes(query);
            const matchesCondition = !condition || cardCondition === condition;
            const matchesSeverity = !severity || cardSeverity === severity;
            
            if (matchesSearch && matchesCondition && matchesSeverity) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

@if($totalAnalyses > 0)
<!-- Leaflet CSS & JS for Mini Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var locations = [
        @foreach($analyses as $a)
            @if($a->latitude && $a->longitude)
                { lat: {{ $a->latitude }}, lng: {{ $a->longitude }}, condition: '{{ strtoupper($a->condition) }}' },
            @endif
        @endforeach
    ];

    if(locations.length > 0) {
        var map = L.map('mini-map-container', {
            zoomControl: false,
            attributionControl: false
        }).setView([locations[0].lat, locations[0].lng], 10);

        // Dark map tiles to match UI
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        var bounds = [];
        locations.forEach(function(loc) {
            var color = '#10b981'; // Good
            if(loc.condition === 'FAIR') color = '#f59e0b';
            else if(loc.condition === 'POOR') color = '#ef4444';
            else if(loc.condition === 'CRITICAL') color = '#7f1d1d';
            else if(loc.condition === 'INVALID') color = '#6b7280';

            L.circleMarker([loc.lat, loc.lng], {
                radius: 6,
                fillColor: color,
                color: '#000',
                weight: 2,
                opacity: 1,
                fillOpacity: 1
            }).addTo(map);
            
            bounds.push([loc.lat, loc.lng]);
        });

        if(bounds.length > 1) {
            map.fitBounds(bounds, { padding: [15, 15] });
        }
    } else {
        document.getElementById('mini-map-container').innerHTML = '<div style="position: absolute; top:0; left:0; width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; background:rgba(5,5,5,0.8); z-index:1000; font-size:11px; color:#6b7280; font-weight:600;"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:24px;height:24px;margin-bottom:8px;opacity:0.5;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>No location data available</div>';
    }
</script>
@endif
@endsection
