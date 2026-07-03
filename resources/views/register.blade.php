@extends('layouts.app')

@section('title', 'Register')

@section('styles')
<style>
    body { background-color: var(--bg-color); color: var(--text-color); height: 100vh; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: background-color 0.3s, color 0.3s; }
    
    .login-container { max-width: 1600px; margin: 0 auto; width: 100%; padding: 0 48px 4vh; display: flex; gap: 4vw; align-items: center; justify-content: space-between; flex-grow: 1; min-height: 0; margin-top: -2vh; }
    
    /* Left Panel: Smarter Onboarding */
    .login-left { flex: 1.1; display: flex; flex-direction: column; gap: 3.5vh; max-width: 720px; min-height: 0; justify-content: center; margin-top: 0; }
    .tag-welcome { display: inline-flex; align-items: center; border: 1px solid rgba(255,213,0,0.3); background: rgba(255,213,0,0.05); border-radius: 20px; color: #FFD500; font-size: min(13px, 1.6vh); font-weight: 700; letter-spacing: 0.05em; padding: 0.6vh 18px; width: max-content; }
    .login-title { font-size: min(65px, 7.5vh); font-weight: 800; line-height: 1.05; letter-spacing: -0.02em; }
    .login-desc { color: #9ca3af; font-size: min(16.5px, 2.0vh); line-height: 1.45; max-width: 580px; }
    
    /* Interactive Onboarding Pipeline Illustration */
    .pipeline-container { position: relative; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); background: #080808; height: 38vh; box-shadow: inset 0 0 40px rgba(0,0,0,0.8); min-height: 260px; padding: 24px; display: flex; flex-direction: column; justify-content: center; gap: 3vh; }
    
    /* Pipeline Step cards */
    .pipeline-step { display: flex; align-items: center; gap: 16px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; padding: 12px 18px; transition: all 0.3s; position: relative; }
    .pipeline-step.active { border-color: #FFD500; background: rgba(255,213,0,0.02); box-shadow: 0 0 15px rgba(255,213,0,0.05); }
    .pipeline-step.completed { border-color: rgba(34,197,94,0.3); background: rgba(34,197,94,0.02); }
    
    .step-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13.5px; font-weight: 800; }
    .pipeline-step.completed .step-icon { background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.3); }
    .pipeline-step.active .step-icon { background: rgba(255,213,0,0.15); color: #FFD500; border: 1px solid #FFD500; animation: pulseGlow 2s infinite; }
    .pipeline-step.pending .step-icon { background: rgba(255,255,255,0.05); color: #6b7280; border: 1px solid rgba(255,255,255,0.1); }
    
    @keyframes pulseGlow {
        0% { box-shadow: 0 0 0 0 rgba(255,213,0,0.4); }
        70% { box-shadow: 0 0 0 8px rgba(255,213,0,0); }
        100% { box-shadow: 0 0 0 0 rgba(255,213,0,0); }
    }
    
    .step-info { display: flex; flex-direction: column; gap: 2px; }
    .step-title { font-size: 14.5px; font-weight: 700; color: white; }
    .step-desc { font-size: 11.5px; color: #9ca3af; }
    
    .step-status { margin-left: auto; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
    .pipeline-step.completed .step-status { color: #22c55e; }
    .pipeline-step.active .step-status { color: #FFD500; }
    .pipeline-step.pending .step-status { color: #6b7280; }
    
    /* Connective pipeline line */
    .pipeline-connector { position: absolute; left: 40px; width: 2px; background: rgba(255,255,255,0.05); z-index: 1; }
    
    /* Testimonial card */
    .testi-card { background: rgba(10,10,10,0.6); border: 1px solid rgba(255,255,255,0.03); border-radius: 10px; padding: 1.4vh 20px; display: flex; flex-direction: column; gap: 1vh; }
    .testi-quote { color: #ffffff; font-size: min(15px, 1.8vh); line-height: 1.45; font-style: italic; display: flex; gap: 8px; }
    .testi-quote svg { width: 18px; height: 18px; color: #FFD500; flex-shrink: 0; }
    .testi-profile { display: flex; align-items: center; justify-content: space-between; }
    .testi-user { display: flex; align-items: center; gap: 12px; }
    .testi-avatar { width: 38px; height: 38px; border-radius: 50%; background: #222; border: 1px solid rgba(255,255,255,0.1); }
    .testi-name { font-size: min(14.5px, 1.7vh); font-weight: 700; color: white; }
    .testi-role { font-size: min(12.5px, 1.5vh); color: #ffffff; margin-top: 1px; }
    
    /* Right Panel: Glassmorphic register Box */
    .login-right { flex: 0.9; max-width: 560px; width: 100%; display: flex; justify-content: flex-end; min-height: 0; margin-top: 0; }
    .login-glass-box { background: var(--card-bg); backdrop-filter: blur(16px); border: 1px solid var(--border-color); border-radius: 16px; width: 100%; padding: min(32px, 3.8vh) 40px; display: flex; flex-direction: column; align-items: center; box-shadow: 0 30px 60px -15px rgba(0,0,0,0.7); gap: 0; min-height: 0; transition: all 0.3s; max-height: 85vh; overflow-y: auto; }
    .login-glass-box::-webkit-scrollbar { width: 6px; }
    .login-glass-box::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
    .login-glass-box::-webkit-scrollbar-thumb { background: rgba(255,213,0,0.3); border-radius: 3px; }
    .login-glass-box::-webkit-scrollbar-thumb:hover { background: rgba(255,213,0,0.6); }
    
    /* Glowing Lock Shield Icon */
    .shield-lock-container { position: relative; width: min(60px, 7.5vh); height: min(60px, 7.5vh); margin-bottom: 1.5vh; display: flex; align-items: center; justify-content: center; }
    .shield-lock-glow { position: absolute; inset: 0; border-radius: 50%; background: radial-gradient(circle, rgba(255,213,0,0.2) 0%, transparent 70%); filter: blur(6px); }
    .shield-lock-icon { width: 100%; height: 100%; color: #FFD500; position: relative; z-index: 10; filter: drop-shadow(0 0 6px rgba(255,213,0,0.4)); }
    
    .login-header-title { font-size: min(24px, 2.8vh); font-weight: 800; letter-spacing: -0.01em; color: var(--text-color); margin-bottom: 4px; text-align: center; }
    .login-header-subtitle { font-size: min(13.5px, 1.6vh); color: var(--text-muted); margin-bottom: min(18px, 2.2vh); text-align: center; }
    
    /* Segmented Role Selector */
    .role-selector { display: flex; width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--border-color); border-radius: 8px; padding: 4px; margin-bottom: 2.2vh; gap: 4px; }
    .role-tab { flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; border-radius: 6px; font-size: 13px; font-weight: 700; color: var(--text-muted); cursor: pointer; transition: all 0.25s ease; user-select: none; }
    .role-tab svg { width: 16px; height: 16px; transition: transform 0.2s; }
    .role-tab:hover { color: var(--text-color); background: rgba(255,255,255,0.02); }
    .role-tab:hover svg { transform: translateY(-1px); }
    .role-tab.active { background: #FFD500; color: black; }
    .role-tab.active svg { color: black; }
    
    /* Inputs */
    .form-group { display: flex; flex-direction: column; gap: 4px; width: 100%; margin-bottom: min(12px, 1.4vh); }
    .form-label-row { display: flex; justify-content: space-between; align-items: center; }
    .form-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
    
    .input-wrapper { position: relative; display: flex; align-items: center; width: 100%; }
    .input-icon-left { position: absolute; left: 16px; width: 18px; height: 18px; color: #6b7280; }
    .input-icon-right { position: absolute; right: 16px; width: 18px; height: 18px; color: #6b7280; cursor: pointer; transition: color 0.2s; }
    .input-icon-right:hover { color: var(--text-color); }
    
    .input-field { width: 100%; background: rgba(0,0,0,0.25); border: 1px solid var(--border-color); border-radius: 6px; padding: 10px 16px 10px 46px; color: var(--text-color); font-size: 13.5px; transition: all 0.2s; outline: none; }
    .input-field:focus { border-color: rgba(255,213,0,0.4); box-shadow: 0 0 10px rgba(255,213,0,0.1); }
    .input-field::placeholder { color: var(--input-placeholder); }
    
    /* Dropdown customization */
    .select-field { appearance: none; -webkit-appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 16px center; background-size: 16px; padding-right: 40px; }
    .select-field option { background: var(--bg-color); color: var(--text-color); }
    
    /* Checkbox options */
    .options-row { display: flex; align-items: center; width: 100%; margin: 4px 0 min(12px, 1.5vh); }
    .checkbox-container { display: flex; align-items: center; gap: 10px; cursor: pointer; font-size: 12.5px; color: var(--text-muted); font-weight: 600; user-select: none; }
    .checkbox-custom { width: 16px; height: 16px; border: 1px solid rgba(255,213,0,0.5); border-radius: 3px; display: flex; align-items: center; justify-content: center; background: var(--checkbox-custom-bg); transition: all 0.2s; flex-shrink: 0; }
    .checkbox-custom svg { width: 10px; height: 10px; color: black; display: none; }
    
    /* Real checkbox hidden */
    .checkbox-hidden { display: none; }
    .checkbox-hidden:checked + .checkbox-custom { background: #FFD500; border-color: #FFD500; }
    .checkbox-hidden:checked + .checkbox-custom svg { display: block; }
    
    /* Button */
    .btn-login { width: 100%; background: #FFD500; color: black; border: none; border-radius: 6px; padding: 12px; font-size: 14.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: background 0.2s; margin-bottom: min(12px, 1.5vh); }
    .btn-login:hover { background: #facc15; }
    .btn-login svg { width: 18px; height: 18px; }
    
    /* Register Subtext */
    .register-sub { font-size: 13px; color: var(--text-muted); display: flex; align-items: center; gap: 6px; margin-top: 4px; }
    .register-sub a { color: #FFD500; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 3px; transition: color 0.2s; }
    .register-sub a:hover { color: #facc15; }
    .register-sub a svg { width: 14px; height: 14px; transition: transform 0.2s; }
    .register-sub a:hover svg { transform: translateX(2px); }
    
    /* Custom thin login footer */
    .login-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.2vh 48px;
        border-top: 1px solid var(--border-color);
        color: var(--text-dark-gray);
        font-size: 11px;
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        background: var(--navbar-bg);
        margin-top: -3vh;
        transition: background-color 0.3s, border-color 0.3s;
    }
    .login-footer-links { display: flex; gap: 16px; }
    .login-footer-links a { color: var(--text-dark-gray); text-decoration: none; transition: color 0.2s; }
    .login-footer-links a:hover { color: var(--text-color); }
    .login-footer-social { display: flex; align-items: center; gap: 12px; }
    .login-footer-social a { color: var(--text-dark-gray); transition: color 0.2s; display: flex; align-items: center; }
    .login-footer-social a:hover { color: var(--text-color); }
    .login-footer-social svg { width: 14px; height: 14px; }
    
    /* Live Validation Feedback Style */
    .validation-success { border-color: #22c55e !important; box-shadow: 0 0 10px rgba(34,197,94,0.15) !important; }
    .email-badge { position: absolute; right: 16px; background: rgba(34,197,94,0.15); color: #22c55e; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 10px; border: 1px solid rgba(34,197,94,0.3); opacity: 0; transform: scale(0.9); transition: all 0.25s ease; pointer-events: none; }
    .email-badge.show { opacity: 1; transform: scale(1); }
</style>
@endsection

@section('content')
<div class="login-container">
    <!-- Left Panel: Municipal Onboarding Info -->
    <div class="login-left">
        <div class="tag-welcome">
            <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px; margin-right: 6px;"><path fill-rule="evenodd" d="M6.267 3.455a.75.75 0 00-.708-.523H4.5a.75.75 0 00-.75.75v3a.75.75 0 00.75.75h1.059a.75.75 0 00.708-.523L6.89 5.385l1.623 1.623a.75.75 0 001.06 0l2.75-2.75a.75.75 0 10-1.06-1.06L9.006 5.44 7.383 3.817a.75.75 0 00-.512-.22l-.604-.142zM4.5 11.25a.75.75 0 00-.75.75v3a.75.75 0 00.75.75h1.059a.75.75 0 00.708-.523l.573-1.58 1.623 1.623a.75.75 0 001.06 0l2.75-2.75a.75.75 0 10-1.06-1.06l-2.244 2.244-1.623-1.623a.75.75 0 00-.512-.22H4.5z" clip-rule="evenodd"/></svg>
            MUNICIPAL PORTAL INITIALIZATION
        </div>
        
        <h1 class="login-title">Empower Your City's Defect Infrastructure.</h1>
        <p class="login-desc">Set up your department account to unlock live pothole scanning, direct contractor scheduling, and AI-powered road degradation analysis.</p>
        
        <!-- Interactive Onboarding Pipeline Illustration -->
        <div class="pipeline-container">
            <!-- Pipeline connector vertical line -->
            <div class="pipeline-connector" style="top: 22%; bottom: 22%;"></div>
            
            <!-- Step 1: Complete -->
            <div class="pipeline-step completed" id="pipeline-step-1">
                <div class="step-icon">
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width: 14px; height: 14px;"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div class="step-info">
                    <span class="step-title">Department Authentication</span>
                    <span class="step-desc">Establish municipal credentials and verify identity parameters</span>
                </div>
                <span class="step-status">Verified</span>
            </div>
            
            <!-- Step 2: Active -->
            <div class="pipeline-step active" id="pipeline-step-2">
                <div class="step-icon">2</div>
                <div class="step-info">
                    <span class="step-title">Jurisdiction Mapping</span>
                    <span class="step-desc" id="pipeline-step-2-desc">Configure geographical boundary settings and ward access keys</span>
                </div>
                <span class="step-status" id="pipeline-step-2-status">Configuring</span>
            </div>
            
            <!-- Step 3: Pending -->
            <div class="pipeline-step pending" id="pipeline-step-3">
                <div class="step-icon">3</div>
                <div class="step-info">
                    <span class="step-title">AI Engine Provisioning</span>
                    <span class="step-desc">Initialize localized neural network weight allocations for road defects</span>
                </div>
                <span class="step-status">Locked</span>
            </div>
        </div>
        

    </div>
    
    <!-- Right: Glassmorphic Account Setup box -->
    <div class="login-right">
        <div class="login-glass-box">
            <div class="shield-lock-container">
                <div class="shield-lock-glow"></div>
                <!-- Rotating key/shield premium setup icon -->
                <svg class="shield-lock-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="#FFD500"/>
                    <circle cx="12" cy="11" r="3" stroke="#FFD500" stroke-width="1.5"/>
                    <path d="M12 14v4" stroke="#FFD500" stroke-width="1.5"/>
                </svg>
            </div>
            
            <h2 class="login-header-title" id="form-header-title">Create Government Account</h2>
            <p class="login-header-subtitle" id="form-header-subtitle">Access professional city-grade pothole analysis tools</p>
            
            <!-- Segmented Role Selector -->
            <div class="role-selector">
                <div class="role-tab active" data-role="officer" id="tab-officer">Officer</div>
                <div class="role-tab" data-role="staff" id="tab-staff">Staff</div>
                <div class="role-tab" data-role="admin" id="tab-admin">Admin</div>
                <div class="role-tab" data-role="citizen" id="tab-citizen">Citizen</div>
            </div>
            
            <form id="register-form" style="width: 100%;" enctype="multipart/form-data">
                @csrf
                <!-- Full Name -->
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <input type="text" id="name" class="input-field" placeholder="Enter your full name" required>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" id="label-email">Government Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <input type="email" id="email" class="input-field" placeholder="Enter your official gov/nic email" required>
                        <span id="email-verification-badge" class="email-badge">Verified Gov Portal</span>
                    </div>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <input type="text" id="phone" class="input-field" placeholder="Enter your phone number" required>
                    </div>
                </div>

                <!-- Employee ID (Officer/Staff only) -->
                <div class="form-group employee-only">
                    <label class="form-label">Employee ID</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0z"></path></svg>
                        <input type="text" id="employee_id" class="input-field" placeholder="Enter your official employee ID">
                    </div>
                </div>

                <!-- Department (Officer/Staff only) -->
                <div class="form-group employee-only">
                    <label class="form-label">Department</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <input type="text" id="department" class="input-field" placeholder="e.g. Roads & Highways">
                    </div>
                </div>

                <!-- Municipality Jurisdiction Select -->
                <div class="form-group">
                    <label class="form-label" id="label-jurisdiction">Municipal Authority / Territory</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <select id="jurisdiction" class="input-field select-field" required>
                            <option value="" disabled selected id="select-jurisdiction-placeholder">Select municipal agency...</option>
                            <option value="Delhi Municipal" id="opt-1">Delhi Municipal</option>
                            <option value="Bengaluru Municipal" id="opt-2">Bengaluru Municipal</option>
                            <option value="Mumbai Municipal" id="opt-3">Mumbai Municipal</option>
                            <option value="Chennai Municipal" id="opt-4">Chennai Municipal</option>
                            <option value="Other Municipal" id="opt-5">Other State Municipal Directorate</option>
                        </select>
                    </div>
                </div>

                <!-- Zone -->
                <div class="form-group">
                    <label class="form-label">Zone</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        <input type="text" id="zone" class="input-field" placeholder="e.g. Central Zone" required>
                    </div>
                </div>

                <!-- Ward -->
                <div class="form-group">
                    <label class="form-label">Ward</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <input type="text" id="ward" class="input-field" placeholder="e.g. Ward 12" required>
                    </div>
                </div>

                <!-- Area -->
                <div class="form-group">
                    <label class="form-label">Area / Locality</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                        <input type="text" id="area" class="input-field" placeholder="e.g. Connaught Place" required>
                    </div>
                </div>

                <!-- Government ID (Officer/Staff only) -->
                <div class="form-group employee-only">
                    <label class="form-label">Government ID Card (Image/PDF)</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <input type="file" id="government_id" class="input-field" style="padding-top: 10px;" accept="image/*,application/pdf">
                    </div>
                </div>

                <!-- Profile Photo (Officer/Staff only) -->
                <div class="form-group employee-only">
                    <label class="form-label">Official Profile Photo (Image)</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <input type="file" id="profile_photo" class="input-field" style="padding-top: 10px;" accept="image/*">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input type="password" id="password" class="input-field" placeholder="Create a strong password" required>
                        <svg id="toggle-password" class="input-icon-right" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input type="password" id="password_confirmation" class="input-field" placeholder="Verify password credentials" required>
                        <svg id="toggle-password-confirm" class="input-icon-right" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                </div>
                
                <!-- Onboarding agreement checkbox -->
                <div class="options-row">
                    <label class="checkbox-container">
                        <input type="checkbox" id="terms" class="checkbox-hidden">
                        <span class="checkbox-custom">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        I agree to Municipal Data Guidelines & Terms
                    </label>
                </div>
                
                <!-- Submit Account Setup button -->
                <button type="submit" class="btn-login" id="btn-submit-register">
                    <span>Initialize Portal Setup</span>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
            
            <div class="register-sub">
                Already registered?
                <a href="{{ url('/login') }}">
                    Log In here
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Custom Thin Copyright Footer -->
<div class="login-footer">
    <div>&copy; 2025 RoadHealth AI. All rights reserved.</div>
    <div class="login-footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Cookie Policy</a>
    </div>
    <div class="login-footer-social">
        <span style="font-weight: 600;">Follow Us</span>
        <!-- LinkedIn Icon -->
        <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
        <!-- Twitter Icon -->
        <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Hide standard layout footer to match screenshot exactly
        const footerElement = document.querySelector('.footer');
        if (footerElement) {
            footerElement.style.display = 'none';
        }
        
        // Hide banner if any
        const bannerElement = document.querySelector('.banner');
        if (bannerElement) {
            bannerElement.style.display = 'none';
        }

        let currentRole = 'officer';

        // Elements to update when switching roles
        const tabOfficer = document.getElementById('tab-officer');
        const tabStaff = document.getElementById('tab-staff');
        const tabAdmin = document.getElementById('tab-admin');
        const tabCitizen = document.getElementById('tab-citizen');
        
        const leftTitle = document.querySelector('.login-left .login-title');
        const leftDesc = document.querySelector('.login-left .login-desc');
        
        const step1 = document.getElementById('pipeline-step-1');
        const step1Title = step1 ? step1.querySelector('.step-title') : null;
        const step1Desc = step1 ? step1.querySelector('.step-desc') : null;
        
        const step2 = document.getElementById('pipeline-step-2');
        const step2Title = step2 ? step2.querySelector('.step-title') : null;
        const step2Desc = document.getElementById('pipeline-step-2-desc');
        const step2Status = document.getElementById('pipeline-step-2-status');
        
        const step3 = document.getElementById('pipeline-step-3');
        const step3Title = step3 ? step3.querySelector('.step-title') : null;
        const step3Desc = step3 ? step3.querySelector('.step-desc') : null;
        const step3Status = step3 ? step3.querySelector('.step-status') : null;

        const formTitle = document.getElementById('form-header-title');
        const formSubtitle = document.getElementById('form-header-subtitle');
        
        const emailLabel = document.getElementById('label-email');
        const emailInput = document.getElementById('email');
        const emailBadge = document.getElementById('email-verification-badge');
        
        const jurisdictionLabel = document.getElementById('label-jurisdiction');
        const jurisdictionSelect = document.getElementById('jurisdiction');
        const jurisdictionPlaceholder = document.getElementById('select-jurisdiction-placeholder');
        
        const opt1 = document.getElementById('opt-1');
        const opt2 = document.getElementById('opt-2');
        const opt3 = document.getElementById('opt-3');
        const opt4 = document.getElementById('opt-4');
        const opt5 = document.getElementById('opt-5');
        
        const submitBtn = document.getElementById('btn-submit-register');
        const submitBtnSpan = submitBtn ? submitBtn.querySelector('span') : null;

        // Function to perform role switch styling and text replacements
        function switchRole(role) {
            currentRole = role;
            
            // Helper function to safely update text
            const safeUpdateText = (el, text) => {
                if (el) el.innerText = text;
            };
            
            // Helper function to safely set attribute
            const safeSetAttr = (el, attr, val) => {
                if (el) el.setAttribute(attr, val);
            };

            const employeeFields = document.querySelectorAll('.employee-only');
            const employeeIdInput = document.getElementById('employee_id');
            const departmentInput = document.getElementById('department');
            const govIdInput = document.getElementById('government_id');
            const photoInput = document.getElementById('profile_photo');

            if (role === 'officer' || role === 'staff') {
                employeeFields.forEach(el => el.style.display = 'flex');
                if (employeeIdInput) employeeIdInput.required = true;
                if (departmentInput) departmentInput.required = true;
                if (govIdInput) govIdInput.required = true;
                if (photoInput) photoInput.required = true;
            } else {
                employeeFields.forEach(el => el.style.display = 'none');
                if (employeeIdInput) { employeeIdInput.required = false; employeeIdInput.value = ''; }
                if (departmentInput) { departmentInput.required = false; departmentInput.value = ''; }
                if (govIdInput) { govIdInput.required = false; govIdInput.value = ''; }
                if (photoInput) { photoInput.required = false; photoInput.value = ''; }
            }

            if (role === 'citizen') {
                // Update tabs
                if (tabOfficer) tabOfficer.classList.remove('active');
                if (tabStaff) tabStaff.classList.remove('active');
                if (tabAdmin) tabAdmin.classList.remove('active');
                if (tabCitizen) tabCitizen.classList.add('active');
                
                // Left Panel Text
                safeUpdateText(leftTitle, "Empower Your Local Neighborhood.");
                safeUpdateText(leftDesc, "Set up your community account to report road hazards, track civic repair timelines, and help build safer streets.");
                
                // Form Headers
                safeUpdateText(formTitle, "Create Citizen Account");
                safeUpdateText(formSubtitle, "Access public road health report portals");
                
                safeUpdateText(emailLabel, "Email Address");
                safeSetAttr(emailInput, 'placeholder', "Enter your email address");
                
                safeUpdateText(submitBtnSpan, "Initialize Citizen Portal");
                
            } else {
                // Update tabs
                if (tabCitizen) tabCitizen.classList.remove('active');
                if (tabOfficer) tabOfficer.classList.remove('active');
                if (tabStaff) tabStaff.classList.remove('active');
                if (tabAdmin) tabAdmin.classList.remove('active');
                
                if (role === 'officer') { if (tabOfficer) tabOfficer.classList.add('active'); }
                if (role === 'staff') { if (tabStaff) tabStaff.classList.add('active'); }
                if (role === 'admin') { if (tabAdmin) tabAdmin.classList.add('active'); }
                
                // Left Panel Text
                safeUpdateText(leftTitle, "Empower Your City's Defect Infrastructure.");
                safeUpdateText(leftDesc, "Set up your department account to unlock live pothole scanning, direct contractor scheduling, and AI-powered road degradation analysis.");
                
                // Form Headers
                safeUpdateText(formTitle, "Create " + role.charAt(0).toUpperCase() + role.slice(1) + " Account");
                safeUpdateText(formSubtitle, "Access professional city-grade pothole analysis tools");
                
                safeUpdateText(emailLabel, "Government Email Address");
                safeSetAttr(emailInput, 'placeholder', "Enter your official gov/nic email");
                
                safeUpdateText(submitBtnSpan, "Initialize Portal Setup");
            }
            
            // Re-trigger email validation check on role change
            triggerEmailValidation();
        }

        // Initialize defaults
        switchRole('officer');

        tabOfficer.addEventListener('click', () => switchRole('officer'));
        tabStaff.addEventListener('click', () => switchRole('staff'));
        tabAdmin.addEventListener('click', () => switchRole('admin'));
        tabCitizen.addEventListener('click', () => switchRole('citizen'));

        function triggerEmailValidation() {
            const val = emailInput.value.toLowerCase();
            if (val === '') {
                emailInput.classList.remove('validation-success');
                emailBadge.classList.remove('show');
                return;
            }
            
            if (currentRole !== 'citizen') {
                if (val.endsWith('.gov') || val.endsWith('.gov.in') || val.endsWith('.nic') || val.endsWith('.nic.in') || val.endsWith('roadhealth.ai') || val.endsWith('.org')) {
                    emailInput.classList.add('validation-success');
                    emailBadge.innerText = "Verified Gov Portal";
                    emailBadge.style.color = "#22c55e";
                    emailBadge.style.borderColor = "rgba(34,197,94,0.3)";
                    emailBadge.style.background = "rgba(34,197,94,0.15)";
                    emailBadge.classList.add('show');
                } else {
                    emailInput.classList.remove('validation-success');
                    emailBadge.classList.remove('show');
                }
            } else {
                // Citizen validates simple email regex
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(val)) {
                    emailInput.classList.add('validation-success');
                    emailBadge.innerText = "Verified Citizen Profile";
                    emailBadge.style.color = "#60a5fa"; // Elegant blue for citizen
                    emailBadge.style.borderColor = "rgba(96,165,250,0.3)";
                    emailBadge.style.background = "rgba(96,165,250,0.15)";
                    emailBadge.classList.add('show');
                } else {
                    emailInput.classList.remove('validation-success');
                    emailBadge.classList.remove('show');
                }
            }
        }

        // Live email validation
        emailInput.addEventListener('input', triggerEmailValidation);

        // Password toggling visibility
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('toggle-password');
        
        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('text-white');
        });

        // Confirm Password toggling visibility
        const confirmInput = document.getElementById('password_confirmation');
        const toggleConfirm = document.getElementById('toggle-password-confirm');
        
        toggleConfirm.addEventListener('click', () => {
            const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmInput.setAttribute('type', type);
            toggleConfirm.classList.toggle('text-white');
        });

        // Form Onboarding Simulation with dynamic loading steps for both roles
        const registerForm = document.getElementById('register-form');
        
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Explicit terms check to prevent silent browser aborts due to hidden elements
            const termsChecked = document.getElementById('terms').checked;
            if (!termsChecked) {
                alert('You must agree to the Municipal Data Guidelines & Terms to proceed.');
                return;
            }
            
            // Password match check
            if (passwordInput.value !== confirmInput.value) {
                alert('Passwords do not match. Please verify credentials.');
                return;
            }
            
            // Set Loading state
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.75';
            submitBtn.style.cursor = 'not-allowed';
            
            submitBtnSpan.innerText = currentRole !== 'citizen' ? 'Provisioning System...' : 'Connecting Community Feed...';
            submitBtn.querySelector('svg').outerHTML = `
                <svg class="animate-spin" viewBox="0 0 24 24" style="animation: spin 1s linear infinite; width: 18px; height: 18px; color: black;" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" style="opacity: 0.25;"></circle>
                    <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;
            
            // Step 2 Transition animation
            setTimeout(() => {
                if (currentRole !== 'citizen') {
                    submitBtnSpan.innerText = 'Jurisdiction Mapping...';
                    step2Desc.innerText = 'Geographical coordinates mapped. Municipal authority Ward APIs connected.';
                    step2Status.innerText = 'Mapped';
                } else {
                    submitBtnSpan.innerText = 'Mapping District Ward...';
                    step2Desc.innerText = 'Citizen neighborhood radius configured. Ward feed subscription synchronized.';
                    step2Status.innerText = 'Mapped';
                }
                
                step2.classList.remove('active');
                step2.classList.add('completed');
                step2.querySelector('.step-icon').innerHTML = `
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width: 14px; height: 14px;"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                `;
                
                // Active Step 3
                step3.classList.remove('pending');
                step3.classList.add('active');
                step3.querySelector('.step-icon').innerText = '3';
                step3.querySelector('.step-icon').style.border = '1px solid #FFD500';
                step3.querySelector('.step-icon').style.color = '#FFD500';
                step3.querySelector('.step-icon').style.background = 'rgba(255,213,0,0.15)';
                step3.querySelector('.step-icon').style.animation = 'pulseGlow 2s infinite';
                step3Status.innerText = 'Provisioning';
                step3Status.style.color = '#FFD500';
            }, 1200);
 
            // Step 3 Allocation
            setTimeout(() => {
                if (currentRole !== 'citizen') {
                    submitBtnSpan.innerText = 'Allocating AI Weight Arrays...';
                    step3Desc.innerText = 'Localized road defect weights allocated and live cache synchronized.';
                } else {
                    submitBtnSpan.innerText = 'Opening Public Portal...';
                    step3Desc.innerText = 'Public hazard reporting access key generated. Civic dashboards provisioned.';
                }
                
                step3Status.innerText = 'Ready';
                step3Status.style.color = '#22c55e';
                step3.classList.remove('active');
                step3.classList.add('completed');
                
                step3.querySelector('.step-icon').innerHTML = `
                    <svg viewBox="0 0 20 20" fill="currentColor" style="width: 14px; height: 14px;"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                `;
                step3.querySelector('.step-icon').style.animation = 'none';
                step3.querySelector('.step-icon').style.background = 'rgba(34,197,94,0.15)';
                step3.querySelector('.step-icon').style.color = '#22c55e';
                step3.querySelector('.step-icon').style.border = '1px solid rgba(34,197,94,0.3)';
            }, 2400);
 
            // Final Submit and redirect
            setTimeout(() => {
                submitBtnSpan.innerText = 'Onboarding complete! Redirecting...';
                
                // Construct FormData for file upload support
                const formData = new FormData();
                formData.append('name', document.getElementById('name').value);
                formData.append('email', emailInput.value);
                formData.append('phone', document.getElementById('phone').value);
                formData.append('territory', jurisdictionSelect.value);
                formData.append('zone', document.getElementById('zone').value);
                formData.append('ward', document.getElementById('ward').value);
                formData.append('area', document.getElementById('area').value);
                formData.append('password', passwordInput.value);
                formData.append('password_confirmation', confirmInput.value);
                formData.append('role', currentRole);

                if (currentRole === 'officer' || currentRole === 'staff') {
                    formData.append('employee_id', document.getElementById('employee_id').value);
                    formData.append('department', document.getElementById('department').value);
                    const govId = document.getElementById('government_id').files[0];
                    const photo = document.getElementById('profile_photo').files[0];
                    if (govId) formData.append('government_id', govId);
                    if (photo) formData.append('profile_photo', photo);
                }
                
                const csrfTokenEl = document.querySelector('meta[name="csrf-token"]') || document.querySelector('input[name="_token"]');
                const csrfToken = csrfTokenEl ? (csrfTokenEl.getAttribute('content') || csrfTokenEl.value) : '{{ csrf_token() }}';
                formData.append('_token', csrfToken);
                
                fetch("{{ url('/register') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    credentials: 'same-origin',
                    body: formData
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(errData => { throw errData; });
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = "{{ url('/dashboard') }}";
                    }
                })
                .catch(err => {
                    // Revert button state
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                    submitBtnSpan.innerText = currentRole === 'citizen' ? 'Initialize Citizen Portal' : 'Initialize Portal Setup';
                    submitBtn.querySelector('svg').outerHTML = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>`;
                    
                    if (err.message && (err.message.includes('CSRF') || err.message.includes('token') || err.message.includes('mismatch'))) {
                        alert('Your session has expired or the security token is invalid. The page will now reload to refresh your session.');
                        window.location.reload();
                    } else if (err.errors) {
                        // Laravel validation errors
                        const firstError = Object.values(err.errors)[0][0];
                        alert('Registration Failed: ' + firstError);
                    } else {
                        alert(err.message || 'An error occurred during registration.');
                    }
                });
            }, 3000);
        });
    });
</script>

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection
