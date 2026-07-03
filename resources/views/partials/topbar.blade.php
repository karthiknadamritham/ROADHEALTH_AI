<header class="topbar">
    <div>
        <h2>{{ $title }}</h2>
        @if(isset($subtitle))
            <span class="topbar-sub">{{ $subtitle }}</span>
        @endif
    </div>
    
    <div style="display:flex; align-items:center; gap:20px; margin-left:auto;">
        <!-- Search bar (only visible on some pages, or we can make it global) -->
        <div style="position:relative;">
            <input type="text" placeholder="Search..." style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 8px 16px 8px 36px; color: white; font-size: 13px; outline: none;">
            <svg fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="position:absolute; left:12px; top:10px; width:16px; height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>

        <!-- Notification Bell -->
        <div class="notif-bell-wrap" id="notif-bell-wrap">
            <button class="notif-bell" onclick="toggleNotifDropdown()">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span class="notif-badge">3</span>
            </button>
            
            <div class="notif-dropdown" id="notif-dropdown">
                <div class="notif-header">
                    <div class="notif-title">
                        Notifications
                        <span style="background: rgba(255,213,0,0.2); color:#FFD500; font-size:10px; padding:2px 6px; border-radius:10px;">3 New</span>
                    </div>
                    <div class="notif-mark-read" onclick="markAllRead()">Mark all read</div>
                </div>
                
                <div class="notif-list">
                    <!-- Critical Alert (Officer View Example) -->
                    <div class="notif-item unread">
                        <div class="notif-icon critical">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-text"><strong>AI Alert:</strong> Severe pothole detected with high accident risk near Highway Sector 7.</div>
                            <div class="notif-time">2 mins ago</div>
                        </div>
                    </div>
                    
                    <!-- Warning Alert -->
                    <div class="notif-item unread">
                        <div class="notif-icon warning">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-text"><strong>Deadline Approaching:</strong> Maintenance task #RH-2045 is due in 2 hours.</div>
                            <div class="notif-time">15 mins ago</div>
                        </div>
                    </div>

                    <!-- Success Alert -->
                    <div class="notif-item unread">
                        <div class="notif-icon success">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-text"><strong>Repair Verified:</strong> Team Alpha successfully completed the repair at MG Road.</div>
                            <div class="notif-time">1 hour ago</div>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="notif-item">
                        <div class="notif-icon info">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-text"><strong>New Assignment:</strong> You have been assigned a new repair task by Officer Kumar.</div>
                            <div class="notif-time">3 hours ago</div>
                        </div>
                    </div>
                </div>
                
                <div class="notif-footer">
                    <button class="notif-view-all" onclick="window.location.href='/notifications'">View All Notifications</button>
                </div>
            </div>
        </div>

        <button class="btn-icon" style="border: 1px solid rgba(255,255,255,0.1);" onclick="window.location.href='/logout'">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        </button>
    </div>
</header>

<script>
    function toggleNotifDropdown() {
        document.getElementById('notif-dropdown').classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const wrap = document.getElementById('notif-bell-wrap');
        if (wrap && !wrap.contains(event.target)) {
            document.getElementById('notif-dropdown').classList.remove('active');
        }
    });

    function markAllRead() {
        const items = document.querySelectorAll('.notif-item.unread');
        items.forEach(item => item.classList.remove('unread'));
        const badge = document.querySelector('.notif-badge');
        if (badge) badge.style.display = 'none';
        
        const titleBadge = document.querySelector('.notif-title span');
        if (titleBadge) titleBadge.style.display = 'none';
    }
</script>
