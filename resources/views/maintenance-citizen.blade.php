@php $hideFooter = true; @endphp
@extends('layouts.app')
@section('title', 'Maintenance Requests')

@section('styles')
<style>
    /* Styling configuration */
    body { background: #050505; overflow: hidden; font-family: 'Inter', sans-serif; }
    .maintenance-layout { display: flex; height: calc(100vh - 73px); }

    /* Sidebar Left */
    .sidebar { width: 240px; min-width: 240px; background: #080808; border-right: 1px solid rgba(255,255,255,0.05); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-nav { padding: 16px 12px; flex-grow: 1; display: flex; flex-direction: column; gap: 4px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; text-decoration: none; }
    .nav-item:hover { background: rgba(255,255,255,0.04); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.1); color: #FFD500; font-weight: 700; border-left: 3px solid #FFD500; border-radius: 4px 8px 8px 4px; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-badge-ai { margin-left: auto; background: #FFD500; color: black; font-size: 9px; font-weight: 800; padding: 1px 5px; border-radius: 10px; text-transform: uppercase; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 8px 12px; }

    /* Premium Upgrade Card */
    .sidebar-premium { margin: 12px; background: linear-gradient(135deg, rgba(255,213,0,0.12) 0%, rgba(255,213,0,0.02) 100%); border: 1px solid rgba(255,213,0,0.15); border-radius: 12px; padding: 16px; }
    .sidebar-premium .crown { font-size: 18px; margin-bottom: 8px; display: block; }
    .sidebar-premium h4 { color: white; font-size: 13px; font-weight: 700; margin-bottom: 4px; }
    .sidebar-premium p { color: #9ca3af; font-size: 10.5px; line-height: 1.4; margin-bottom: 12px; }
    .sidebar-premium .btn-upgrade { width: 100%; background: #FFD500; color: black; border: none; border-radius: 6px; padding: 8px 0; font-size: 12px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.2s; }
    .sidebar-premium .btn-upgrade:hover { background: #facc15; }
    .sidebar-lang { padding: 12px 16px 16px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 8px; color: #9ca3af; font-size: 13px; cursor: pointer; }
    .sidebar-lang svg { width: 16px; height: 16px; }

    /* Scrollable Container spacing */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; background: #050505; }
    .main-scrollable { flex-grow: 1; overflow-y: auto; padding: 32px; display: grid; grid-template-columns: 1.5fr 1fr 340px; gap: 24px; }

    /* Header component */
    .page-header-wrap { grid-column: span 3; display: flex; justify-content: space-between; align-items: flex-start; }
    .header-left h1 { display: flex; align-items: center; gap: 10px; font-size: 24px; font-weight: 800; color: white; letter-spacing: -0.02em; margin-bottom: 6px; }
    .header-left h1 svg { width: 26px; height: 26px; color: #FFD500; }
    .header-left p { font-size: 13px; color: #9ca3af; }
    .btn-back { border: 1px solid rgba(255,255,255,0.08); background: #0a0a0a; color: #9ca3af; padding: 8px 16px; border-radius: 6px; font-size: 12.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; text-decoration: none; }
    .btn-back:hover { color: white; border-color: rgba(255,255,255,0.15); background: rgba(255,255,255,0.02); }

    /* Interactive Buttons Top Row Metrics */
    .metrics-row { grid-column: span 3; display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .metric-btn-box { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.04); border-radius: 12px; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; text-align: left; transition: all 0.25s ease; width: 100%; outline: none; }
    .metric-btn-box:hover { background: #0f0f0f; border-color: rgba(255,255,255,0.1); transform: translateY(-2px); box-shadow: 0 8px 16px rgba(0,0,0,0.3); }
    .metric-btn-box.active { background: rgba(255,213,0,0.04); border-color: rgba(255,213,0,0.3); box-shadow: 0 0 15px rgba(255,213,0,0.05); }
    
    .metric-info { display: flex; flex-direction: column; }
    .metric-lbl { font-size: 9.5px; font-weight: 800; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 6px; }
    .metric-val { font-size: 24px; font-weight: 900; color: white; line-height: 1.1; }
    .metric-sub { font-size: 11px; font-weight: 600; margin-top: 4px; }
    .metric-sub.green { color: #10b981; }
    .metric-sub.gold { color: #f59e0b; }
    .metric-sub.red { color: #ef4444; }

    .metric-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .metric-icon.green { background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.15); color: #10b981; }
    .metric-icon.gold { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.15); color: #f59e0b; }
    .metric-icon.red { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.15); color: #ef4444; }
    .metric-icon svg { width: 18px; height: 18px; }

    /* Left / Middle card elements */
    .card-wrap { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.04); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; gap: 20px; }
    .card-title { font-size: 14px; font-weight: 800; color: white; display: flex; align-items: center; gap: 8px; }
    .card-title span.badge-num { width: 20px; height: 20px; border-radius: 50%; background: #FFD500; color: black; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; }

    /* Form Fields */
    .field-group { display: flex; flex-direction: column; gap: 6px; }
    .field-label { font-size: 11.5px; font-weight: 700; color: #9ca3af; }
    .field-label span { color: #ef4444; }
    
    .field-input, .field-textarea, .field-select { background: #050505; border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 10px 14px; color: white; font-size: 13px; outline: none; transition: border 0.2s; width: 100%; box-sizing: border-box; }
    .field-input:focus, .field-textarea:focus, .field-select:focus { border-color: rgba(255,213,0,0.4); }
    .field-textarea { resize: none; height: 96px; }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    
    /* Image Upload box */
    .upload-drag-box { height: 96px; border: 1px dashed rgba(255,255,255,0.08); border-radius: 8px; background: #050505; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.2s; }
    .upload-drag-box:hover { border-color: rgba(255,213,0,0.3); background: rgba(255,213,0,0.01); }
    .upload-drag-box svg { width: 24px; height: 24px; color: #6b7280; }
    .upload-drag-box span { font-size: 11px; color: #6b7280; font-weight: 600; text-align: center; }

    /* Submit Button styling */
    .btn-submit-request { background: #FFD500; color: black; border: none; border-radius: 8px; padding: 12px 0; font-size: 13px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.2s; width: 100%; }
    .btn-submit-request:hover { background: #facc15; }
    .btn-submit-request svg { width: 14px; height: 14px; }

    /* Location Preview Widget */
    .map-container { height: 240px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.04); background: #050505; position: relative; overflow: hidden; }
    .map-svg { width: 100%; height: 100%; opacity: 0.45; }
    .map-marker-pin { position: absolute; left: 130px; top: 110px; width: 18px; height: 18px; border-radius: 50%; background: #FFD500; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(255,213,0,0.6); }
    .map-marker-pin::after { content: ''; position: absolute; width: 100%; height: 100%; border-radius: 50%; background: #FFD500; animation: mapPulse 2.5s infinite ease-out; }
    
    @keyframes mapPulse {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(3); opacity: 0; }
    }
    
    .map-overlay-lbl { position: absolute; left: 105px; top: 75px; background: rgba(0,0,0,0.85); backdrop-filter: blur(4px); border: 1px solid rgba(255,255,255,0.06); border-radius: 6px; padding: 6px 12px; text-align: center; }
    .map-overlay-lbl div { font-size: 11px; font-weight: 800; color: white; }
    .map-overlay-lbl span { font-size: 9px; color: #6b7280; font-weight: 600; }
    
    .map-zoom-tools { position: absolute; right: 10px; bottom: 10px; display: flex; flex-direction: column; gap: 2px; }
    .zoom-tool { background: rgba(0,0,0,0.7); border: 1px solid rgba(255,255,255,0.05); color: #9ca3af; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; cursor: pointer; border-radius: 4px; }
    .zoom-tool:hover { color: white; background: rgba(0,0,0,0.9); }

    .loc-details-wrap { display: flex; flex-direction: column; gap: 12px; margin-top: 8px; }
    .loc-title { font-size: 12.5px; font-weight: 800; color: white; }
    .loc-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 12px; }
    .loc-entry { display: flex; align-items: flex-start; gap: 8px; font-size: 11.5px; }
    .loc-entry svg { width: 14px; height: 14px; color: #4b5563; flex-shrink: 0; margin-top: 1px; }
    .loc-entry-details { display: flex; flex-direction: column; }
    .loc-lbl { font-size: 9.5px; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; }
    .loc-val { font-weight: 600; color: #9ca3af; margin-top: 1px; }

    /* Right column widgets */
    .sidebar-right { display: flex; flex-direction: column; gap: 24px; }
    .panel { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.04); border-radius: 16px; padding: 20px; }
    .panel-title { font-size: 13.5px; font-weight: 800; color: white; letter-spacing: -0.01em; margin-bottom: 16px; display: block; }

    /* Summary list */
    .summary-list { display: flex; flex-direction: column; gap: 12px; }
    .summary-item { display: flex; justify-content: space-between; align-items: center; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 10px; }
    .summary-item:last-child { border: none; padding-bottom: 0; }
    .summary-item .lbl { color: #6b7280; font-weight: 500; display: flex; align-items: center; gap: 6px; }
    .summary-item .lbl svg { width: 14px; height: 14px; color: #4b5563; }
    .summary-item .val { color: white; font-weight: 700; text-align: right; }
    .summary-item .val.red-pill { display: flex; align-items: center; gap: 6px; color: #ef4444; }
    .summary-item .val.red-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #ef4444; display: inline-block; }

    /* Status progress flow widget styling */
    .status-timeline { display: flex; flex-direction: column; position: relative; padding-left: 16px; margin: 8px 0; }
    .status-timeline-line { position: absolute; left: 5px; top: 8px; bottom: 8px; width: 1px; background: rgba(255,255,255,0.05); }
    .status-node { display: flex; gap: 14px; position: relative; margin-bottom: 18px; }
    .status-node:last-child { margin-bottom: 0; }
    .status-dot { position: absolute; left: -16px; top: 4px; width: 10px; height: 10px; border-radius: 50%; border: 2px solid #0a0a0a; background: rgba(255,255,255,0.1); z-index: 10; }
    .status-node.active .status-dot { background: #FFD500; box-shadow: 0 0 8px rgba(255,213,0,0.5); }
    .status-node.active .status-desc { color: white; font-weight: 700; }
    .status-node.active .status-time { display: block; }
    
    .status-details { display: flex; flex-direction: column; }
    .status-desc { font-size: 12px; color: #6b7280; font-weight: 600; }
    .status-time { font-size: 9.5px; color: #4b5563; margin-top: 2px; font-weight: 600; display: none; }
    .status-node.pending-node .status-desc { color: #4b5563; }

    /* Authority badges */
    .auth-list { display: flex; flex-direction: column; gap: 12px; }
    .auth-badge { background: #050505; border: 1px solid rgba(255,255,255,0.04); border-radius: 10px; padding: 12px; display: flex; align-items: center; gap: 12px; }
    .auth-icon { width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 11px; flex-shrink: 0; }
    .auth-icon.orange { background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.15); color: #f59e0b; }
    .auth-icon.blue { background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.15); color: #3b82f6; }
    .auth-meta { display: flex; flex-direction: column; }
    .auth-name { font-size: 11px; font-weight: 700; color: white; line-height: 1.3; }
    .auth-type { font-size: 9px; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 2px; }
    .auth-type.primary { color: #f59e0b; }
    .auth-type.notified { color: #3b82f6; }

    /* Bottom banner: "What happens next?" */
    .bottom-flow-wrap { grid-column: span 3; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.04); border-radius: 16px; padding: 24px; text-align: center; }
    .bottom-flow-wrap h3 { color: white; font-size: 13.5px; font-weight: 800; letter-spacing: 0.02em; margin-bottom: 24px; text-transform: uppercase; }
    
    .flow-steps { display: flex; justify-content: space-between; align-items: center; max-width: 800px; margin: 0 auto; position: relative; }
    .flow-steps::after { content: ''; position: absolute; left: 10%; right: 10%; top: 18px; height: 1px; background: rgba(255,255,255,0.05); z-index: 1; }
    .flow-step-item { display: flex; flex-direction: column; align-items: center; gap: 8px; width: 100px; position: relative; z-index: 5; }
    .flow-step-icon { width: 36px; height: 36px; border-radius: 50%; background: #050505; border: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; color: #FFD500; transition: all 0.3s; }
    .flow-step-item.active .flow-step-icon { border-color: #FFD500; background: rgba(255,213,0,0.04); box-shadow: 0 0 10px rgba(255,213,0,0.15); }
    .flow-step-icon svg { width: 16px; height: 16px; }
    .flow-step-lbl { font-size: 11px; font-weight: 800; color: white; }
    .flow-step-sub { font-size: 9.5px; color: #4b5563; font-weight: 600; text-align: center; line-height: 1.3; }

    /* Interactive Database List Drawer Panel */
    .db-drawer-panel { grid-column: span 2; display: none; flex-direction: column; gap: 16px; }
    .db-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .db-title-col h2 { font-size: 18px; font-weight: 800; color: white; display: flex; align-items: center; gap: 8px; }
    .db-title-col h2 span { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px; color: #9ca3af; }
    .db-title-col p { font-size: 12px; color: #6b7280; margin-top: 2px; }
    
    .btn-create-toggle { border: 1px solid rgba(255,213,0,0.3); background: rgba(255,213,0,0.05); color: #FFD500; padding: 8px 16px; border-radius: 6px; font-size: 12.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
    .btn-create-toggle:hover { background: rgba(255,213,0,0.1); }
    .btn-create-toggle svg { width: 14px; height: 14px; }

    .db-table-box { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.04); border-radius: 16px; overflow: hidden; }
    .db-table { width: 100%; border-collapse: collapse; text-align: left; }
    .db-table th { padding: 14px 18px; font-size: 9.5px; font-weight: 800; color: #4b5563; text-transform: uppercase; letter-spacing: 0.08em; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .db-table td { padding: 14px 18px; font-size: 12.5px; color: #9ca3af; border-bottom: 1px solid rgba(255,255,255,0.03); vertical-align: middle; }
    .db-table tr:last-child td { border: none; }
    .db-table tr:hover td { background: rgba(255,255,255,0.01); color: white; }
    
    .db-table td.title-cell { font-weight: 700; color: white; }
    .db-table td.title-cell span { display: block; font-size: 10px; color: #4b5563; font-weight: 500; margin-top: 2px; }

    .db-badge { padding: 4px 8px; border-radius: 5px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.04em; display: inline-block; }
    .db-badge.pending { background: rgba(245,158,11,0.08); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
    .db-badge.resolved { background: rgba(16,185,129,0.08); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
    .db-badge.critical { background: rgba(239,68,68,0.08); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

    .db-priority { display: flex; align-items: center; gap: 6px; font-weight: 700; }
    .db-priority::before { content: ''; width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
    .db-priority.high { color: #ef4444; }
    .db-priority.high::before { background: #ef4444; }
    .db-priority.medium { color: #f59e0b; }
    .db-priority.medium::before { background: #f59e0b; }
    .db-priority.low { color: #10b981; }
    .db-priority.low::before { background: #10b981; }

    .btn-db-action { background: transparent; border: 1px solid rgba(255,255,255,0.06); border-radius: 6px; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; color: #6b7280; cursor: pointer; transition: all 0.2s; }
    .btn-db-action:hover { color: #FFD500; border-color: rgba(255,213,0,0.3); background: rgba(255,213,0,0.03); }
    .btn-db-action svg { width: 12px; height: 12px; }

    /* Loading overlay */
    .loading-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.9); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 9999; flex-direction: column; gap: 24px; }
    .loading-overlay.active { display: flex; }
    .loading-spinner { width: 64px; height: 64px; border: 3px solid rgba(255,213,0,0.2); border-top-color: #FFD500; border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-title { color: white; font-size: 20px; font-weight: 800; letter-spacing: 0.02em; }
    .loading-sub { color: #9ca3af; font-size: 14px; text-align: center; }
    .loading-steps { display: flex; flex-direction: column; gap: 8px; margin-top: 8px; }
    .loading-step { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #6b7280; transition: color 0.3s; }
    .loading-step.done { color: #10b981; }
    .loading-step svg { width: 14px; height: 14px; }
</style>

<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-title">Analyzing Infrastructure</div>
    <div class="loading-sub" id="loading-sub">Processing request data...</div>
</div>

<div class="maintenance-layout">
    <!-- Sidebar Left -->
    @include('partials.sidebar')

    <!-- Main Content Area -->
    <main class="main-area">
        <div class="main-scrollable">

            <!-- Title & Back -->
            <div class="page-header-wrap">
                <div class="header-left">
                    <h1>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Maintenance Request
                    </h1>
                    <p>Submit a maintenance request to the relevant authority and help improve road conditions.</p>
                </div>
                <a href="/dashboard" class="btn-back">
                    &larr; Back to Dashboard
                </a>
            </div>

            <!-- Top Row Metrics Box acting as buttons! -->
            <div class="metrics-row">
                <button type="button" id="mb-total" class="metric-btn-box" onclick="filterDatabase('all')">
                    <div class="metric-info">
                        <span class="metric-lbl">Total Requests</span>
                        <span class="metric-val" id="count-total">24</span>
                        <span class="metric-sub green">All time</span>
                    </div>
                    <div class="metric-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </button>

                <button type="button" id="mb-pending" class="metric-btn-box" onclick="filterDatabase('pending')">
                    <div class="metric-info">
                        <span class="metric-lbl">Pending Requests</span>
                        <span class="metric-val" id="count-pending">5</span>
                        <span class="metric-sub gold">Awaiting action</span>
                    </div>
                    <div class="metric-icon gold">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </button>

                <button type="button" id="mb-resolved" class="metric-btn-box" onclick="filterDatabase('resolved')">
                    <div class="metric-info">
                        <span class="metric-lbl">Resolved Requests</span>
                        <span class="metric-val" id="count-resolved">16</span>
                        <span class="metric-sub green">Successfully resolved</span>
                    </div>
                    <div class="metric-icon green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                </button>

                <button type="button" id="mb-critical" class="metric-btn-box" onclick="filterDatabase('critical')">
                    <div class="metric-info">
                        <span class="metric-lbl">Critical Requests</span>
                        <span class="metric-val" id="count-critical">3</span>
                        <span class="metric-sub red">High priority</span>
                    </div>
                    <div class="metric-icon red">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </button>
            </div>

            <!-- Left Form Column (Toggled in JS) -->
            <div id="request-form-card" class="card-wrap">
                <div class="card-title">
                    <span class="badge-num">1</span>
                    Request Details
                </div>
                
                <div class="field-group">
                    <label class="field-label">Request Title <span>*</span></label>
                    <input type="text" id="req-title" class="field-input" placeholder="e.g., Pothole on MG Road causing traffic issues" oninput="updateSummary()">
                </div>

                <div class="field-group">
                    <label class="field-label">Description <span>*</span></label>
                    <textarea id="req-desc" class="field-textarea" placeholder="Provide detailed information about the issue..."></textarea>
                </div>

                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Issue Type <span>*</span></label>
                        <select id="req-type" class="field-select" onchange="updateSummary()">
                            <option value="Pothole">Pothole</option>
                            <option value="Crack">Crack</option>
                            <option value="Rutting">Rutting</option>
                            <option value="Surface Wear">Surface Wear</option>
                            <option value="Bleeding">Bleeding</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Priority Level <span>*</span></label>
                        <select id="req-priority" class="field-select" onchange="updateSummary()">
                            <option value="High" selected>High</option>
                            <option value="Medium">Medium</option>
                            <option value="Low">Low</option>
                        </select>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Location <span>*</span></label>
                        <div style="display: flex; gap: 6px;">
                            <input type="text" id="req-location" class="field-input" value="MG Road, Downtown" placeholder="Enter location or pin on map" oninput="updateSummary()" style="flex-grow:1;">
                            <button type="button" id="btn-geocode" style="background:#0a0a0a; border:1px solid rgba(255,255,255,0.08); color:#9ca3af; border-radius:8px; padding:0 12px; cursor:pointer; font-size:12px; font-weight:700;">Find</button>
                        </div>
                        <input type="hidden" id="citizen-latitude" name="latitude">
                        <input type="hidden" id="citizen-longitude" name="longitude">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Upload Photo / Evidence <span>*</span></label>
                        <div class="upload-drag-box" id="citizen-drop-zone">
                            <input type="file" name="road_image" id="citizen-image-input" accept="image/*" style="display:none;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <span id="citizen-upload-text">Drag &amp; drop image here<br>or click to browse</span>
                        </div>
                        <div id="citizen-preview-box" style="display:none; flex-direction:column; gap:8px;">
                            <img id="citizen-preview-img" src="" style="width:100%; height:96px; object-fit:cover; border-radius:8px; border:1px solid rgba(255,255,255,0.1);" />
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span id="citizen-preview-name" style="font-size:11px; color:#9ca3af; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:180px;">image.jpg</span>
                                <button type="button" id="citizen-btn-clear" style="background:transparent; border:1px solid rgba(255,255,255,0.1); color:#ef4444; font-size:10px; font-weight:700; padding:2px 8px; border-radius:4px; cursor:pointer;">✕ Remove</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Detected in Report (Optional)</label>
                    <select class="field-select" id="req-report-select" onchange="fillFromReport(this.value)">
                        <option value="">Select analysis report</option>
                        @foreach($reportedComplaints as $complaint)
                            <option value="#{{ $complaint->scan_id }}">#{{ $complaint->scan_id }} ({{ $complaint->location }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="nav-divider" style="margin: 4px 0;"></div>

                <div class="card-title">
                    <span class="badge-num">2</span>
                    Authority Assignment
                </div>

                <div class="grid-2">
                    <div class="field-group">
                        <label class="field-label">Select Authority <span>*</span></label>
                        <select id="req-authority" class="field-select" onchange="updateAuthorities()">
                            <option value="bbmp">Bruhat Bengaluru Mahanagara Palike (BBMP)</option>
                            <option value="pwd">Karnataka PWD</option>
                            <option value="nhai">National Highway Authority</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Notify Additional Authorities</label>
                        <select id="req-notify" class="field-select" onchange="updateAuthorities()">
                            <option value="none">Select authorities (optional)</option>
                            <option value="pwd">Karnataka PWD</option>
                            <option value="police">Traffic Police Department</option>
                            <option value="kspcb">Pollution Control Board</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="btn-submit-request" onclick="submitRequest()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Submit Maintenance Request
                </button>
            </div>

            <!-- Left Database Listing Panel (Hidden by default, shown when metric clicked) -->
            <div id="request-db-panel" class="db-drawer-panel">
                <div class="db-header">
                    <div class="db-title-col">
                        <h2 id="db-title-text">All Maintenance Requests <span>24</span></h2>
                        <p id="db-subtitle-text">Review and monitor status workflows of reported road defects.</p>
                    </div>
                    <button type="button" class="btn-create-toggle" onclick="toggleFormView()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        New Request
                    </button>
                </div>

                <div class="db-table-box">
                    <table class="db-table">
                        <thead>
                            <tr>
                                <th>Request Title</th>
                                <th>Location</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Assigned Authority</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="db-table-body">
                            <!-- Populated in JS dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Middle Location Preview Column -->
            <div class="card-wrap">
                <div class="card-title">
                    <span class="badge-num">3</span>
                    Location Preview
                </div>

                <div class="map-container" style="position: relative;">
                    <div class="map-overlay-lbl" style="z-index: 1000;">
                        <div id="lbl-map-title">MG Road, Downtown</div>
                        <span id="lbl-map-coords">Lat: 12.9716° N, Long: 77.5946° E</span>
                    </div>
                    
                    <!-- Map zoom icons -->
                    <div class="map-zoom-tools" style="z-index: 1000;">
                        <button type="button" class="zoom-tool zoom-tool-plus">+</button>
                        <button type="button" class="zoom-tool zoom-tool-minus">-</button>
                    </div>

                    <!-- Leaflet Map Container -->
                    <div id="citizen-map" style="width:100%; height:100%; position:absolute; inset:0; z-index:1;"></div>
                </div>

                <div class="loc-details-wrap">
                    <div class="loc-title">Location Details</div>
                    <div class="loc-grid">
                        <div class="loc-entry">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div class="loc-entry-details">
                                <span class="loc-lbl">Area</span>
                                <span class="loc-val" id="det-area">MG Road, Downtown</span>
                            </div>
                        </div>
                        <div class="loc-entry">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <div class="loc-entry-details">
                                <span class="loc-lbl">City</span>
                                <span class="loc-val">Bengaluru, Karnataka</span>
                            </div>
                        </div>
                        <div class="loc-entry">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            <div class="loc-entry-details">
                                <span class="loc-lbl">Coordinates</span>
                                <span class="loc-val">12.9716° N, 77.5946° E</span>
                            </div>
                        </div>
                        <div class="loc-entry">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="loc-entry-details">
                                <span class="loc-lbl">Nearby Landmark</span>
                                <span class="loc-val">Trinity Circle</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Widget Sidebar -->
            <div class="sidebar-right">
                
                <!-- Request Summary -->
                <div class="panel">
                    <span class="panel-title">Request Summary</span>
                    <div class="summary-list">
                        <!-- Evidence Photo -->
                        <div class="summary-item" id="selected-req-image-wrap" style="display:none; flex-direction:column; gap:8px; border-bottom:1px solid rgba(255,255,255,0.03); padding-bottom:10px;">
                            <span class="lbl" style="align-self:flex-start;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Evidence Photo
                            </span>
                            <img id="selected-req-image" src="" style="width:100%; height:120px; object-fit:cover; border-radius:8px; border:1px solid rgba(255,255,255,0.1);" />
                        </div>
                        
                        <!-- PCI Score & Condition -->
                        <div class="summary-item" id="selected-req-pci-wrap" style="display:none;">
                            <span class="lbl">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                PCI Score / Condition
                            </span>
                            <span class="val" id="selected-req-pci" style="font-weight:800;"></span>
                        </div>

                        <div class="summary-item">
                            <span class="lbl">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Condition
                            </span>
                            <span class="val" id="sum-type">Pothole</span>
                        </div>
                        <div class="summary-item">
                            <span class="lbl">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Severity
                            </span>
                            <span class="val red-pill" id="sum-priority">High</span>
                        </div>
                        <div class="summary-item">
                            <span class="lbl">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Location
                            </span>
                            <span class="val" id="sum-location">MG Road, Downtown</span>
                        </div>
                        <div class="summary-item">
                            <span class="lbl">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Estimated Impact
                            </span>
                            <span class="val" style="color:#ef4444;" id="sum-impact">High</span>
                        </div>
                        <div class="summary-item">
                            <span class="lbl">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Reported On
                            </span>
                            <span class="val" id="sum-date">20 May 2026, 11:30 AM</span>
                        </div>
                    </div>
                </div>

                <!-- Request Status Flow -->
                <div class="panel">
                    <span class="panel-title">Request Status Flow</span>
                    <div class="status-timeline">
                        <div class="status-timeline-line"></div>
                        
                        <div class="status-node active">
                            <div class="status-dot"></div>
                            <div class="status-details">
                                <span class="status-desc">Request Submitted</span>
                                <span class="status-time">20 May 2026, 11:30 AM</span>
                            </div>
                        </div>

                        <div class="status-node pending-node">
                            <div class="status-dot"></div>
                            <div class="status-details">
                                <span class="status-desc">Under Review</span>
                                <span class="status-time">Pending</span>
                            </div>
                        </div>

                        <div class="status-node pending-node">
                            <div class="status-dot"></div>
                            <div class="status-details">
                                <span class="status-desc">In Progress</span>
                                <span class="status-time">Pending</span>
                            </div>
                        </div>

                        <div class="status-node pending-node">
                            <div class="status-dot"></div>
                            <div class="status-details">
                                <span class="status-desc">Resolved</span>
                                <span class="status-time">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Who Will Receive This? -->
                <div class="panel">
                    <span class="panel-title">Who Will Receive This?</span>
                    <div class="auth-list">
                        <!-- Target 1 -->
                        <div class="auth-badge" id="auth-bbmp-badge">
                            <div class="auth-icon orange">BB</div>
                            <div class="auth-meta">
                                <span class="auth-name" style="font-size:10px;">Bruhat Bengaluru Mahanagara Palike</span>
                                <span class="auth-type primary">Primary Authority</span>
                            </div>
                        </div>

                        <!-- Target 2 -->
                        <div class="auth-badge" id="auth-pwd-badge" style="display:none;">
                            <div class="auth-icon orange">PW</div>
                            <div class="auth-meta">
                                <span class="auth-name">Karnataka PWD</span>
                                <span class="auth-type primary">Primary Authority</span>
                            </div>
                        </div>

                        <!-- Target 3 -->
                        <div class="auth-badge" id="auth-nhai-badge" style="display:none;">
                            <div class="auth-icon orange">NH</div>
                            <div class="auth-meta">
                                <span class="auth-name">National Highway Authority</span>
                                <span class="auth-type primary">Primary Authority</span>
                            </div>
                        </div>

                        <!-- Notify PWD -->
                        <div class="auth-badge" id="notify-pwd-badge" style="display:none;">
                            <div class="auth-icon blue">PW</div>
                            <div class="auth-meta">
                                <span class="auth-name">Karnataka PWD</span>
                                <span class="auth-type notified">Notified</span>
                            </div>
                        </div>

                        <!-- Notify Police -->
                        <div class="auth-badge" id="notify-police-badge" style="display:none;">
                            <div class="auth-icon blue">TP</div>
                            <div class="auth-meta">
                                <span class="auth-name">Traffic Police Department</span>
                                <span class="auth-type notified">Notified</span>
                            </div>
                        </div>

                        <!-- Notify KSPCB -->
                        <div class="auth-badge" id="notify-kspcb-badge" style="display:none;">
                            <div class="auth-icon blue">PC</div>
                            <div class="auth-meta">
                                <span class="auth-name">Pollution Control Board</span>
                                <span class="auth-type notified">Notified</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Flow: "What happens next?" -->
            <div class="bottom-flow-wrap">
                <h3>What happens next?</h3>
                <div class="flow-steps">
                    <div class="flow-step-item active">
                        <div class="flow-step-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </div>
                        <span class="flow-step-lbl">1. Submit</span>
                        <span class="flow-step-sub">Request sent to relevant authority.</span>
                    </div>

                    <div class="flow-step-item">
                        <div class="flow-step-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <span class="flow-step-lbl">2. Review</span>
                        <span class="flow-step-sub">Officials inspect and validate.</span>
                    </div>

                    <div class="flow-step-item">
                        <div class="flow-step-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="flow-step-lbl">3. Action</span>
                        <span class="flow-step-sub">Maintenance team fills potholes.</span>
                    </div>

                    <div class="flow-step-item">
                        <div class="flow-step-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="flow-step-lbl">4. Resolved</span>
                        <span class="flow-step-sub">Pavement restored to perfect.</span>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-title">Analyzing Road Image...</div>
    <div class="loading-sub">AI model is processing your upload</div>
    <div class="loading-steps">
        <div class="loading-step" id="step-upload">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Uploading image to server...
        </div>
        <div class="loading-step" id="step-ai">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Running AI defect detection...
        </div>
        <div class="loading-step" id="step-save">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Saving report to database...
        </div>
    </div>
</div>

<!-- Custom Error Modal -->
<div class="custom-modal-overlay" id="error-modal">
    <div class="custom-modal-content">
        <div class="modal-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 id="error-modal-title">Action Required</h3>
        <p id="error-modal-message"></p>
        <button class="modal-btn-confirm" onclick="document.getElementById('error-modal').classList.remove('active')">Understood</button>
    </div>
</div>

<!-- Leaflet Library -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Escape HTML helper
    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Initialize JavaScript requestsDb dynamically from the Eloquent collection
    let requestsDb = [
        @foreach($reportedComplaints as $complaint)
        {
            id: '#{{ $complaint->scan_id }}',
            dbId: {{ $complaint->id }},
            title: '{{ addslashes($complaint->location) }} defect',
            location: '{{ addslashes($complaint->location) }}',
            latitude: {{ $complaint->latitude ?? 'null' }},
            longitude: {{ $complaint->longitude ?? 'null' }},
            condition: '{{ $complaint->condition }}',
            pci_score: {{ $complaint->pci_score }},
            severity: '{{ $complaint->severity }}',
            priority: '{{ ucfirst($complaint->severity) }}',
            image_path: '{{ $complaint->image_path }}',
            image_url: '{{ asset("storage/" . $complaint->image_path) }}',
            date: '{{ $complaint->created_at->format("d M Y") }}',
            time: '{{ $complaint->created_at->format("h:i A") }}',
            full_date: '{{ $complaint->created_at->format("d M Y, h:i A") }}',
            status: '{{ $complaint->maintenanceTask ? ($complaint->maintenanceTask->status === "approved" ? "resolved" : "pending") : "pending" }}',
            task_status: '{{ $complaint->maintenanceTask->status ?? "unassigned" }}',
            authority: '{{ $complaint->maintenanceTask && $complaint->maintenanceTask->assignedBy ? addslashes($complaint->maintenanceTask->assignedBy->department) : "Awaiting Review" }}',
            activities: [
                @if($complaint->maintenanceTask)
                    @foreach($complaint->maintenanceTask->taskActivities as $act)
                    {
                        action: '{{ $act->action }}',
                        description: '{{ addslashes($act->description) }}',
                        image_path: '{{ $act->image_path }}',
                        created_at: '{{ $act->created_at->format("d M Y, h:i A") }}'
                    },
                    @endforeach
                @endif
            ]
        },
        @endforeach
    ];

    // Map initialization
    var map = L.map('citizen-map', {
        zoomControl: false
    }).setView([12.9716, 77.5946], 13);
    
    var activePinMarker = null;
    var markersGroup = L.layerGroup().addTo(map);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Zoom bindings
    document.querySelector('.zoom-tool-plus').addEventListener('click', () => map.zoomIn());
    document.querySelector('.zoom-tool-minus').addEventListener('click', () => map.zoomOut());

    // Geolocate user to center map
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            map.setView([position.coords.latitude, position.coords.longitude], 13);
        });
    }

    // Map interactive click pinning
    map.on('click', function(e) {
        // Only allow clicking to pin coordinates when request-form-card is visible
        if (document.getElementById('request-form-card').style.display === 'none') {
            return;
        }

        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        document.getElementById('citizen-latitude').value = lat;
        document.getElementById('citizen-longitude').value = lng;
        
        updateActivePin(lat, lng);

        // Fetch reverse geocoding via Nominatim
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name) {
                    var addressParts = data.display_name.split(',');
                    var name = addressParts[0] + (addressParts[1] ? ', ' + addressParts[1].trim() : '');
                    
                    document.getElementById('req-location').value = name;
                    document.getElementById('lbl-map-title').textContent = name;
                    document.getElementById('lbl-map-coords').textContent = `Lat: ${lat.toFixed(4)}° N, Long: ${lng.toFixed(4)}° E`;
                    document.getElementById('det-area').textContent = name;
                    
                    updateSummary();
                }
            })
            .catch(err => {
                console.error("Nominatim reverse geocode error:", err);
                var name = lat.toFixed(4) + ", " + lng.toFixed(4);
                document.getElementById('req-location').value = name;
                document.getElementById('lbl-map-title').textContent = name;
                document.getElementById('lbl-map-coords').textContent = `Lat: ${lat.toFixed(4)}° N, Long: ${lng.toFixed(4)}° E`;
                document.getElementById('det-area').textContent = name;
                updateSummary();
            });
    });

    // Add glowing pin for coordinates
    function updateActivePin(lat, lng) {
        if (activePinMarker) {
            map.removeLayer(activePinMarker);
        }
        let pinHtml = `<div class="map-marker-pin" style="transform: translate(-9px, -9px);"></div>`;
        let pinIcon = L.divIcon({
            html: pinHtml,
            className: 'active-pin-layer',
            iconSize: [18, 18],
            iconAnchor: [9, 9]
        });
        activePinMarker = L.marker([lat, lng], { icon: pinIcon }).addTo(map);
    }

    // Render markers of existing reports
    function renderMarkers() {
        markersGroup.clearLayers();
        requestsDb.forEach(r => {
            if (r.latitude && r.longitude) {
                let markerColor = r.status === 'resolved' ? '#10b981' : (r.priority === 'High' ? '#ef4444' : '#f59e0b');
                let markerHtml = `<div style="background-color: ${markerColor}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 8px rgba(0,0,0,0.5);"></div>`;
                let customIcon = L.divIcon({
                    html: markerHtml,
                    className: 'custom-map-marker',
                    iconSize: [12, 12],
                    iconAnchor: [6, 6]
                });
                
                L.marker([r.latitude, r.longitude], { icon: customIcon })
                    .bindPopup(`<strong>${escapeHtml(r.location)}</strong><br>Status: ${r.status.toUpperCase()}<br><button onclick="selectRequestById('${r.id}')" style="margin-top:6px; background:#FFD500; color:black; border:none; padding:3px 8px; border-radius:4px; font-size:10px; cursor:pointer; font-weight:bold;">View Summary</button>`)
                    .addTo(markersGroup);
            }
        });
    }

    // Bind Search logic
    document.getElementById('btn-geocode').addEventListener('click', searchAddress);
    document.getElementById('req-location').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchAddress();
        }
    });

    function searchAddress() {
        var query = document.getElementById('req-location').value.trim();
        if (!query) return;

        var btn = document.getElementById('btn-geocode');
        var originalText = btn.textContent;
        btn.textContent = "...";

        fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                btn.textContent = originalText;
                if (data && data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lon = parseFloat(data[0].lon);
                    var name = data[0].display_name.split(',')[0] + ', ' + (data[0].display_name.split(',')[1] || '').trim();
                    
                    map.flyTo([lat, lon], 14);

                    document.getElementById('citizen-latitude').value = lat;
                    document.getElementById('citizen-longitude').value = lon;
                    document.getElementById('req-location').value = name;
                    document.getElementById('lbl-map-title').textContent = name;
                    document.getElementById('lbl-map-coords').textContent = `Lat: ${lat.toFixed(4)}° N, Long: ${lon.toFixed(4)}° E`;
                    document.getElementById('det-area').textContent = name;

                    updateActivePin(lat, lon);
                    updateSummary();
                } else {
                    showCustomAlert("Not Found", "Location not found. Please clarify address.");
                }
            })
            .catch(err => {
                btn.textContent = originalText;
                console.error("Geocoding search error:", err);
                showCustomAlert("Error", "Network error occurred during address search.");
            });
    }

    // Drag-and-Drop + Preview Upload Experience
    const citizenDropZone = document.getElementById('citizen-drop-zone');
    const citizenFileInput = document.getElementById('citizen-image-input');
    const citizenPreviewBox = document.getElementById('citizen-preview-box');
    const citizenPreviewImg = document.getElementById('citizen-preview-img');
    const citizenPreviewName = document.getElementById('citizen-preview-name');
    const citizenBtnClear = document.getElementById('citizen-btn-clear');

    citizenDropZone.addEventListener('click', () => {
        citizenFileInput.click();
    });

    citizenFileInput.addEventListener('change', () => {
        showCitizenPreview(citizenFileInput.files[0]);
    });

    citizenDropZone.addEventListener('dragover', e => {
        e.preventDefault();
        citizenDropZone.style.borderColor = '#FFD500';
        citizenDropZone.style.backgroundColor = 'rgba(255,213,0,0.02)';
    });

    citizenDropZone.addEventListener('dragleave', () => {
        citizenDropZone.style.borderColor = 'rgba(255,255,255,0.08)';
        citizenDropZone.style.backgroundColor = '#050505';
    });

    citizenDropZone.addEventListener('drop', e => {
        e.preventDefault();
        citizenDropZone.style.borderColor = 'rgba(255,255,255,0.08)';
        citizenDropZone.style.backgroundColor = '#050505';
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            const dt = new DataTransfer();
            dt.items.add(file);
            citizenFileInput.files = dt.files;
            showCitizenPreview(file);
        }
    });

    function showCitizenPreview(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            citizenPreviewImg.src = e.target.result;
            citizenPreviewName.textContent = `${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
            citizenDropZone.style.display = 'none';
            citizenPreviewBox.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }

    citizenBtnClear.addEventListener('click', e => {
        e.stopPropagation();
        citizenFileInput.value = '';
        citizenPreviewBox.style.display = 'none';
        citizenDropZone.style.display = 'flex';
    });

    // Custom alerts
    function showCustomAlert(title, message) {
        document.getElementById('error-modal-title').innerText = title;
        document.getElementById('error-modal-message').innerHTML = message;
        document.getElementById('error-modal').classList.add('active');
    }

    // Pre-fill fields from report selection dropdown
    function fillFromReport(scanId) {
        if (!scanId) {
            document.getElementById('req-title').value = '';
            document.getElementById('req-desc').value = '';
            document.getElementById('req-location').value = '';
            document.getElementById('citizen-latitude').value = '';
            document.getElementById('citizen-longitude').value = '';
            
            citizenFileInput.value = '';
            citizenPreviewBox.style.display = 'none';
            citizenDropZone.style.display = 'flex';
            
            if (activePinMarker) {
                map.removeLayer(activePinMarker);
                activePinMarker = null;
            }
            updateSummary();
            return;
        }

        const r = requestsDb.find(item => item.id === scanId);
        if (!r) return;

        document.getElementById('req-title').value = `Maintenance request for defect at ${r.location}`;
        document.getElementById('req-desc').value = `Identified road distress (${r.condition}) with a PCI score of ${r.pci_score}. Severity: ${r.severity}. Recommended Action: ${r.recommended_action || 'Repair needed.'}`;
        document.getElementById('req-location').value = r.location;
        document.getElementById('citizen-latitude').value = r.latitude || '';
        document.getElementById('citizen-longitude').value = r.longitude || '';

        if (document.getElementById('req-type')) {
            document.getElementById('req-type').value = r.condition || 'Pothole';
        }
        if (document.getElementById('req-priority')) {
            document.getElementById('req-priority').value = r.priority || 'Medium';
        }

        if (r.image_url) {
            citizenPreviewImg.src = r.image_url;
            citizenPreviewName.textContent = `Report image pinned: ${r.id}`;
            citizenDropZone.style.display = 'none';
            citizenPreviewBox.style.display = 'flex';
        }

        if (r.latitude && r.longitude) {
            map.flyTo([r.latitude, r.longitude], 15);
            updateActivePin(r.latitude, r.longitude);
        }

        updateSummary();
    }

    // Initialize metrics count inside cards
    function calculateMetrics() {
        const total = requestsDb.length;
        const pending = requestsDb.filter(r => r.status === 'pending').length;
        const resolved = requestsDb.filter(r => r.status === 'resolved').length;
        const critical = requestsDb.filter(r => r.status === 'critical' || (r.priority === 'High' && r.status === 'pending')).length;

        document.getElementById('count-total').textContent = total;
        document.getElementById('count-pending').textContent = pending;
        document.getElementById('count-resolved').textContent = resolved;
        document.getElementById('count-critical').textContent = critical;
    }

    // Toggle back to create request form
    function toggleFormView() {
        document.getElementById('request-db-panel').style.display = 'none';
        document.getElementById('request-form-card').style.display = 'flex';
        document.querySelectorAll('.metric-btn-box').forEach(btn => btn.classList.remove('active'));
    }

    // Filter database rows
    function filterDatabase(filterType) {
        document.getElementById('request-form-card').style.display = 'none';
        document.getElementById('request-db-panel').style.display = 'flex';

        document.querySelectorAll('.metric-btn-box').forEach(btn => btn.classList.remove('active'));
        if (filterType === 'all') document.getElementById('mb-total').classList.add('active');
        if (filterType === 'pending') document.getElementById('mb-pending').classList.add('active');
        if (filterType === 'resolved') document.getElementById('mb-resolved').classList.add('active');
        if (filterType === 'critical') document.getElementById('mb-critical').classList.add('active');

        const titleText = document.getElementById('db-title-text');
        const subtitleText = document.getElementById('db-subtitle-text');
        
        let filtered = [];
        if (filterType === 'all') {
            filtered = requestsDb;
            titleText.innerHTML = `All Maintenance Requests <span>${filtered.length}</span>`;
            subtitleText.textContent = "Review and monitor status workflows of reported road defects.";
        } else if (filterType === 'pending') {
            filtered = requestsDb.filter(r => r.status === 'pending');
            titleText.innerHTML = `Pending Requests <span>${filtered.length}</span>`;
            subtitleText.textContent = "Inspection requests currently awaiting review or maintenance scheduling.";
        } else if (filterType === 'resolved') {
            filtered = requestsDb.filter(r => r.status === 'resolved');
            titleText.innerHTML = `Resolved Requests <span>${filtered.length}</span>`;
            subtitleText.textContent = "Completed restoration tasks successfully verified by local authorities.";
        } else if (filterType === 'critical') {
            filtered = requestsDb.filter(r => r.status === 'critical' || (r.priority === 'High' && r.status === 'pending'));
            titleText.innerHTML = `Critical &amp; High Priority Requests <span>${filtered.length}</span>`;
            subtitleText.textContent = "High-priority pavement hazards requiring immediate team dispatchment.";
        }

        const tbody = document.getElementById('db-table-body');
        tbody.innerHTML = '';

        if (filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:32px;color:#4b5563;">No requests found in this category.</td></tr>`;
            return;
        }

        filtered.forEach(r => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="title-cell">${escapeHtml(r.location)} defect <span>ID: ${r.id}</span></td>
                <td>${escapeHtml(r.location)}</td>
                <td><span class="db-priority ${r.priority.toLowerCase()}">${r.priority}</span></td>
                <td><span class="db-badge ${r.status}">${r.status}</span></td>
                <td>${escapeHtml(r.authority)}</td>
                <td style="color:#6b7280;font-size:11px;">${r.date}</td>
                <td>
                    <button class="btn-db-action" onclick="selectRequestById('${r.id}')" title="Inspect Status Logs">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    // Real-time Sidebar Summary Updating from Form
    function updateSummary() {
        const type = document.getElementById('req-type').value;
        const priority = document.getElementById('req-priority').value;
        const location = document.getElementById('req-location').value.trim() || 'MG Road, Downtown';
        
        document.getElementById('sum-type').textContent = type;
        
        const priorityEl = document.getElementById('sum-priority');
        priorityEl.textContent = priority;
        priorityEl.className = 'val';
        if (priority === 'High') {
            priorityEl.classList.add('red-pill');
            document.getElementById('sum-impact').textContent = 'High';
            document.getElementById('sum-impact').style.color = '#ef4444';
        } else if (priority === 'Medium') {
            priorityEl.style.color = '#f59e0b';
            document.getElementById('sum-impact').textContent = 'Moderate';
            document.getElementById('sum-impact').style.color = '#f59e0b';
        } else {
            priorityEl.style.color = '#10b981';
            document.getElementById('sum-impact').textContent = 'Low';
            document.getElementById('sum-impact').style.color = '#10b981';
        }

        document.getElementById('sum-location').textContent = location;
    }

    // Toggle authority display badges based on choice
    function updateAuthorities() {
        const primary = document.getElementById('req-authority').value;
        const notify = document.getElementById('req-notify').value;

        document.getElementById('auth-bbmp-badge').style.display = 'none';
        document.getElementById('auth-pwd-badge').style.display = 'none';
        document.getElementById('auth-nhai-badge').style.display = 'none';

        if (primary === 'bbmp') document.getElementById('auth-bbmp-badge').style.display = 'flex';
        if (primary === 'pwd') document.getElementById('auth-pwd-badge').style.display = 'flex';
        if (primary === 'nhai') document.getElementById('auth-nhai-badge').style.display = 'flex';

        document.getElementById('notify-pwd-badge').style.display = 'none';
        document.getElementById('notify-police-badge').style.display = 'none';
        document.getElementById('notify-kspcb-badge').style.display = 'none';

        if (notify === 'pwd') document.getElementById('notify-pwd-badge').style.display = 'flex';
        if (notify === 'police') document.getElementById('notify-police-badge').style.display = 'flex';
        if (notify === 'kspcb') document.getElementById('notify-kspcb-badge').style.display = 'flex';
    }

    // Select Request Details & Update UI
    function selectRequestById(id) {
        const r = requestsDb.find(item => item.id === id);
        if (!r) return;

        // Update summary type with condition, or fallback
        document.getElementById('sum-type').textContent = r.condition ? (r.condition.charAt(0).toUpperCase() + r.condition.slice(1)) : 'Pothole';
        
        const priorityEl = document.getElementById('sum-priority');
        priorityEl.textContent = r.priority || 'Medium';
        priorityEl.className = 'val';
        if (r.priority === 'High') {
            priorityEl.classList.add('red-pill');
            document.getElementById('sum-impact').textContent = 'High';
            document.getElementById('sum-impact').style.color = '#ef4444';
        } else if (r.priority === 'Medium') {
            priorityEl.style.color = '#f59e0b';
            document.getElementById('sum-impact').textContent = 'Moderate';
            document.getElementById('sum-impact').style.color = '#f59e0b';
        } else {
            priorityEl.style.color = '#10b981';
            document.getElementById('sum-impact').textContent = 'Low';
            document.getElementById('sum-impact').style.color = '#10b981';
        }

        document.getElementById('sum-location').textContent = r.location;
        document.getElementById('sum-date').textContent = r.full_date || (r.date + ', ' + r.time);

        // Evidence photo wrap updating
        const imgWrap = document.getElementById('selected-req-image-wrap');
        const imgEl = document.getElementById('selected-req-image');
        if (r.image_url && r.image_path) {
            imgEl.src = r.image_url;
            imgWrap.style.display = 'flex';
        } else {
            imgWrap.style.display = 'none';
        }

        // PCI Score widget updating
        const pciWrap = document.getElementById('selected-req-pci-wrap');
        const pciEl = document.getElementById('selected-req-pci');
        if (r.pci_score !== undefined && r.pci_score !== null) {
            pciEl.textContent = `${r.pci_score} / 100 (${r.condition})`;
            if (r.pci_score >= 80) pciEl.style.color = '#10b981';
            else if (r.pci_score >= 55) pciEl.style.color = '#f59e0b';
            else pciEl.style.color = '#ef4444';
            pciWrap.style.display = 'flex';
        } else {
            pciWrap.style.display = 'none';
        }

        // Map label & coords
        document.getElementById('lbl-map-title').textContent = r.location;
        document.getElementById('lbl-map-coords').textContent = `Lat: ${r.latitude ? r.latitude.toFixed(4) : 'N/A'}° N, Long: ${r.longitude ? r.longitude.toFixed(4) : 'N/A'}° E`;
        document.getElementById('det-area').textContent = r.location;
        document.querySelector('.loc-grid .loc-entry:nth-child(3) .loc-val').textContent = `${r.latitude ? r.latitude.toFixed(4) : 'N/A'}° N, ${r.longitude ? r.longitude.toFixed(4) : 'N/A'}° E`;

        if (r.latitude && r.longitude) {
            map.flyTo([r.latitude, r.longitude], 15);
            updateActivePin(r.latitude, r.longitude);
        }

        // Dynamic status workflow timeline logs rendering
        renderTimeline(r);

        // Primary authority badge toggles
        const primaryBadgeBBMP = document.getElementById('auth-bbmp-badge');
        const primaryBadgePWD = document.getElementById('auth-pwd-badge');
        const primaryBadgeNHAI = document.getElementById('auth-nhai-badge');
        
        primaryBadgeBBMP.style.display = 'none';
        primaryBadgePWD.style.display = 'none';
        primaryBadgeNHAI.style.display = 'none';

        if (r.authority && r.authority.toLowerCase().includes('pwd')) {
            primaryBadgePWD.style.display = 'flex';
        } else if (r.authority && r.authority.toLowerCase().includes('highway')) {
            primaryBadgeNHAI.style.display = 'flex';
        } else {
            primaryBadgeBBMP.style.display = 'flex';
        }
    }

    // Dynamic timeline timeline rendering
    function renderTimeline(r) {
        const timelineContainer = document.querySelector('.status-timeline');
        if (!timelineContainer) return;

        timelineContainer.innerHTML = '<div class="status-timeline-line"></div>';

        // Creation / Submission Node
        let submissionNode = document.createElement('div');
        submissionNode.className = 'status-node active';
        submissionNode.innerHTML = `
            <div class="status-dot"></div>
            <div class="status-details">
                <span class="status-desc">Request Submitted</span>
                <span class="status-time" style="display:block;">${r.full_date}</span>
            </div>
        `;
        timelineContainer.appendChild(submissionNode);

        if (r.activities && r.activities.length > 0) {
            r.activities.forEach(act => {
                let actNode = document.createElement('div');
                actNode.className = 'status-node active';
                
                let actionTitle = act.action.toUpperCase();
                let activityDotColor = '#FFD500';
                let progressImageHtml = '';
                
                if (act.action === 'assigned') actionTitle = 'Officer Assigned Crew';
                else if (act.action === 'started') { actionTitle = 'Crew Dispatched & Started'; activityDotColor = '#10b981'; }
                else if (act.action === 'paused') { actionTitle = 'Repairs Suspended'; activityDotColor = '#f59e0b'; }
                else if (act.action === 'completed') { actionTitle = 'Repairs Finished'; activityDotColor = '#3b82f6'; }
                else if (act.action === 'approved') { actionTitle = 'Closed & Resolved'; activityDotColor = '#10b981'; }
                else if (act.action === 'correction') { actionTitle = 'Sent Back for Correction'; activityDotColor = '#ef4444'; }
                else if (act.action === 'progress_update') {
                    actionTitle = 'Work Progress Update';
                    activityDotColor = '#3b82f6';
                    if (act.image_path) {
                        progressImageHtml = `<img src="/storage/${act.image_path}" style="max-width: 100%; height: auto; max-height: 120px; object-fit: cover; border-radius: 6px; margin-top: 6px; border: 1px solid rgba(255,255,255,0.1);">`;
                    }
                }

                actNode.innerHTML = `
                    <div class="status-dot" style="background:${activityDotColor}; box-shadow:0 0 8px ${activityDotColor}80;"></div>
                    <div class="status-details">
                        <span class="status-desc" style="color:white; font-weight:700;">${actionTitle}</span>
                        <span class="status-desc" style="font-size:11px; color:#9ca3af; font-weight:normal; margin-top:2px;">${act.description}</span>
                        ${progressImageHtml}
                        <span class="status-time" style="display:block; margin-top:2px;">${act.created_at}</span>
                    </div>
                `;
                timelineContainer.appendChild(actNode);
            });

            // If not verified & closed yet, add pending verified node
            let lastActivity = r.activities[r.activities.length - 1];
            if (lastActivity.action !== 'approved') {
                let pendingNode = document.createElement('div');
                pendingNode.className = 'status-node pending-node';
                pendingNode.innerHTML = `
                    <div class="status-dot" style="background:rgba(255,255,255,0.1);"></div>
                    <div class="status-details">
                        <span class="status-desc" style="color:#4b5563;">Resolution Approved & Verified</span>
                        <span class="status-time">Pending</span>
                    </div>
                `;
                timelineContainer.appendChild(pendingNode);
            }
        } else {
            // Default pending statuses
            let reviewNode = document.createElement('div');
            reviewNode.className = 'status-node pending-node';
            reviewNode.innerHTML = `
                <div class="status-dot"></div>
                <div class="status-details">
                    <span class="status-desc">Awaiting Officer Assignment</span>
                    <span class="status-time">Pending</span>
                </div>
            `;
            timelineContainer.appendChild(reviewNode);

            let progressNode = document.createElement('div');
            progressNode.className = 'status-node pending-node';
            progressNode.innerHTML = `
                <div class="status-dot"></div>
                <div class="status-details">
                    <span class="status-desc">Field Repairs In Progress</span>
                    <span class="status-time">Pending</span>
                </div>
            `;
            timelineContainer.appendChild(progressNode);

            let resolvedNode = document.createElement('div');
            resolvedNode.className = 'status-node pending-node';
            resolvedNode.innerHTML = `
                <div class="status-dot"></div>
                <div class="status-details">
                    <span class="status-desc">Resolution Approved & Verified</span>
                    <span class="status-time">Pending</span>
                </div>
            `;
            timelineContainer.appendChild(resolvedNode);
        }
    }

    // Submit Request Action
    function submitRequest() {
        const title = document.getElementById('req-title').value.trim();
        const desc = document.getElementById('req-desc').value.trim();
        const location = document.getElementById('req-location').value.trim();
        const latitude = document.getElementById('citizen-latitude').value;
        const longitude = document.getElementById('citizen-longitude').value;
        const file = citizenFileInput.files[0];

        if (!location) {
            showCustomAlert("Input Error", "Please provide a location name or pin a coordinate on the map.");
            return;
        }

        if (!file) {
            showCustomAlert("Input Error", "Please upload a photo of the road defect for YOLO AI analysis.");
            return;
        }

        // Show loading screen & reset step statuses
        const loadingOverlay = document.getElementById('loading-overlay');
        loadingOverlay.classList.add('active');
        document.getElementById('step-upload').classList.remove('done');
        document.getElementById('step-ai').classList.remove('done');
        document.getElementById('step-save').classList.remove('done');

        // Step 1: Uploading
        setTimeout(() => document.getElementById('step-upload').classList.add('done'), 600);

        // Prepare FormData API payload
        const formData = new FormData();
        formData.append('road_image', file);
        formData.append('location', location);
        if (latitude) formData.append('latitude', latitude);
        if (longitude) formData.append('longitude', longitude);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('/analyze?json=1', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(err => { throw new Error(err.error || 'Server error'); });
            }
            return res.json();
        })
        .then(data => {
            // Step 2: AI completed
            document.getElementById('step-ai').classList.add('done');
            
            setTimeout(() => {
                // Step 3: Saved report
                document.getElementById('step-save').classList.add('done');
                
                setTimeout(() => {
                    loadingOverlay.classList.remove('active');

                    // Prepend new request object into local client db
                    const newReq = {
                        id: '#' + data.scan_id,
                        dbId: data.id,
                        title: data.location + ' defect',
                        location: data.location,
                        latitude: data.latitude ? parseFloat(data.latitude) : null,
                        longitude: data.longitude ? parseFloat(data.longitude) : null,
                        condition: data.condition,
                        pci_score: data.pci_score,
                        severity: data.severity,
                        priority: data.severity ? data.severity.charAt(0).toUpperCase() + data.severity.slice(1) : 'Medium',
                        image_path: data.image_path,
                        image_url: '/storage/' + data.image_path,
                        date: new Date(data.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }),
                        time: new Date(data.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
                        full_date: new Date(data.created_at).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }),
                        status: 'pending',
                        task_status: 'unassigned',
                        authority: 'Awaiting Review',
                        activities: []
                    };

                    requestsDb.unshift(newReq);
                    
                    // Update stats counts & render map markers
                    calculateMetrics();
                    renderMarkers();

                    // Reset form inputs & file upload controls
                    document.getElementById('req-title').value = '';
                    document.getElementById('req-desc').value = '';
                    citizenFileInput.value = '';
                    citizenPreviewBox.style.display = 'none';
                    citizenDropZone.style.display = 'flex';
                    document.getElementById('citizen-latitude').value = '';
                    document.getElementById('citizen-longitude').value = '';
                    
                    if (activePinMarker) {
                        map.removeLayer(activePinMarker);
                        activePinMarker = null;
                    }

                    // Switch view to db list and select new report
                    filterDatabase('all');
                    selectRequestById(newReq.id);

                }, 800);
            }, 1000);
        })
        .catch(err => {
            loadingOverlay.classList.remove('active');
            showCustomAlert("Analysis Failed", err.message || "Could not analyze image. Make sure the FastAPI Python service is running.");
        });
    }

    // Seed initial metrics and display defaults on window load
    window.addEventListener('DOMContentLoaded', () => {
        calculateMetrics();
        updateAuthorities();
        renderMarkers();
        
        // Select first complaint if present
        if (requestsDb.length > 0) {
            selectRequestById(requestsDb[0].id);
        }
    });
</script>
@endsection
