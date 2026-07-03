<!-- Notification Bell -->
@php
    $unreadCount = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
    $notifications = auth()->check() ? auth()->user()->unreadNotifications()->take(20)->get() : collect();
@endphp

<div class="notif-bell-wrap" id="notif-bell-wrap">
    <button class="btn-icon notif-bell" onclick="toggleNotifDropdown()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        @if($unreadCount > 0)
        <span class="notif-badge">{{ $unreadCount }}</span>
        @endif
    </button>
    
    <div class="notif-dropdown" id="notif-dropdown">
        <div class="notif-header">
            <div class="notif-title">
                Notifications
                @if($unreadCount > 0)
                <span style="background: rgba(255,213,0,0.2); color:#FFD500; font-size:10px; padding:2px 6px; border-radius:10px;">{{ $unreadCount }} New</span>
                @endif
            </div>
            <form action="{{ route('notifications.markRead') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="notif-mark-read" style="background:none; border:none; padding:0;">Mark all read</button>
            </form>
        </div>
        
        <div class="notif-list">
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
            @php
                $redirectUrl = '/reports/' . ($data['analysis_id'] ?? '');
                if (isset($data['action']) && $data['action'] === 'progress_update') {
                    $redirectUrl = auth()->user()->role === 'citizen' ? '/dashboard/maintenance' : '/dashboard';
                }
            @endphp
            <div class="notif-item {{ is_null($notif->read_at) ? 'unread' : '' }}" onclick="window.location.href='{{ $redirectUrl }}'">
                <div class="notif-icon {{ $data['severity_class'] ?? 'info' }}">
                    {!! $iconSvg !!}
                </div>
                <div class="notif-content">
                    <div class="notif-text"><strong>{{ $data['title'] ?? 'Alert' }}:</strong> {{ $data['description'] ?? '' }}</div>
                    <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="notif-item">
                <div class="notif-content" style="text-align: center; color: #6b7280;">
                    No new notifications.
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="notif-footer">
            <button class="notif-view-all" onclick="window.location.href='/notifications'">View All Notifications</button>
        </div>
    </div>
</div>

<script>
    function toggleNotifDropdown() {
        document.getElementById('notif-dropdown').classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const wrap = document.getElementById('notif-bell-wrap');
        if (wrap && !wrap.contains(event.target)) {
            const dropdown = document.getElementById('notif-dropdown');
            if (dropdown) dropdown.classList.remove('active');
        }
    });

    function pollNotifications() {
        fetch('/notifications/unread-count')
            .then(response => response.json())
            .then(data => {
                // Update unread count badge
                const bell = document.querySelector('.notif-bell');
                if (bell) {
                    let badge = bell.querySelector('.notif-badge');
                    if (data.unreadCount > 0) {
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'notif-badge';
                            bell.appendChild(badge);
                        }
                        badge.textContent = data.unreadCount;
                    } else if (badge) {
                        badge.remove();
                    }
                }

                // Update title new count
                const notifTitle = document.querySelector('.notif-title');
                if (notifTitle) {
                    // Clear any existing sub-badge
                    const existingBadge = notifTitle.querySelector('span');
                    if (existingBadge) {
                        existingBadge.remove();
                    }
                    if (data.unreadCount > 0) {
                        const countSpan = document.createElement('span');
                        countSpan.style = "background: rgba(255,213,0,0.2); color:#FFD500; font-size:10px; padding:2px 6px; border-radius:10px; margin-left: 8px;";
                        countSpan.textContent = data.unreadCount + ' New';
                        notifTitle.appendChild(countSpan);
                    }
                }

                // Update notif list
                const notifList = document.querySelector('.notif-list');
                if (notifList && data.notifications) {
                    if (data.notifications.length === 0) {
                        notifList.innerHTML = `
                            <div class="notif-item">
                                <div class="notif-content" style="text-align: center; color: #6b7280; width: 100%;">
                                    No new notifications.
                                </div>
                            </div>
                        `;
                    } else {
                        let html = '';
                        data.notifications.forEach(notif => {
                            const d = notif.data || {};
                            let iconSvg = '';
                            if (d.severity_class === 'critical') {
                                iconSvg = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
                            } else if (d.severity_class === 'warning') {
                                iconSvg = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                            } else if (d.severity_class === 'success') {
                                iconSvg = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                            } else {
                                iconSvg = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                            }

                            const unreadClass = notif.read_at === null ? 'unread' : '';
                            
                            let redirectUrl = '/notifications';
                            if (d.action === 'progress_update') {
                                redirectUrl = '{{ auth()->check() && auth()->user()->role === "citizen" ? "/dashboard/maintenance" : "/dashboard" }}';
                            } else if (d.analysis_id) {
                                redirectUrl = `/reports/${d.analysis_id}`;
                            } else if (d.task_id) {
                                redirectUrl = `/dashboard`;
                            }

                            html += `
                                <div class="notif-item ${unreadClass}" onclick="window.location.href='${redirectUrl}'">
                                    <div class="notif-icon ${d.severity_class || 'info'}">
                                        ${iconSvg}
                                    </div>
                                    <div class="notif-content">
                                        <div class="notif-text"><strong>${d.title || 'Alert'}:</strong> ${d.description || ''}</div>
                                        <div class="notif-time">${notif.time_diff}</div>
                                    </div>
                                </div>
                            `;
                        });
                        notifList.innerHTML = html;
                    }
                }
            })
            .catch(err => console.error("Error polling notifications:", err));
    }

    // Poll every 10 seconds
    setInterval(pollNotifications, 10000);
    // Initial check on load
    document.addEventListener('DOMContentLoaded', pollNotifications);
</script>
