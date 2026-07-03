@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'Notification Center')

@section('styles')
<style>
    body { background-color: #050505; color: white; overflow: hidden; }
    .dashboard-layout { display: flex; height: calc(100vh - 73px); overflow: hidden; }
    
    /* Sidebar */
    .sidebar { width: 220px; min-width: 220px; background: #0a0a0a; border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-nav { padding: 12px 10px; flex-grow: 1; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; text-decoration: none; }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.12); color: #FFD500; font-weight: 700; border-left: 3px solid #FFD500; border-radius: 4px 8px 8px 4px; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 10px; }

    .sidebar-premium { margin: 10px; background: linear-gradient(135deg, rgba(255,213,0,0.12) 0%, rgba(255,213,0,0.02) 100%); border: 1px solid rgba(255,213,0,0.2); border-radius: 12px; padding: 14px; }
    .sidebar-premium h4 { color: white; font-size: 12px; font-weight: 800; margin-bottom: 4px; }
    .sidebar-premium p { color: #9ca3af; font-size: 10px; line-height: 1.4; margin-bottom: 10px; }
    .btn-upgrade { width: 100%; background: #FFD500; color: black; border: none; border-radius: 6px; padding: 7px 0; font-size: 11px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; }

    /* Main Area */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; background: #050505; }
    .topbar { height: 64px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; padding: 0 32px; background: #0a0a0a; flex-shrink: 0; }
    .topbar-title { font-size: 18px; font-weight: 800; color: white; display: flex; align-items: center; gap: 12px; }
    
    .content-scrollable { flex-grow: 1; overflow-y: auto; padding: 32px; }

    /* Notification Page Layout */
    .notif-container { width: 100%; max-width: 900px; margin: 0 auto; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.5); }
    
    .notif-header-panel { padding: 24px 32px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.01); }
    .notif-header-panel h1 { font-size: 24px; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 12px; }
    .notif-header-panel h1 svg { width: 28px; height: 28px; color: #FFD500; }
    .mark-all-read { background: rgba(255,213,0,0.1); color: #FFD500; border: 1px solid rgba(255,213,0,0.2); padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
    .mark-all-read:hover { background: rgba(255,213,0,0.2); }

    /* Tabs */
    .notif-tabs { display: flex; padding: 0 32px; border-bottom: 1px solid rgba(255,255,255,0.05); background: #0a0a0a; }
    .tab-item { padding: 16px 24px; color: #9ca3af; font-size: 14px; font-weight: 600; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.2s; position: relative; }
    .tab-item:hover { color: white; }
    .tab-item.active { color: #FFD500; border-bottom-color: #FFD500; }
    .tab-badge { position: absolute; top: 12px; right: 8px; background: #ef4444; color: white; font-size: 10px; font-weight: 800; padding: 2px 6px; border-radius: 10px; }

    /* List */
    .notif-list-full { display: flex; flex-direction: column; }
    .date-divider { padding: 12px 32px; background: rgba(255,255,255,0.02); color: #6b7280; font-size: 12px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.05); border-top: 1px solid rgba(255,255,255,0.05); }
    
    .notif-row { padding: 20px 32px; border-bottom: 1px solid rgba(255,255,255,0.03); display: flex; gap: 16px; transition: background 0.2s; cursor: pointer; position: relative; }
    .notif-row:hover { background: rgba(255,255,255,0.02); }
    .notif-row.unread { background: rgba(255,213,0,0.02); border-left: 3px solid #FFD500; padding-left: 29px; }
    
    .notif-icon { width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .notif-icon.info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59,130,246,0.2); }
    .notif-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
    .notif-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
    .notif-icon.critical { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
    .notif-icon svg { width: 22px; height: 22px; }
    
    .notif-content { flex-grow: 1; display: flex; flex-direction: column; gap: 6px; justify-content: center; }
    .notif-title-text { font-size: 15px; font-weight: 700; color: white; display: flex; align-items: center; gap: 8px; }
    .notif-desc { font-size: 13px; color: #d1d5db; line-height: 1.5; }
    .notif-meta { font-size: 11px; color: #6b7280; font-weight: 500; display: flex; gap: 12px; align-items: center; margin-top: 4px; }
    
    .notif-action-btn { padding: 6px 12px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; font-size: 11px; font-weight: 700; border-radius: 6px; cursor: pointer; transition: all 0.2s; }
    .notif-action-btn:hover { background: white; color: black; }
</style>
@endsection

@section('content')
<div class="dashboard-layout">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content Area -->
    <main class="main-area">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-title">Notification Center</div>
        </header>

        <!-- Body -->
        <div class="content-scrollable">
            <div class="notif-container">
                <div class="notif-header-panel">
                    <h1>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        All Notifications
                    </h1>
                    <form action="{{ route('notifications.markRead') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="mark-all-read">Mark All as Read</button>
                    </form>
                </div>

                <form class="notif-tabs" action="{{ route('notifications') }}" method="GET" style="display: flex; justify-content: flex-end; align-items: center; gap: 12px; padding: 16px 32px; margin:0;">
                    <label style="color:#9ca3af; font-size:13px; font-weight:600;">Filter by Date Range:</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:white; padding:8px 12px; border-radius:6px; font-size:13px; outline:none; font-family:inherit; color-scheme:dark;">
                    <span style="color:#6b7280; font-size:13px;">to</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:white; padding:8px 12px; border-radius:6px; font-size:13px; outline:none; font-family:inherit; color-scheme:dark;">
                    <button type="submit" style="background:#FFD500; color:black; border:none; padding:8px 16px; border-radius:6px; font-size:13px; font-weight:700; cursor:pointer;">Apply</button>
                    @if(request()->filled('start_date'))
                        <a href="{{ route('notifications') }}" style="color:#9ca3af; font-size:12px; text-decoration:none;">Clear</a>
                    @endif
                </form>

                <div class="notif-list-full">
                    
                    @forelse($notifications as $notif)
                    @php
                        $data = $notif->data;
                        $iconSvg = '';
                        if ($data['severity_class'] === 'critical') {
                            $iconSvg = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                        } elseif ($data['severity_class'] === 'warning') {
                            $iconSvg = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        } elseif ($data['severity_class'] === 'success') {
                            $iconSvg = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                        } else {
                            $iconSvg = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        }
                    @endphp
                    <div class="notif-row {{ is_null($notif->read_at) ? 'unread' : '' }}">
                        <div class="notif-icon {{ $data['severity_class'] ?? 'info' }}">
                            {!! $iconSvg !!}
                        </div>
                        <div class="notif-content">
                            <div class="notif-title-text">
                                {{ $data['title'] ?? 'Alert' }}
                                @if(isset($data['severity_class']) && $data['severity_class'] === 'critical')
                                <span style="background:rgba(239,68,68,0.2); color:#ef4444; font-size:9px; padding:2px 6px; border-radius:4px; text-transform:uppercase;">Critical Risk</span>
                                @endif
                            </div>
                            <div class="notif-desc">{{ $data['description'] ?? '' }}</div>
                            <div class="notif-meta">
                                <span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;display:inline;margin-right:2px;margin-bottom:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $notif->created_at->diffForHumans() }}</span>
                                @if(isset($data['location']))
                                <span><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;display:inline;margin-right:2px;margin-bottom:-2px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg> {{ $data['location'] }}</span>
                                @endif
                            </div>
                        </div>
                        @php
                            $redirectUrl = '/reports/' . ($data['analysis_id'] ?? '');
                            if (isset($data['action']) && $data['action'] === 'progress_update') {
                                $redirectUrl = auth()->user()->role === 'citizen' ? '/dashboard/maintenance' : '/dashboard';
                            }
                        @endphp
                        <div style="display:flex; align-items:center;">
                            <button class="notif-action-btn" onclick="window.location.href='{{ $redirectUrl }}'">View Details</button>
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        No notifications found.
                    </div>
                    @endforelse
                    
                    <div style="margin-top: 20px;">
                        {{ $notifications->links() }}
                    </div>

                    </div>

                </div>
            </div>
        </div>
    </main>
</div>
@endsection
