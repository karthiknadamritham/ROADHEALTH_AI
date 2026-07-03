@php $hideFooter = true; @endphp
@extends('layouts.app')
@section('title', 'Account Settings')

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
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 10px; }

    /* Main Area styling */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; background: var(--bg-color); }
    .topbar { height: 64px; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; padding: 0 32px; background: #0a0a0a; flex-shrink: 0; gap: 12px; }
    .topbar h2 { color: var(--text-color); font-size: 18px; font-weight: 700; }
    .topbar-sub { color: var(--text-dark-gray); font-size: 13px; }

    .settings-content {
        flex-grow: 1;
        overflow-y: auto;
        padding: 40px;
        background: var(--bg-color);
    }

    .settings-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 36px;
        max-width: 800px;
        margin: 0 auto 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: background-color 0.3s, border-color 0.3s;
    }

    /* Tabs Styling */
    .settings-tabs {
        display: flex;
        gap: 8px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 32px;
        padding-bottom: 2px;
    }

    .tab-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 14px;
        font-weight: 600;
        padding: 12px 16px;
        cursor: pointer;
        position: relative;
        transition: color 0.2s;
    }

    .tab-btn:hover {
        color: var(--text-color);
    }

    .tab-btn.active {
        color: #FFD500;
        font-weight: 700;
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100%;
        height: 3px;
        background: #FFD500;
        border-radius: 2px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Form Fields grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
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

    .section-title {
        font-size: 13px;
        font-weight: 800;
        color: #FFD500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 32px 0 16px 0;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 8px;
    }

    /* Alerts */
    .alert-box {
        border-radius: 8px;
        padding: 14px 18px;
        font-size: 13.5px;
        font-weight: 600;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    .btn-save {
        background: #FFD500;
        color: black;
        border: none;
        border-radius: 8px;
        padding: 14px 28px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-save:hover {
        background: #facc15;
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(255, 213, 0, 0.25);
    }

    /* Light Theme Sidebar & Layout overrides */
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

    <!-- Main Area -->
    <main class="main-area">
        <div class="topbar">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;color:#FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <h2>Account Settings</h2>
            <span class="topbar-sub">— Manage Profile &amp; Preferences</span>
        </div>

        <div class="settings-content">
            <div class="settings-card">
                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert-box alert-success">
                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-box alert-error">
                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 16px; height: 16px;"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            @foreach($errors->all() as $error)
                                <span>{{ $error }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Navigation Tabs -->
                <div class="settings-tabs">
                    <button class="tab-btn active" onclick="switchTab(event, 'profile-tab')">Profile Details</button>
                    <button class="tab-btn" onclick="switchTab(event, 'security-tab')">Security &amp; Password</button>
                    @if(in_array($user->role, ['officer', 'staff']))
                        <button class="tab-btn" onclick="switchTab(event, 'work-tab')">Work Details</button>
                    @endif
                </div>

                <!-- Settings Update Form -->
                <form id="settings-form" method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Tab 1: Profile Details -->
                    <div id="profile-tab" class="tab-content active">
                        <!-- Profile Photo Upload -->
                        <div style="display:flex; align-items:center; gap:20px; margin-bottom:32px;">
                            <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}" 
                                 alt="Avatar Preview" id="avatar-preview" 
                                 style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid var(--border-color); background: #111;">
                            
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                <label class="form-label">Profile Picture</label>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <input type="file" name="profile_photo" id="profile-photo-input" accept="image/*" style="display:none;" onchange="previewAvatar(event)">
                                    <button type="button" class="form-input" onclick="document.getElementById('profile-photo-input').click()" style="width:auto; cursor:pointer; background:rgba(255,255,255,0.05); font-weight:700; padding:8px 16px;">Choose Image</button>
                                    <span id="profile-photo-name" style="font-size:12px; color:var(--text-muted);"></span>
                                </div>
                                <span style="font-size:11px; color:var(--text-dark-gray);">Max size 5MB. Formats: JPG, PNG</span>
                            </div>
                        </div>

                        <!-- General Details Grid -->
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required placeholder="e.g. John Doe">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $user->email) }}" required placeholder="e.g. john@example.com">
                            </div>
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone', $user->phone) }}" required placeholder="e.g. +91 9876543210">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Account Role</label>
                                <input type="text" class="form-input" value="{{ ucfirst($user->role) }}" disabled style="opacity: 0.6; cursor: not-allowed;">
                            </div>
                        </div>

                        <!-- Geographical Jurisdictions -->
                        <h3 class="section-title">Geographical Jurisdiction</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="territory" class="form-label">Territory / Corporation</label>
                                <input type="text" name="territory" id="territory" class="form-input" value="{{ old('territory', $user->territory) }}" required placeholder="e.g. City Corporation">
                            </div>
                            <div class="form-group">
                                <label for="zone" class="form-label">Zone</label>
                                <input type="text" name="zone" id="zone" class="form-input" value="{{ old('zone', $user->zone) }}" required placeholder="e.g. South Zone">
                            </div>
                            <div class="form-group">
                                <label for="ward" class="form-label">Ward</label>
                                <input type="text" name="ward" id="ward" class="form-input" value="{{ old('ward', $user->ward) }}" required placeholder="e.g. Ward 45">
                            </div>
                            <div class="form-group">
                                <label for="area" class="form-label">Area / Locality</label>
                                <input type="text" name="area" id="area" class="form-input" value="{{ old('area', $user->area) }}" required placeholder="e.g. MG Road">
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Security & Password -->
                    <div id="security-tab" class="tab-content">
                        <div class="form-grid">
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="form-input" placeholder="••••••••">
                                <span style="font-size:11px; color:var(--text-dark-gray);">Required only if you wish to change your password.</span>
                            </div>
                            <div class="form-group">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-input" placeholder="••••••••">
                            </div>
                            <div class="form-group">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-input" placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Work Details (conditional) -->
                    @if(in_array($user->role, ['officer', 'staff']))
                        <div id="work-tab" class="tab-content">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="employee_id" class="form-label">Employee ID</label>
                                    <input type="text" name="employee_id" id="employee_id" class="form-input" value="{{ old('employee_id', $user->employee_id) }}" required placeholder="e.g. EMP12345">
                                </div>
                                <div class="form-group">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" name="department" id="department" class="form-input" value="{{ old('department', $user->department) }}" required placeholder="e.g. Roads &amp; Maintenance">
                                </div>
                            </div>

                            <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:24px;">
                                <label class="form-label">Government ID Document</label>
                                @if($user->government_id_path)
                                    <div style="display:flex; align-items:center; gap:12px; background:rgba(255,213,0,0.05); border:1px solid rgba(255,213,0,0.15); border-radius:8px; padding:12px; margin-bottom:12px; max-width: 400px;">
                                        <svg fill="currentColor" viewBox="0 0 20 20" style="width: 20px; height: 20px; color: #FFD500; flex-shrink: 0;"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                                        <div style="flex-grow:1;">
                                            <div style="font-size:12px; font-weight:700; color:var(--text-color);">Current ID Document</div>
                                            <a href="{{ asset('storage/' . $user->government_id_path) }}" target="_blank" style="font-size:11px; color:#FFD500; text-decoration:underline; font-weight:600;">Download / View File</a>
                                        </div>
                                    </div>
                                @endif
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <input type="file" name="government_id" id="government-id-input" accept="image/*,application/pdf" style="display:none;" onchange="updateIdName(event)">
                                    <button type="button" class="form-input" onclick="document.getElementById('government-id-input').click()" style="width:auto; cursor:pointer; background:rgba(255,255,255,0.05); font-weight:700; padding:8px 16px; display:flex; align-items:center; gap:8px;">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px; height:14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Replace Document
                                    </button>
                                    <span id="government-id-name" style="font-size:12px; color:var(--text-muted);"></span>
                                </div>
                                <span style="font-size:11px; color:var(--text-dark-gray);">Max size 10MB. Formats: PDF, JPG, PNG</span>
                            </div>
                        </div>
                    @endif

                    <div style="margin-top:40px; display:flex; justify-content:flex-end; border-top:1px solid var(--border-color); padding-top:24px;">
                        <button type="submit" class="btn-save">
                            Save Changes
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px; height:18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    function switchTab(event, tabId) {
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        // Add active class to clicked button
        event.currentTarget.classList.add('active');

        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        // Show target tab content
        document.getElementById(tabId).classList.add('active');
    }

    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('profile-photo-name').innerText = file.name;
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('avatar-preview');
                output.src = reader.result;
                // Also update matching header avatars if applicable
                document.querySelectorAll('.user-avatar').forEach(img => {
                    img.src = reader.result;
                });
            };
            reader.readAsDataURL(file);
        }
    }

    function updateIdName(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('government-id-name').innerText = file.name;
        }
    }
</script>
@endsection
