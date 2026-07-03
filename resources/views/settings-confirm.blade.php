@php $hideFooter = true; @endphp
@extends('layouts.app')
@section('title', 'Security Verification')

@section('styles')
<style>
    body { background: #050505; overflow: hidden; }
    .settings-layout { display: flex; height: calc(100vh - 73px); }
    
    /* Sidebar styling matching dashboard */
    .sidebar { width: 220px; min-width: 220px; background: #0a0a0a; border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-nav { padding: 12px 10px; flex-grow: 1; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.12); color: #FFD500; font-weight: 700; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-badge { margin-left: auto; background: #FFD500; color: black; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 10px; }
    .nav-badge.danger { background: #ef4444; color: white; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 10px; }

    /* Main content styling */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; background: var(--bg-color); }
    .topbar { height: 64px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; padding: 0 32px; background: #0a0a0a; flex-shrink: 0; gap: 12px; }
    .topbar h2 { color: var(--text-color); font-size: 18px; font-weight: 700; }
    .topbar-sub { color: var(--text-dark-gray); font-size: 13px; }

    .confirm-container {
        flex-grow: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        background: var(--bg-color);
    }

    .confirm-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 40px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        display: flex;
        flex-direction: column;
        gap: 24px;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: background-color 0.3s, border-color 0.3s;
    }

    .confirm-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(255, 213, 0, 0.1);
        border: 1px solid rgba(255, 213, 0, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        color: #FFD500;
    }

    .confirm-icon-wrap svg {
        width: 28px;
        height: 28px;
    }

    .confirm-header {
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .confirm-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-color);
    }

    .confirm-desc {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.5;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-input {
        width: 100%;
        background: var(--input-bg);
        border: 1px solid var(--input-border);
        border-radius: 8px;
        padding: 12px 16px;
        color: var(--text-color);
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        font-family: 'Inter', sans-serif;
    }

    .form-input:focus {
        border-color: rgba(255, 213, 0, 0.4);
    }

    .form-input::placeholder {
        color: var(--input-placeholder);
    }

    .btn-submit {
        width: 100%;
        background: #FFD500;
        color: black;
        border: none;
        border-radius: 8px;
        padding: 14px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        letter-spacing: 0.02em;
    }

    .btn-submit:hover {
        background: #facc15;
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255, 213, 0, 0.25);
    }

    .error-alert {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 8px;
        padding: 12px 16px;
        color: #ef4444;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Light Theme Sidebar & Layout overrides matching rest of app */
    html.light-theme .sidebar {
        background: #ffffff !important;
        border-right: 1px solid rgba(0, 0, 0, 0.08) !important;
    }
    html.light-theme .topbar {
        background: #ffffff !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
    }
    html.light-theme .nav-item {
        color: #4b5563 !important;
    }
    html.light-theme .nav-item:hover {
        background: rgba(0, 0, 0, 0.04) !important;
        color: #111827 !important;
    }
    html.light-theme .nav-item.active {
        background: rgba(255, 213, 0, 0.15) !important;
        color: #b28000 !important;
    }
    html.light-theme .nav-divider {
        background: rgba(0, 0, 0, 0.08) !important;
    }
</style>
@endsection

@section('content')
<div class="settings-layout">
    <!-- Sidebar Nav -->
    @include('partials.sidebar')

    <!-- Main Content Area -->
    <main class="main-area">
        <div class="topbar">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;color:#FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <h2>Security Access Lock</h2>
            <span class="topbar-sub">— Verification Required</span>
        </div>

        <div class="confirm-container">
            <div class="confirm-card">
                <div class="confirm-icon-wrap">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>

                <div class="confirm-header">
                    <h3 class="confirm-title">Confirm Your Password</h3>
                    <p class="confirm-desc">To access settings and edit your profile details, please verify your identity by entering your password.</p>
                </div>

                @if($errors->any())
                    <div class="error-alert">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('settings.confirm') }}">
                    @csrf
                    <div style="display:flex; flex-direction:column; gap:20px;">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required autofocus autocomplete="current-password">
                        </div>

                        <button type="submit" class="btn-submit">
                            Unlock Settings
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
