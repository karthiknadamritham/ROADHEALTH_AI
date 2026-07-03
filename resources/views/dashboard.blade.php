@php
    $hideFooter = true;
@endphp

@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    body { background-color: #050505; }
    
    .dashboard-layout { display: flex; height: calc(100vh - 73px); overflow: hidden; }
    

    
    /* Sidebar */
    .sidebar { width: 220px; min-width: 220px; background: #0a0a0a; border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; flex-shrink: 0; overflow: hidden; }

    .sidebar-nav { padding: 12px 10px; flex-grow: 1; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; position: relative; }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.12); color: #FFD500; font-weight: 700; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-badge { margin-left: auto; background: #ef4444; color: white; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 10px; }
    .nav-badge.ai { background: #FFD500; color: black; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 10px; }

    .sidebar-premium { margin: 10px; background: linear-gradient(135deg, rgba(255,213,0,0.12) 0%, rgba(255,213,0,0.02) 100%); border: 1px solid rgba(255,213,0,0.2); border-radius: 12px; padding: 14px; }
    .sidebar-premium .crown { font-size: 18px; margin-bottom: 6px; display: block; }
    .sidebar-premium h4 { color: white; font-size: 12px; font-weight: 800; margin-bottom: 4px; }
    .sidebar-premium p { color: #9ca3af; font-size: 10px; line-height: 1.4; margin-bottom: 10px; }
    .sidebar-premium .btn-upgrade { width: 100%; background: #FFD500; color: black; border: none; border-radius: 6px; padding: 7px 0; font-size: 11px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background 0.2s; }
    .sidebar-premium .btn-upgrade:hover { background: #facc15; }

    .sidebar-lang { padding: 10px 16px 14px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 8px; color: #9ca3af; font-size: 12px; cursor: pointer; transition: color 0.2s; }
    .sidebar-lang:hover { color: white; }
    .sidebar-lang svg { width: 16px; height: 16px; }

    /* Main Area */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; background: #050505; }
    
    /* Topbar */
    .topbar { height: 64px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; padding: 0 32px; background: #0a0a0a; flex-shrink: 0; }
    .search-bar { display: flex; align-items: center; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 8px 16px; width: 320px; }
    .search-bar svg { width: 16px; height: 16px; color: #6b7280; margin-right: 8px; }
    .search-bar input { background: transparent; border: none; color: white; font-size: 13px; width: 100%; outline: none; font-family: 'Inter', sans-serif; }
    
    .topbar-actions { display: flex; align-items: center; gap: 16px; }

    /* Dashboard Content */
    .dashboard-content { flex-grow: 1; overflow-y: auto; padding: 32px; display: flex; flex-direction: column; gap: 24px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: white; }
    .date-filter { display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 8px; color: #d1d5db; font-size: 13px; cursor: pointer; }
    
    /* Government Entity Logos */
    .gov-logos { display: flex; align-items: center; gap: 24px; padding: 12px 24px; background: rgba(255,213,0,0.02); border: 1px solid rgba(255,213,0,0.1); border-radius: 12px; }
    .gov-logo-item { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: #9ca3af; }
    .gov-logo-item svg { width: 24px; height: 24px; color: #FFD500; }

    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .stat-card { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 20px; display: flex; align-items: flex-start; justify-content: space-between; }
    .stat-info { display: flex; flex-direction: column; }
    .stat-title { color: #9ca3af; font-size: 12px; font-weight: 600; margin-bottom: 8px; }
    .stat-value { color: white; font-size: 24px; font-weight: 800; margin-bottom: 8px; }
    .stat-trend { display: flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; }
    .trend-up { color: #10b981; }
    .trend-down { color: #ef4444; }
    .stat-icon { width: 40px; height: 40px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; color: #9ca3af; }
    
    /* Layout Main Grid */
    .main-grid { display: grid; grid-template-columns: 2fr 1.1fr; gap: 24px; }
    .panel { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 24px; display: flex; flex-direction: column; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .panel-title { color: white; font-size: 16px; font-weight: 700; }
    .panel-action { color: #FFD500; font-size: 13px; font-weight: 600; cursor: pointer; }

    /* Line Chart Panel */
    .line-chart-box { height: 220px; display: flex; align-items: flex-end; justify-content: space-between; padding-top: 24px; position: relative; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .chart-y-axis { position: absolute; left: 0; top: 0; bottom: 0; display: flex; flex-direction: column; justify-content: space-between; font-size: 10px; color: #6b7280; }
    .chart-bars-wrap { display: flex; width: 100%; justify-content: space-around; height: 100%; padding-left: 24px; align-items: flex-end; }
    .chart-bar-container { display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; gap: 8px; width: 40px; }
    .chart-bar-fill { width: 8px; background: linear-gradient(180deg, #FFD500 0%, transparent 100%); border-radius: 4px 4px 0 0; transition: height 1s; }
    .chart-bar-lbl { font-size: 10px; color: #6b7280; }
    
    /* AI Assistant Chat Panel */
    .ai-assistant { flex-grow: 1; }
    .chat-box { border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; background: rgba(0,0,0,0.2); padding: 16px; display: flex; flex-direction: column; gap: 12px; height: 240px; overflow-y: auto; margin-bottom: 12px; }
    .chat-msg { display: flex; gap: 12px; align-items: flex-start; }
    .chat-avatar { width: 24px; height: 24px; border-radius: 50%; background: #FFD500; display: flex; align-items: center; justify-content: center; color: black; font-size: 10px; font-weight: 800; flex-shrink: 0; }
    .chat-avatar.user { background: #3b82f6; color: white; }
    .chat-bubble { padding: 8px 12px; border-radius: 8px; font-size: 12px; color: #d1d5db; line-height: 1.4; max-width: 80%; background: rgba(255,255,255,0.05); }
    .chat-bubble.ai { background: rgba(255,213,0,0.05); border: 1px solid rgba(255,213,0,0.1); }
    
    .chat-input-wrap { display: flex; gap: 8px; }
    .chat-input-wrap input { flex-grow: 1; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; padding: 8px 12px; color: white; font-size: 12px; outline: none; }
    .chat-input-wrap button { background: #FFD500; color: black; border: none; padding: 8px 16px; font-size: 12px; font-weight: 700; border-radius: 6px; cursor: pointer; }

    /* Nearest Authorities List */
    .authority-list { display: flex; flex-direction: column; gap: 12px; }
    .auth-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; }
    .auth-left { display: flex; align-items: center; gap: 12px; }
    .auth-icon { width: 32px; height: 32px; background: rgba(255,213,0,0.05); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #FFD500; }
    .auth-icon svg { width: 18px; height: 18px; }
    .auth-name { font-size: 13px; font-weight: 600; color: white; }
    .auth-dist { font-size: 11px; color: #6b7280; }
    .auth-status { font-size: 11px; color: #10b981; font-weight: 700; }

    /* QR Code & Mobile App Banner */
    .qr-banner { background: linear-gradient(90deg, rgba(255,213,0,0.05) 0%, rgba(255,213,0,0.01) 100%); border: 1px solid rgba(255,213,0,0.2); border-radius: 12px; padding: 24px; display: flex; align-items: center; justify-content: space-between; gap: 24px; }
    .qr-left { display: flex; align-items: center; gap: 16px; }
    .qr-code-box { width: 80px; height: 80px; background: white; border-radius: 8px; padding: 8px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
    .qr-code-box img { width: 100%; height: 100%; }
    .qr-text h4 { color: white; font-size: 15px; font-weight: 700; margin-bottom: 4px; }
    .qr-text p { color: #9ca3af; font-size: 12px; line-height: 1.4; }
    .btn-qr { background: #FFD500; color: black; font-size: 12px; font-weight: 700; padding: 8px 16px; border: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; white-space: nowrap; }

    /* Table */
    .table-container { width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.1); color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    td { padding: 16px; border-bottom: 1px solid rgba(255,255,255,0.05); color: #d1d5db; font-size: 13px; }
    tr:hover td { background: rgba(255,255,255,0.02); }
    .td-scan { display: flex; align-items: center; gap: 12px; }
    .td-img { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; }
    .badge-status { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; }
    .badge-poor { background: rgba(239,68,68,0.2); color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
    .badge-fair { background: rgba(245,158,11,0.2); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
    .badge-good { background: rgba(16,185,129,0.2); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
    
    .action-btn { color: #FFD500; background: transparent; border: none; cursor: pointer; font-size: 13px; font-weight: 600; }

    /* Modal Styles */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 1000; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: #0a0a0a; border: 1px solid rgba(255,213,0,0.3); border-radius: 16px; padding: 32px; width: 500px; max-width: 90%; box-shadow: 0 0 30px rgba(255,213,0,0.15); display: flex; flex-direction: column; gap: 20px; position: relative; }
    .modal-title { font-size: 20px; font-weight: 800; color: white; display: flex; align-items: center; gap: 12px; }
    .modal-title svg { color: #FFD500; width: 24px; height: 24px; }
    .modal-close { position: absolute; top: 20px; right: 20px; color: #6b7280; cursor: pointer; transition: color 0.2s; background: none; border: none; }
    .modal-close:hover { color: white; }
    .modal-close svg { width: 20px; height: 20px; }
    
    .scan-upload-zone { border: 2px dashed rgba(255,255,255,0.1); border-radius: 12px; padding: 40px 24px; text-align: center; cursor: pointer; transition: all 0.2s; position: relative; overflow: hidden; min-height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .scan-upload-zone:hover { border-color: #FFD500; background: rgba(255,213,0,0.02); }
    .scan-upload-zone svg { width: 48px; height: 48px; color: #FFD500; margin-bottom: 16px; }
    .scan-active-line { position: absolute; top: 0; left: 0; width: 100%; height: 3px; background: #FFD500; box-shadow: 0 0 10px #FFD500; display: none; }
    
    @keyframes scanAnim {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }

    /* Custom Panel Styles */
    .task-card-item:hover {
        background: rgba(255,255,255,0.05) !important;
        border-color: rgba(255,213,0,0.3) !important;
    }
    .select-staff {
        background: #0c0c0c;
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        padding: 10px;
        border-radius: 8px;
        font-size: 13px;
        width: 100%;
        outline: none;
    }
    .select-staff:focus {
        border-color: #FFD500;
    }
    .control-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .control-lbl {
        color: #9ca3af;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    /* Dashboard Tab Switching Premium Styles */
    .dash-tab-btn {
        background: transparent;
        color: #9ca3af;
        border: none;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 12px;
        margin-bottom: -1px;
        outline: none;
        border-bottom: 2px solid transparent;
        transition: all 0.2s ease-in-out;
    }
    .dash-tab-btn:hover {
        color: white;
    }
    .dash-tab-btn.active {
        color: #FFD500;
        border-bottom-color: #FFD500;
        font-weight: 700;
    }

    /* Communications sub-tab switching */
    .comm-tab-content {
        display: none !important;
    }
    .comm-tab-content.active {
        display: block !important;
    }
</style>
@endsection

@section('content')
<div class="dashboard-layout">
    @include('partials.sidebar')

    <!-- Main Content Area -->
    <main class="main-area">
        <!-- Topbar -->
        <header class="topbar">
            <div class="search-bar">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" placeholder="Search analyses, locations, reports..." />
            </div>
            
            <div class="topbar-actions">
                <button class="btn-primary" id="btn-new-analysis" style="padding: 8px 16px; display: flex; align-items: center; gap: 8px; background: #FFD500; color: black; border: none; border-radius: 6px; font-size: 14px; font-weight: 700; cursor: pointer;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    New Analysis
                </button>
            </div>
        </header>

        <!-- Dashboard Body -->
        <div class="dashboard-content">
            <div class="page-header">
                <div>
                    <h1>Dashboard Overview</h1>
                    @if(auth()->check())
                        <div style="color: #FFD500; font-size: 13px; font-weight: 700; margin-top: 4px; text-transform: uppercase;">
                            Role: {{ auth()->user()->role }}
                        </div>
                    @endif
                </div>
                
                <!-- Government Entity Logos Inline -->
                <div class="gov-logos">
                    <div class="gov-logo-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        MCD
                    </div>
                    <div class="gov-logo-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        NHAI
                    </div>
                    <div class="gov-logo-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        MoRTH
                    </div>
                </div>

                <div class="date-filter">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #9ca3af;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Last 30 Days
                </div>
            </div>
            
            @if(auth()->check() && auth()->user()->role === 'officer')
            <div style="display: flex; gap: 16px; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 0px; margin-bottom: 24px; margin-top: -10px;">
                <button class="dash-tab-btn active" id="tab-btn-overview" onclick="switchDashboardTab('overview')" style="transition: all 0.2s;">
                    📊 Overview
                </button>
                <button class="dash-tab-btn" id="tab-btn-messages" onclick="switchDashboardTab('messages')" style="transition: all 0.2s;">
                    ✉️ Messages &amp; Contact Inbox
                </button>
            </div>
            @endif

            <div id="dash-section-overview" class="dash-tab-content">
                @if(auth()->user()->role === 'admin')
                    @include('partials.admin-panel')
                @elseif(auth()->user()->role === 'officer')
                    @include('partials.officer-panel')
                @elseif(auth()->user()->role === 'staff')
                    @include('partials.staff-panel')
                @elseif(auth()->user()->role === 'citizen')
                    @include('partials.citizen-panel')
                @endif
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <div id="dash-section-users" class="dash-tab-content" style="display: none;">
                @include('partials.user-directory')
            </div>
            @endif

            @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'officer'))
            <div id="dash-section-verification" class="dash-tab-content" style="display: none;">
                @include('partials.verification-requests')
            </div>
            @endif

            @if(auth()->check() && auth()->user()->role === 'officer')
            <div id="dash-section-messages" class="dash-tab-content" style="display: none; min-height: 400px;">
                <!-- Communications & Dispatch Center -->
                <div class="panel" style="margin-top: 24px; margin-bottom: 24px; display: block;">
                    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 16px; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(255,213,0,0.05); display: flex; align-items: center; justify-content: center; color: #FFD500;">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            </div>
                            <div>
                                <div class="panel-title" style="font-size: 18px;">Communications &amp; Dispatch Center</div>
                                <span style="font-size: 11px; color: #6b7280;">Secure multi-channel routing &amp; remarks logging</span>
                            </div>
                        </div>
                        <!-- Tab buttons -->
                        <div style="display: flex; gap: 8px; background: rgba(255,255,255,0.02); padding: 4px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05); flex-wrap: wrap;">
                            <button class="comm-tab-btn active" onclick="switchCommTab('citizen-mail', event)" style="background: #FFD500; color: black; border: none; padding: 8px 16px; font-size: 12px; font-weight: 700; border-radius: 6px; cursor: pointer; transition: all 0.2s;">Inbound Citizen Mail</button>
                            <button class="comm-tab-btn" onclick="switchCommTab('municipal-mail', event)" style="background: transparent; color: #9ca3af; border: none; padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 6px; cursor: pointer; transition: all 0.2s;">Inter-Municipal Inbox</button>
                            <button class="comm-tab-btn" onclick="switchCommTab('sent-log', event)" style="background: transparent; color: #9ca3af; border: none; padding: 8px 16px; font-size: 12px; font-weight: 600; border-radius: 6px; cursor: pointer; transition: all 0.2s;">Sent Dispatches</button>
                            <button class="comm-tab-btn" onclick="switchCommTab('new-dispatch', event)" style="background: transparent; color: #FFD500; border: none; padding: 8px 16px; font-size: 12px; font-weight: 700; border-radius: 6px; cursor: pointer; transition: all 0.2s;">+ Draft Dispatch</button>
                        </div>
                    </div>

                    <!-- TAB 1: Inbound Citizen Mail -->
                    <div id="comm-tab-citizen-mail" class="comm-tab-content active">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Citizen Info</th>
                                        <th>Subject / Query</th>
                                        <th>Target Jurisdiction</th>
                                        <th>Status</th>
                                        <th>Remarks / Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incomingCitizenMessages as $msg)
                                    <tr>
                                        <td>
                                            <div style="color: white; font-weight: 600;">{{ $msg->name }}</div>
                                            <div style="font-size: 11px; color: #6b7280;">{{ $msg->email }}</div>
                                            @if($msg->phone)
                                            <div style="font-size: 11px; color: #6b7280;">Ph: {{ $msg->phone }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="color: #FFD500; font-weight: 600; font-size: 13px;">{{ $msg->subject }}</div>
                                            <div style="color: #d1d5db; font-size: 12px; margin-top: 4px; line-height: 1.4; max-width: 400px; white-space: normal;">"{{ $msg->message }}"</div>
                                            <div style="font-size: 11px; color: #6b7280; margin-top: 6px;">Received: {{ $msg->created_at->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td>
                                            <span style="background: rgba(255,213,0,0.05); color: #FFD500; border: 1px solid rgba(255,213,0,0.15); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">{{ $msg->territory }}</span>
                                        </td>
                                        <td>
                                            @if($msg->status === 'replied')
                                            <span class="badge-status badge-good" style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);">REPLIED</span>
                                            @else
                                            <span class="badge-status badge-poor" style="background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2);">PENDING</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($msg->status === 'replied')
                                            <div style="max-width: 250px; font-size: 12px; line-height: 1.4;">
                                                <div style="color: #6b7280; font-size: 10px; font-weight: bold; text-transform: uppercase;">Remarks:</div>
                                                <div style="color: #10b981;">"{{ $msg->reply }}"</div>
                                                <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">By: {{ $msg->replier->name ?? 'Officer' }}</div>
                                            </div>
                                            @else
                                            <button class="action-btn" onclick='openReplyModal({!! json_encode($msg) !!})' style="color: #FFD500; font-weight: 700; background: transparent; border: none; cursor: pointer;">Post Reply / Remarks</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #6b7280; padding: 32px;">No incoming citizen inquiries routed to your jurisdiction.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 2: Inter-Municipal Inbox -->
                    <div id="comm-tab-municipal-mail" class="comm-tab-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sender Agency</th>
                                        <th>Subject / Inquiry</th>
                                        <th>Recipients</th>
                                        <th>Status</th>
                                        <th>Remarks / Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($incomingOfficerMessages as $msg)
                                    <tr>
                                        <td>
                                            <div style="color: white; font-weight: 600;">{{ $msg->name }}</div>
                                            <div style="font-size: 11px; color: #FFD500; font-weight: bold;">{{ $msg->sender_territory }} Officer</div>
                                            <div style="font-size: 11px; color: #6b7280;">{{ $msg->email }}</div>
                                        </td>
                                        <td>
                                            <div style="color: #FFD500; font-weight: 600; font-size: 13px;">{{ $msg->subject }}</div>
                                            <div style="color: #d1d5db; font-size: 12px; margin-top: 4px; line-height: 1.4; max-width: 400px; white-space: normal;">"{{ $msg->message }}"</div>
                                            <div style="font-size: 11px; color: #6b7280; margin-top: 6px;">Received: {{ $msg->created_at->format('d M Y, H:i') }}</div>
                                        </td>
                                        <td>
                                            <span style="background: rgba(255,255,255,0.05); color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px;">{{ $msg->territory }}</span>
                                        </td>
                                        <td>
                                            @if($msg->status === 'replied')
                                            <span class="badge-status badge-good" style="background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2);">RESOLVED</span>
                                            @else
                                            <span class="badge-status badge-poor" style="background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2);">PENDING</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($msg->status === 'replied')
                                            <div style="max-width: 250px; font-size: 12px; line-height: 1.4;">
                                                <div style="color: #6b7280; font-size: 10px; font-weight: bold; text-transform: uppercase;">Remarks:</div>
                                                <div style="color: #10b981;">"{{ $msg->reply }}"</div>
                                                <div style="font-size: 10px; color: #6b7280; margin-top: 2px;">By: {{ $msg->replier->name ?? 'Officer' }}</div>
                                            </div>
                                            @else
                                            <button class="action-btn" onclick='openReplyModal({!! json_encode($msg) !!})' style="color: #FFD500; font-weight: 700; background: transparent; border: none; cursor: pointer;">Post Reply / Remarks</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #6b7280; padding: 32px;">No incoming inter-municipal dispatches routed to your jurisdiction.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 3: Sent Dispatches Log -->
                    <div id="comm-tab-sent-log" class="comm-tab-content">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Recipient Jurisdiction</th>
                                        <th>Subject / Inquiry</th>
                                        <th>Routed Date</th>
                                        <th>Status / Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sentOfficerMessages as $msg)
                                    <tr>
                                        <td>
                                            <span style="background: rgba(255,213,0,0.05); color: #FFD500; border: 1px solid rgba(255,213,0,0.15); padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">{{ $msg->territory }} Agency</span>
                                        </td>
                                        <td>
                                            <div style="color: white; font-weight: 600; font-size: 13px;">{{ $msg->subject }}</div>
                                            <div style="color: #d1d5db; font-size: 12px; margin-top: 4px; line-height: 1.4; max-width: 500px; white-space: normal;">"{{ $msg->message }}"</div>
                                        </td>
                                        <td>{{ $msg->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            @if($msg->status === 'replied')
                                            <div style="max-width: 300px; font-size: 12px; line-height: 1.4;">
                                                <div style="color: #10b981; font-weight: bold; margin-bottom: 2px;">✓ REMARKS RECEIVED:</div>
                                                <div style="color: #a7f3d0; background: rgba(16,185,129,0.03); border: 1px solid rgba(16,185,129,0.1); padding: 8px; border-radius: 6px;">"{{ $msg->reply }}"</div>
                                                <div style="font-size: 10px; color: #6b7280; margin-top: 4px;">Posted by recipient agency officer</div>
                                            </div>
                                            @else
                                            <span class="badge-status badge-poor" style="background: rgba(245,158,11,0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2);">PENDING RECIPIENT REMARKS</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; color: #6b7280; padding: 32px;">No sent dispatches logged. Draft one to coordinate.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB 4: Draft Dispatch Form -->
                    <div id="comm-tab-new-dispatch" class="comm-tab-content">
                        <form action="{{ route('contact.officer.store') }}" method="POST" style="max-width: 600px; display: flex; flex-direction: column; gap: 16px; margin: 0 auto; background: rgba(255,255,255,0.01); border: 1px solid rgba(255,255,255,0.05); padding: 24px; border-radius: 12px; backdrop-filter: blur(8px);">
                            @csrf
                            <div style="font-size: 16px; font-weight: 700; color: white; margin-bottom: 4px;">Draft Inter-Municipal Dispatch</div>
                            <p style="color: #9ca3af; font-size: 12px; margin-top: -10px;">Transmit operational logs, notes, or coordinate cross-territory issues directly with another jurisdiction's officers.</p>
                            
                            <div class="control-group">
                                <span class="control-lbl">Recipient Territory / Municipality</span>
                                <select name="territory" required style="background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); color: white; padding: 12px; border-radius: 8px; font-size: 13px; width: 100%;">
                                    <option value="" disabled selected>Select Recipient Jurisdiction...</option>
                                    @foreach(['Delhi Municipal', 'Bengaluru Municipal', 'Mumbai Municipal', 'Chennai Municipal', 'Other Municipal'] as $muni)
                                        @if($muni !== auth()->user()->territory)
                                            <option value="{{ $muni }}">{{ $muni }} Jurisdiction</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="control-group">
                                <span class="control-lbl">Subject / Purpose</span>
                                <input type="text" name="subject" placeholder="Enter dispatch subject..." required style="background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); color: white; padding: 12px; border-radius: 8px; font-size: 13px;">
                            </div>

                            <div class="control-group">
                                <span class="control-lbl">Dispatch Message</span>
                                <textarea name="message" placeholder="Describe the inquiry, cross-territory repair logistics or other coordination requests..." required style="background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; padding: 12px; font-size: 13px; font-family:'Inter',sans-serif; height: 120px; resize: none;"></textarea>
                            </div>

                            <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                                <button type="submit" class="btn" style="background: #FFD500; color: black; border: none; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    Transmit Dispatch
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Modal 1: New Analysis -->
<div class="modal-overlay" id="modal-new-analysis">
    <div class="modal-box">
        <button class="modal-close" id="close-analysis-modal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            AI Pavement Scan Engine
        </div>
        <p style="color: #9ca3af; font-size: 13px; line-height: 1.4; margin-top: -8px;" id="modal-scan-desc">Drag and drop or select a road image to perform high-fidelity AI detection instantly.</p>
        
        <div class="scan-upload-zone" id="scan-zone" onclick="document.getElementById('modal-file-input').click()">
            <div class="scan-active-line" id="scan-line"></div>
            <div id="upload-prompt" style="text-align: center;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; color: #FFD500; margin: 0 auto 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <div style="font-weight: 700; color: white; margin-bottom: 8px;">Upload Road Photo</div>
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 12px;">Supports JPG, PNG up to 10MB</div>
                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(255,213,0,0.1); border: 1px solid rgba(255,213,0,0.3); border-radius: 4px; color: #FFD500; font-size: 11px; font-weight: 700; cursor: pointer;" onclick="event.stopPropagation(); launchModalCamera();">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 14px; height: 14px; color: #FFD500; margin-bottom: 0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Or Use Camera &amp; Location
                </div>
            </div>
            <div id="scanning-prompt" style="display: none; text-align: center;">
                <div style="font-weight: 800; color: #FFD500; font-size: 16px; margin-bottom: 12px; letter-spacing: 0.1em; text-transform: uppercase;">ANALYZING COGNITIVE LAYER...</div>
                <div style="font-size: 12px; color: #9ca3af; line-height: 1.4;">AI is segmenting pixels and classifying road surface anomalies.</div>
            </div>
        </div>
        <input type="file" id="modal-file-input" accept="image/*" style="display: none;">
        
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px;" id="modal-scan-actions">
            <button class="btn btn-outline" id="btn-cancel-scan" style="padding: 10px 20px; border-radius: 8px; color: white; border: 1px solid rgba(255,255,255,0.1); background: transparent; cursor: pointer; font-weight: 600;">Cancel</button>
        </div>

        <!-- Result / Registration pane inside the modal box -->
        <div id="modal-result-pane" style="display: none; flex-direction: column; gap: 16px; margin-top: 10px;">
            <div style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px; display: flex; flex-direction: column; gap: 8px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280; font-size: 12px;">Scan ID</span>
                    <span style="color: white; font-weight: 700; font-size: 13px;" id="modal-res-scan-id">#RH-2026-0001</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280; font-size: 12px;">Condition</span>
                    <span style="font-weight: 700; font-size: 13px;" id="modal-res-condition">POOR</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280; font-size: 12px;">PCI Score</span>
                    <span style="color: white; font-weight: 700; font-size: 13px;" id="modal-res-pci">42/100</span>
                </div>
            </div>
            
            <button class="btn" id="modal-res-register-btn" style="background:#10b981; color:white; width: 100%; padding: 12px; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px;">
                Register Problem
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
            
            <!-- Registration Form inside modal -->
            <form id="modal-register-form" style="display: none; flex-direction: column; gap: 16px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
                <input type="hidden" id="modal-reg-analysis-id">
                
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Problem Title *</label>
                    <input type="text" id="modal-reg-title" placeholder="E.g., Deep potholes near intersection" required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Municipal Jurisdiction / Territory *</label>
                    <select id="modal-reg-territory" required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
                        @php
                            $userTerritory = auth()->check() ? auth()->user()->territory : '';
                        @endphp
                        <option value="" disabled {{ empty($userTerritory) ? 'selected' : '' }}>Select municipal agency...</option>
                        <option value="Delhi Municipal" {{ $userTerritory === 'Delhi Municipal' ? 'selected' : '' }}>Delhi Municipal</option>
                        <option value="Bengaluru Municipal" {{ $userTerritory === 'Bengaluru Municipal' ? 'selected' : '' }}>Bengaluru Municipal</option>
                        <option value="Mumbai Municipal" {{ $userTerritory === 'Mumbai Municipal' ? 'selected' : '' }}>Mumbai Municipal</option>
                        <option value="Chennai Municipal" {{ $userTerritory === 'Chennai Municipal' ? 'selected' : '' }}>Chennai Municipal</option>
                        <option value="Other Municipal" {{ $userTerritory === 'Other Municipal' ? 'selected' : '' }}>Other State Municipal Directorate</option>
                    </select>
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Location / Address *</label>
                    <input type="text" id="modal-reg-location" placeholder="E.g., MG Road, Sector 4" required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Landmark</label>
                    <input type="text" id="modal-reg-landmark" placeholder="E.g., Near metro station pillar 42" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Remarks / Message</label>
                    <textarea id="modal-reg-remarks" placeholder="Provide extra context, severity or safety warnings for the repair team..." style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; padding: 12px; font-size: 13px; font-family:'Inter',sans-serif; height: 80px; resize: none; outline: none;"></textarea>
                </div>

                <button type="submit" class="btn" style="padding: 12px; border-radius: 8px; color: black; background: #FFD500; border: none; font-weight: 700; cursor: pointer; width: 100%;">Submit &amp; Report Problem</button>
            </form>

            <button id="modal-ack-btn" class="btn btn-outline" style="width:100%; border: 1px solid rgba(255,255,255,0.1); color:#9ca3af; padding:10px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer;">Acknowledge (Close)</button>
        </div>
    </div>
</div>

<!-- Modal 2: View Report (Fallback helper modal if needed) -->
<div class="modal-overlay" id="modal-view-report">
    <div class="modal-box" style="gap: 16px;">
        <button class="modal-close" id="close-report-modal">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: #FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Pavement Assessment Report
        </div>
        
        <div style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 12px; display: flex; flex-direction: column; gap: 8px;">
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280; font-size: 12px;">Inspection ID</span>
                <span style="color: white; font-weight: 700; font-size: 13px;" id="rep-scan-id">#RH-2024-8924</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280; font-size: 12px;">Inspection Date</span>
                <span style="color: white; font-weight: 600; font-size: 13px;" id="rep-date">Today, 10:24 AM</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280; font-size: 12px;">Location</span>
                <span style="color: white; font-weight: 600; font-size: 13px;" id="rep-location">NH-48 Highway, Delhi</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px; text-align: center;">
                <div style="color: #6b7280; font-size: 9px; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 6px; text-transform: uppercase;">PCI score</div>
                <div style="color: #FFD500; font-size: 24px; font-weight: 900;" id="rep-pci">42<span style="font-size: 13px; color: #6b7280;">/100</span></div>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                <div style="color: #6b7280; font-size: 9px; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 6px; text-transform: uppercase;">Condition</div>
                <span class="badge-status badge-poor" id="rep-badge">POOR</span>
            </div>
        </div>

        <div>
            <div style="color: #6b7280; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 6px; text-transform: uppercase;">AI Classification Breakdown</div>
            <div style="color: white; font-size: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 10px;" id="rep-defects">
                2 Potholes, 3 Severe Cracks, 1 Surface Wear Area
            </div>
        </div>

        <div>
            <div style="color: #6b7280; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 6px; text-transform: uppercase;">Recommended Repair Action</div>
            <div style="color: #10b981; font-size: 12px; font-weight: 600; background: rgba(16,185,129,0.05); border: 1px solid rgba(16,185,129,0.15); border-radius: 8px; padding: 10px; line-height: 1.4;" id="rep-recom">
                Immediate hot-mix overlay patching and structural asphalt rehabilitation.
            </div>
        </div>
        
        <button class="btn" id="close-report-btn" style="padding: 12px; border-radius: 8px; color: black; background: #FFD500; border: none; font-weight: 700; cursor: pointer; width: 100%; margin-top: 10px;">Acknowledge Report</button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalNewAnalysis = document.getElementById('modal-new-analysis');
    const btnNewAnalysis = document.getElementById('btn-new-analysis');
    const modalFileInput = document.getElementById('modal-file-input');
    const scanZone = document.getElementById('scan-zone');
    const scanLine = document.getElementById('scan-line');
    const uploadPrompt = document.getElementById('upload-prompt');
    const scanningPrompt = document.getElementById('scanning-prompt');
    const modalScanDesc = document.getElementById('modal-scan-desc');
    
    const modalResultPane = document.getElementById('modal-result-pane');
    const modalResScanId = document.getElementById('modal-res-scan-id');
    const modalResCondition = document.getElementById('modal-res-condition');
    const modalResPci = document.getElementById('modal-res-pci');
    const modalResRegisterBtn = document.getElementById('modal-res-register-btn');
    const modalRegisterForm = document.getElementById('modal-register-form');
    const modalRegAnalysisId = document.getElementById('modal-reg-analysis-id');
    const modalRegLocation = document.getElementById('modal-reg-location');
    const modalAckBtn = document.getElementById('modal-ack-btn');

    if (btnNewAnalysis) {
        btnNewAnalysis.addEventListener('click', () => {
            modalNewAnalysis.classList.add('active');
            resetModalState();
        });
    }

    document.getElementById('close-analysis-modal').addEventListener('click', () => {
        modalNewAnalysis.classList.remove('active');
    });
    document.getElementById('btn-cancel-scan').addEventListener('click', () => {
        modalNewAnalysis.classList.remove('active');
    });
    modalAckBtn.addEventListener('click', () => {
        modalNewAnalysis.classList.remove('active');
        window.location.reload();
    });

    function resetModalState() {
        uploadPrompt.style.display = 'block';
        scanningPrompt.style.display = 'none';
        scanLine.style.display = 'none';
        scanZone.style.display = 'flex';
        modalScanDesc.style.display = 'block';
        modalResultPane.style.display = 'none';
        modalRegisterForm.style.display = 'none';
        modalResRegisterBtn.style.display = 'flex';
        document.getElementById('modal-scan-actions').style.display = 'flex';
        modalFileInput.value = '';
    }

    // Drag-and-drop on modal zone
    scanZone.addEventListener('dragover', e => e.preventDefault());
    scanZone.addEventListener('drop', e => {
        e.preventDefault();
        const f = e.dataTransfer.files[0];
        if (f && f.type.startsWith('image/')) {
            triggerScan(f);
        }
    });

    modalFileInput.addEventListener('change', function() {
        if (this.files[0]) {
            triggerScan(this.files[0]);
        }
    });

    window.launchModalCamera = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const camInput = document.createElement('input');
                camInput.type = 'file';
                camInput.accept = 'image/*';
                camInput.capture = 'environment';
                camInput.onchange = function() {
                    if (camInput.files.length > 0) {
                        triggerScan(camInput.files[0], lat, lng, "Live Camera Location");
                    }
                };
                camInput.click();
            }, function(error) {
                alert("Location access is required for camera capture. Using standard file chooser.");
                const camInput = document.createElement('input');
                camInput.type = 'file';
                camInput.accept = 'image/*';
                camInput.capture = 'environment';
                camInput.onchange = function() {
                    if (camInput.files.length > 0) {
                        triggerScan(camInput.files[0]);
                    }
                };
                camInput.click();
            });
        } else {
            const camInput = document.createElement('input');
            camInput.type = 'file';
            camInput.accept = 'image/*';
            camInput.capture = 'environment';
            camInput.onchange = function() {
                if (camInput.files.length > 0) {
                    triggerScan(camInput.files[0]);
                }
            };
            camInput.click();
        }
    };

    function triggerScan(file, lat = null, lng = null, loc = null) {
        uploadPrompt.style.display = 'none';
        scanningPrompt.style.display = 'block';
        scanLine.style.display = 'block';
        scanLine.style.animation = 'scanAnim 1.5s ease-in-out infinite';

        if (!lat && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                sendAnalysisRequest(file, position.coords.latitude, position.coords.longitude, "Uploaded Location");
            }, error => {
                sendAnalysisRequest(file);
            }, { timeout: 5000 });
        } else {
            sendAnalysisRequest(file, lat, lng, loc);
        }
    }

    function sendAnalysisRequest(file, lat = null, lng = null, loc = null) {
        const fd = new FormData();
        fd.append('road_image', file);
        fd.append('json', '1');
        if (lat) fd.append('latitude', lat);
        if (lng) fd.append('longitude', lng);
        if (loc) fd.append('location', loc);
        fd.append('_token', document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '');

        fetch('/analyze', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: fd
        })
        .then(r => {
            if (!r.ok) throw new Error('Analysis failed.');
            return r.json();
        })
        .then(data => {
            displayResult(data);
        })
        .catch(err => {
            console.error(err);
            alert('Analysis failed. Make sure the AI server is active.');
            resetModalState();
        });
    }

    function displayResult(data) {
        // Hide scanning prompts
        scanZone.style.display = 'none';
        modalScanDesc.style.display = 'none';
        document.getElementById('modal-scan-actions').style.display = 'none';
        
        // Show results panel
        modalResultPane.style.display = 'flex';
        modalResScanId.textContent = data.scan_id;
        modalResCondition.textContent = (data.condition || 'Unknown').toUpperCase();
        modalResPci.textContent = (data.pci_score ?? 'N/A') + '/100';

        // Color condition text
        const cond = (data.condition || 'Unknown').toUpperCase();
        const pci = data.pci_score ?? 0;
        if (cond === 'INVALID') {
            modalResCondition.style.color = '#6b7280';
            modalResPci.textContent = 'NOT A ROAD';
        } else {
            modalResCondition.style.color = pci < 55 ? '#ef4444' : pci < 75 ? '#f59e0b' : '#10b981';
        }

        // Prepare registration values
        modalRegAnalysisId.value = data.id;
        modalRegLocation.value = data.location || '';
    }

    modalResRegisterBtn.addEventListener('click', function() {
        modalResRegisterBtn.style.display = 'none';
        modalRegisterForm.style.display = 'flex';
    });

    modalRegisterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = modalRegAnalysisId.value;
        const title = document.getElementById('modal-reg-title').value;
        const territory = document.getElementById('modal-reg-territory').value;
        const location = modalRegLocation.value;
        const landmark = document.getElementById('modal-reg-landmark').value;
        const remarks = document.getElementById('modal-reg-remarks').value;

        const submitBtn = modalRegisterForm.querySelector('button[type="submit"]');
        submitBtn.textContent = 'Registering...';
        submitBtn.disabled = true;

        const fd = new FormData();
        fd.append('title', title);
        fd.append('territory', territory);
        fd.append('location', location);
        fd.append('landmark', landmark);
        fd.append('remarks', remarks);
        fd.append('json', '1');
        fd.append('_token', document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '');

        fetch('/reports/' + id + '/register', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: fd
        })
        .then(r => {
            if(!r.ok) throw new Error('Registration failed.');
            return r.json();
        })
        .then(data => {
            modalNewAnalysis.classList.remove('active');
            alert('Success! Problem registered and forwarded to municipal officers.');
            window.location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Registration failed. Please try again.');
            submitBtn.textContent = 'Submit & Report Problem';
            submitBtn.disabled = false;
        });
    });
});
</script>

@if(auth()->check() && (auth()->user()->role === 'officer' || auth()->user()->role === 'admin'))
<!-- Modal 4: Post Reply / Remarks Modal -->
<div class="modal-overlay" id="modal-reply-remarks">
    <div class="modal-box" style="width: 500px;">
        <button class="modal-close" onclick="closeReplyModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title" style="color: #FFD500;">
            Post Reply / Remarks
        </div>
        
        <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px; display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 11px; color: #6b7280; font-weight: bold; text-transform: uppercase;">Inquiry Details</span>
                <span id="reply-msg-date" style="font-size: 11px; color: #6b7280;">Date</span>
            </div>
            <div>
                <span style="font-size: 10px; color: #6b7280; display: block;">From:</span>
                <div style="font-size: 12px; color: white; font-weight: bold;" id="reply-msg-sender">Sender</div>
            </div>
            <div>
                <span style="font-size: 10px; color: #6b7280; display: block;">Subject:</span>
                <div style="font-size: 12px; color: #FFD500; font-weight: 600;" id="reply-msg-subject">Subject</div>
            </div>
            <div>
                <span style="font-size: 10px; color: #6b7280; display: block;">Message:</span>
                <div style="font-size: 12px; color: #d1d5db; line-height: 1.4; font-style: italic; max-height: 100px; overflow-y: auto;" id="reply-msg-body">"Message content..."</div>
            </div>
        </div>

        <form id="reply-remarks-form" action="" method="POST" style="display: flex; flex-direction: column; gap: 16px; margin: 0;">
            @csrf
            <div>
                <label style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px; text-align: left;">Your Reply / Remarks</label>
                <textarea name="reply" placeholder="Type your official remarks or reply to transmit to the sender..." required style="width:100%; height:100px; background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; padding: 12px; font-size: 13px; font-family:'Inter',sans-serif; outline:none; resize: none;"></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeReplyModal()" style="border-color: rgba(255,255,255,0.1); color: #9ca3af; background: transparent; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn" style="background: #FFD500; color: black; border: none; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Submit Remarks</button>
            </div>
        </form>
    </div>
</div>

<!-- Officer/Staff Verification Modal -->
<div class="modal-overlay" id="modal-approve-officer">
    <div class="modal-box" style="width: 550px;">
        <button class="modal-close" onclick="closeApprovalModal()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title" style="color: #FFD500;">
            Review Account Registration
        </div>
        
        <div style="display: flex; gap: 16px; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 16px;">
            <img id="rev-photo" src="" alt="Profile Photo" style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid #FFD500;">
            <div>
                <h4 style="color: white; font-size: 16px; font-weight: bold; text-align: left;" id="rev-name">Officer Name</h4>
                <p style="color: #9ca3af; font-size: 12px; text-align: left;" id="rev-email">email@example.com</p>
                <p style="color: #6b7280; font-size: 11px; margin-top: 4px; text-align: left;" id="rev-geography">Delhi Municipal &bull; Zone 1</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px; text-align: left;">
            <div>
                <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase;">Employee ID</span>
                <div style="color: white; font-weight: bold; font-size: 13px;" id="rev-id">EMP-001</div>
            </div>
            <div>
                <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase;">Department</span>
                <div style="color: white; font-weight: bold; font-size: 13px;" id="rev-dept">Road &amp; Bridges</div>
            </div>
        </div>

        <div style="margin-top: 16px; text-align: left;">
            <span style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px;">Government ID Document</span>
            <div id="rev-id-doc-container" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px; text-align: center;">
                <a id="rev-id-link" href="" target="_blank" style="color: #FFD500; font-weight: bold; font-size: 12px; display: inline-flex; align-items: center; gap: 8px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download / View Government ID File
                </a>
            </div>
        </div>

        <form id="approval-form" action="" method="POST" style="display:flex; flex-direction:column; gap:16px; margin-top:20px; margin-bottom:0;">
            @csrf
            <div>
                <label style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; display: block; margin-bottom: 6px; text-align: left;">Remarks / Reason (Optional)</label>
                <textarea name="remarks" placeholder="Enter reason for approval or rejection..." style="width:100%; height:80px; background: #0c0c0c; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; padding: 10px; font-size: 13px; font-family:'Inter',sans-serif; outline:none; resize: none;"></textarea>
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeApprovalModal()" style="border-color: rgba(255,255,255,0.1); color: #9ca3af; background: transparent; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Cancel</button>
                <button type="submit" name="status" value="rejected" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444; background: transparent; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Reject Account</button>
                <button type="submit" name="status" value="approved" class="btn" style="background: #10b981; color: white; border: none; padding: 12px 24px; font-weight: bold; border-radius: 8px; cursor: pointer;">Approve Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openApprovalModal(user) {
        document.getElementById('rev-name').textContent = user.name;
        document.getElementById('rev-email').textContent = user.email;
        document.getElementById('rev-geography').textContent = user.territory + ' • ' + user.zone + ' • ' + user.ward;
        document.getElementById('rev-id').textContent = user.employee_id || 'N/A';
        document.getElementById('rev-dept').textContent = user.department || 'N/A';
        
        const photoUrl = user.profile_photo_path ? '/storage/' + user.profile_photo_path : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.name);
        document.getElementById('rev-photo').src = photoUrl;

        if (user.government_id_path) {
            document.getElementById('rev-id-link').href = '/storage/' + user.government_id_path;
            document.getElementById('rev-id-doc-container').style.display = 'block';
        } else {
            document.getElementById('rev-id-doc-container').style.display = 'none';
        }

        // Set action route dynamic
        document.getElementById('approval-form').action = '/dashboard/approve-user/' + user.id;

        document.getElementById('modal-approve-officer').classList.add('active');
    }

    function closeApprovalModal() {
        document.getElementById('modal-approve-officer').classList.remove('active');
    }

    // Tab switching for Overview vs Messages
    function switchDashboardTab(tabName) {
        // Toggle tab content visibility
        document.querySelectorAll('.dash-tab-content').forEach(el => {
            el.style.display = 'none';
        });
        const targetContent = document.getElementById('dash-section-' + tabName);
        if (targetContent) {
            targetContent.style.display = 'block';
        }

        // Toggle active styling on buttons
        document.querySelectorAll('.dash-tab-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.style.color = '#9ca3af';
            btn.style.fontWeight = '600';
            btn.style.borderBottomColor = 'transparent';
        });

        const activeBtn = document.getElementById('tab-btn-' + tabName);
        if (activeBtn) {
            activeBtn.classList.add('active');
            activeBtn.style.color = '#FFD500';
            activeBtn.style.fontWeight = '700';
            activeBtn.style.borderBottomColor = '#FFD500';
        }

        // Keep active sidebar class updated if needed
        const sidebarMessages = document.getElementById('sidebar-nav-messages');
        const sidebarDashboard = document.querySelector('.sidebar-nav a[href="/dashboard"]');
        const sidebarUsers = document.querySelector('a[href="/dashboard?tab=users"]');
        const sidebarVerification = document.querySelector('a[href="/dashboard?tab=verification"]');
        
        if (tabName === 'messages') {
            if (sidebarMessages) sidebarMessages.classList.add('active');
            if (sidebarDashboard) sidebarDashboard.classList.remove('active');
            if (sidebarUsers) sidebarUsers.classList.remove('active');
            if (sidebarVerification) sidebarVerification.classList.remove('active');
        } else if (tabName === 'users') {
            if (sidebarMessages) sidebarMessages.classList.remove('active');
            if (sidebarDashboard) sidebarDashboard.classList.remove('active');
            if (sidebarUsers) sidebarUsers.classList.add('active');
            if (sidebarVerification) sidebarVerification.classList.remove('active');
        } else if (tabName === 'verification') {
            if (sidebarMessages) sidebarMessages.classList.remove('active');
            if (sidebarDashboard) sidebarDashboard.classList.remove('active');
            if (sidebarUsers) sidebarUsers.classList.remove('active');
            if (sidebarVerification) sidebarVerification.classList.add('active');
        } else {
            if (sidebarMessages) sidebarMessages.classList.remove('active');
            if (sidebarDashboard) sidebarDashboard.classList.add('active');
            if (sidebarUsers) sidebarUsers.classList.remove('active');
            if (sidebarVerification) sidebarVerification.classList.remove('active');
        }
    }

    // Communications Center Switcher
    function switchCommTab(tabId, event) {
        // Toggle tab content — use CSS class only (inline styles override CSS)
        const tabContents = document.querySelectorAll('.comm-tab-content');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });
        const targetContent = document.getElementById('comm-tab-' + tabId);
        if (targetContent) {
            targetContent.classList.add('active');
        }

        // Toggle tab buttons state
        const tabBtns = document.querySelectorAll('.comm-tab-btn');
        tabBtns.forEach(btn => {
            btn.style.background = 'transparent';
            btn.style.color = '#9ca3af';
            btn.classList.remove('active');
            btn.style.fontWeight = '600';
        });
        
        // Style current button
        let clickedBtn = null;
        if (event && event.currentTarget) {
            clickedBtn = event.currentTarget;
        } else if (typeof window !== 'undefined' && window.event && window.event.currentTarget) {
            clickedBtn = window.event.currentTarget;
        } else {
            // Robust fallback if no event is passed
            tabBtns.forEach(btn => {
                const attr = btn.getAttribute('onclick');
                if (attr && attr.includes("'" + tabId + "'")) {
                    clickedBtn = btn;
                }
            });
        }
        
        if (clickedBtn) {
            if (tabId === 'new-dispatch') {
                clickedBtn.style.background = 'rgba(255,213,0,0.1)';
                clickedBtn.style.color = '#FFD500';
            } else {
                clickedBtn.style.background = '#FFD500';
                clickedBtn.style.color = 'black';
            }
            clickedBtn.style.fontWeight = '700';
            clickedBtn.classList.add('active');
        }
    }

    function openReplyModal(msg) {
        const createdDate = new Date(msg.created_at);
        document.getElementById('reply-msg-date').textContent = createdDate.toLocaleDateString() + ' ' + createdDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        document.getElementById('reply-msg-sender').textContent = msg.name + ' (' + msg.email + ')' + (msg.sender_territory ? ' [' + msg.sender_territory + ']' : '');
        document.getElementById('reply-msg-subject').textContent = msg.subject;
        document.getElementById('reply-msg-body').textContent = '"' + msg.message + '"';
        
        document.getElementById('reply-remarks-form').action = '/dashboard/contact/reply/' + msg.id;
        document.getElementById('modal-reply-remarks').classList.add('active');
    }

    function closeReplyModal() {
        document.getElementById('modal-reply-remarks').classList.remove('active');
    }

    // Auto-activate tab from URL parameters
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab === 'messages') {
            switchDashboardTab('messages');
        } else if (activeTab === 'users') {
            switchDashboardTab('users');
        } else if (activeTab === 'verification') {
            switchDashboardTab('verification');
        }
    });
</script>
@endif
@endsection
