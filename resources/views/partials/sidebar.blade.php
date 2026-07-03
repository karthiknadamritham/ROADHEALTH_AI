<!-- Sidebar -->
<aside class="sidebar">
    <!-- Nav -->
    <nav class="sidebar-nav">
        <a href="/dashboard" class="nav-item {{ (request()->is('dashboard') && !request()->filled('tab')) ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            Dashboard
        </a>
        <a href="/upload" class="nav-item {{ request()->is('upload') ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Upload &amp; Analyze
        </a>
        <a href="/reports" class="nav-item {{ (request()->is('reports') || request()->is('reports/*')) ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Analysis History
        </a>
        <a href="/network" class="nav-item {{ request()->is('network') ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
            Road Network
        </a>
        <a href="/notifications" class="nav-item {{ request()->is('notifications') ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            Notifications
        </a>
        <a href="/dashboard/report-export" class="nav-item {{ request()->is('dashboard/report-export') ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Reports &amp; Exports
        </a>
        
        @if(auth()->check() && auth()->user()->role !== 'admin')
        <a href="/dashboard/maintenance" class="nav-item {{ request()->is('dashboard/maintenance') ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Maintenance Requests
        </a>
        @endif

        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="nav-divider"></div>
        <div style="padding: 10px 12px; font-size: 10px; font-weight: 800; color: #6b7280; text-transform: uppercase; letter-spacing: 0.1em;">Admin Panel</div>
        <a href="/dashboard?tab=users" class="nav-item {{ request('tab') === 'users' ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            User Management
        </a>
        <a href="/dashboard?tab=verification" class="nav-item {{ request('tab') === 'verification' ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Verification Requests
        </a>
        @endif
        
        @if(auth()->check() && auth()->user()->role === 'officer')
        <a href="/dashboard?tab=messages" class="nav-item {{ request('tab') === 'messages' ? 'active' : '' }}" id="sidebar-nav-messages" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03-8 9-8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Messages
        </a>
        <a href="/dashboard?tab=verification" class="nav-item {{ request('tab') === 'verification' ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Verification Requests
        </a>
        @endif
        
        <a href="/contact" class="nav-item {{ request()->is('contact') ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Authorities &amp; Contact
        </a>

        <div class="nav-divider"></div>

        <a href="/ai-chat" class="nav-item {{ request()->is('ai-chat') ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            AI Assistant
            <span class="nav-badge ai">AI</span>
        </a>
        <a href="/settings" class="nav-item {{ request()->is('settings') ? 'active' : '' }}" style="text-decoration:none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Settings
        </a>
        <div class="nav-item" onclick="alert('Help &amp; Support coming soon!')">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Help &amp; Support
        </div>
    </nav>

    <!-- Go Premium Card -->
    <div class="sidebar-premium" style="margin:10px;background:linear-gradient(135deg,rgba(255,213,0,0.12) 0%,rgba(255,213,0,0.02) 100%);border:1px solid rgba(255,213,0,0.2);border-radius:12px;padding:14px;">
        <span style="font-size:18px;margin-bottom:6px;display:block;">👑</span>
        <h4 style="color:white;font-size:12px;font-weight:800;margin-bottom:4px;margin-top:0;">Go Premium</h4>
        <p style="color:#9ca3af;font-size:10px;line-height:1.4;margin-bottom:10px;margin-top:0;">Unlock advanced analytics, priority support &amp; more.</p>
        <button style="width:100%;background:#FFD500;color:black;border:none;border-radius:6px;padding:7px 0;font-size:11px;font-weight:800;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;" onclick="alert('Subscription gateway coming soon!')">
            Upgrade Now
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </div>

    <!-- Language Selector -->
    <div class="sidebar-lang">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
        English
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:12px;height:12px;margin-left:auto;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </div>
</aside>
