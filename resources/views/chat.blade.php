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
        <div class="dashboard-content" style="padding: 0;">
            <!-- AI Assistant Chat Panel Full Screen -->
            <div class="panel ai-assistant" style="height: 100%; border: none; border-radius: 0;">
                <div class="panel-header" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px;">
                    <div class="panel-title" style="font-size: 20px;">RoadHealth AI Assistant</div>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: #FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                
                <div class="chat-box" style="height: 100%; max-height: calc(100vh - 230px); background: transparent; border: none;">
                    <div class="chat-msg">
                        <div class="chat-avatar">AI</div>
                        <div class="chat-bubble ai">Hello {{ explode(' ', auth()->user()->name ?? 'User')[0] }}! I can help you query pavement condition details. Ask me anything.</div>
                    </div>
                </div>
                
                <div class="chat-input-wrap" style="margin-top: 20px;">
                    <input type="text" placeholder="Ask AI Assistant..." style="font-size: 14px; padding: 12px 16px;" />
                    <button style="padding: 12px 24px; font-size: 14px;">Send</button>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ----------------------------------------------------
    // AI CHAT BOT INTERACTION
    // ----------------------------------------------------
    const chatInput = document.querySelector('.chat-input-wrap input');
    const sendBtn = document.querySelector('.chat-input-wrap button');
    const chatBody = document.querySelector('.chat-box');

    function appendMessage(sender, text) {
        const msgDiv = document.createElement('div');
        msgDiv.className = 'chat-msg';
        
        const isUser = sender === 'user';
        
        msgDiv.innerHTML = `
            <div class="chat-avatar${isUser ? ' user' : ''}">
                ${isUser ? 'U' : 'AI'}
            </div>
            <div class="chat-bubble${isUser ? ' user' : ' ai'}">
                ${text}
            </div>
        `;
        chatBody.appendChild(msgDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // CSRF token setup
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    async function handleSend() {
        const text = chatInput.value.trim();
        if (!text) return;
        
        appendMessage('user', text);
        chatInput.value = '';
        
        // Add typing indicator
        const typingId = 'typing-' + Date.now();
        const typingDiv = document.createElement('div');
        typingDiv.className = 'chat-msg';
        typingDiv.id = typingId;
        typingDiv.innerHTML = `
            <div class="chat-avatar">AI</div>
            <div class="chat-bubble ai">Thinking...</div>
        `;
        chatBody.appendChild(typingDiv);
        chatBody.scrollTop = chatBody.scrollHeight;
        
        try {
            const response = await fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({ query: text })
            });
            
            const data = await response.json();
            
            // Remove typing indicator
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();
            
            // Format markdown-like bold (**) if any
            let formattedResponse = (data.response || 'No response.').replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
            appendMessage('ai', formattedResponse);
        } catch (error) {
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();
            appendMessage('ai', 'Error connecting to the AI Assistant. Please ensure the backend is running.');
        }
    }

    if (sendBtn && chatInput) {
        sendBtn.addEventListener('click', handleSend);
        chatInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') handleSend();
        });
    }
});
</script>
@endsection
