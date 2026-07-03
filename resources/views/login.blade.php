@extends('layouts.app')

@section('title', 'Log In')

@section('styles')
<style>
    body { background-color: var(--bg-color); color: var(--text-color); height: 100vh; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; transition: background-color 0.3s, color 0.3s; }
    
    .login-container { max-width: 1600px; margin: 0 auto; width: 100%; padding: 0 48px 4vh; display: flex; gap: 4vw; align-items: center; justify-content: space-between; flex-grow: 1; min-height: 0; margin-top: 0vh; }
    
    /* Left Panel: Smarter Insights */
    .login-left { flex: 1.1; display: flex; flex-direction: column; gap: 3.5vh; max-width: 720px; min-height: 0; justify-content: center; margin-top: 0; }
    .tag-welcome { display: inline-flex; align-items: center; border: 1px solid rgba(255,213,0,0.3); background: rgba(255,213,0,0.05); border-radius: 20px; color: #FFD500; font-size: min(13px, 1.6vh); font-weight: 700; letter-spacing: 0.05em; padding: 0.6vh 18px; width: max-content; }
    .login-title { font-size: min(65px, 7.5vh); font-weight: 800; line-height: 1.05; letter-spacing: -0.02em; }
    .login-desc { color: #9ca3af; font-size: min(16.5px, 2.0vh); line-height: 1.45; max-width: 580px; }
    
    /* Image Defect Overlay Illustration */
    .defect-overlay-container { position: relative; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05); background: #080808; height: 38vh; box-shadow: inset 0 0 40px rgba(0,0,0,0.8); min-height: 260px; }
    .defect-road-img { width: 100%; height: 100%; object-fit: cover; opacity: 0.35; filter: contrast(1.1) brightness(0.8); }
    
    /* Dynamic Callouts */
    .defect-callout { position: absolute; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); border-radius: 5px; padding: 8px 12px; display: flex; flex-direction: column; gap: 1px; box-shadow: 0 4px 20px rgba(0,0,0,0.5); z-index: 10; }
    .callout-title { font-size: 11px; font-weight: 800; color: #FFD500; letter-spacing: 0.05em; }
    .callout-sub { font-size: 10px; color: #9ca3af; }
    
    /* Specific Callout Classes */
    .callout-patch { top: 55%; left: 6%; border: 1px solid rgba(255,255,255,0.1); }
    .callout-pothole { top: 32%; left: 22%; border: 1px solid #FFD500; }
    .callout-crack { top: 50%; right: 28%; border: 1px solid rgba(255,255,255,0.1); }
    
    /* Lines */
    .callout-line { position: absolute; background: rgba(255,255,255,0.2); }
    .line-patch { top: 62%; left: 16%; width: 40px; height: 1px; }
    .line-pothole { top: 44%; left: 34%; width: 40px; height: 1px; background: #FFD500; box-shadow: 0 0 6px #FFD500; }
    .line-crack { top: 56%; right: 38%; width: 40px; height: 1px; }

    /* Yellow Circle Target scanner */
    .scanner-circle { position: absolute; border: 2.2px solid #FFD500; border-radius: 50%; width: 120px; height: 72px; top: 46%; left: 20%; background: rgba(255,213,0,0.02); transform: translate(-50%, -50%) rotateX(45deg); box-shadow: 0 0 20px rgba(255,213,0,0.3); border-style: dashed; animation: targetPulse 2.5s infinite linear; }
    @keyframes targetPulse {
        0% { transform: translate(-50%, -50%) rotateX(45deg) scale(0.95); opacity: 0.7; }
        50% { transform: translate(-50%, -50%) rotateX(45deg) scale(1.05); opacity: 1; }
        100% { transform: translate(-50%, -50%) rotateX(45deg) scale(0.95); opacity: 0.7; }
    }

    /* Testimonial card */
    .testi-card { background: rgba(10,10,10,0.6); border: 1px solid rgba(255,255,255,0.03); border-radius: 10px; padding: 1.4vh 20px; display: flex; flex-direction: column; gap: 1vh; }
    .testi-quote { color: #ffffff; font-size: min(15px, 1.8vh); line-height: 1.45; font-style: italic; display: flex; gap: 8px; }
    .testi-quote svg { width: 18px; height: 18px; color: #FFD500; flex-shrink: 0; }
    .testi-profile { display: flex; align-items: center; justify-content: space-between; }
    .testi-user { display: flex; align-items: center; gap: 12px; }
    .testi-avatar { width: 38px; height: 38px; border-radius: 50%; background: #222; border: 1px solid rgba(255,255,255,0.1); }
    .testi-name { font-size: min(14.5px, 1.7vh); font-weight: 700; color: white; }
    .testi-role { font-size: min(12.5px, 1.5vh); color: #ffffff; margin-top: 1px; }
    .testi-stars { display: flex; gap: 2px; color: #FFD500; }
    .testi-stars svg { width: 12px; height: 12px; }

    /* Right Panel: Glassmorphic Log In Box */
    .login-right { flex: 0.9; max-width: 560px; width: 100%; display: flex; justify-content: flex-end; min-height: 0; margin-top: 0; }
    .login-glass-box { background: var(--card-bg); backdrop-filter: blur(16px); border: 1px solid var(--border-color); border-radius: 16px; width: 100%; padding: min(44px, 4.8vh) 40px; display: flex; flex-direction: column; align-items: center; box-shadow: 0 30px 60px -15px rgba(0,0,0,0.7); gap: 0; min-height: 0; transition: all 0.3s; }
    
    /* Glowing Lock Shield Icon */
    .shield-lock-container { position: relative; width: min(80px, 9.2vh); height: min(80px, 9.2vh); margin-bottom: 2vh; display: flex; align-items: center; justify-content: center; }
    .shield-lock-glow { position: absolute; inset: 0; border-radius: 50%; background: radial-gradient(circle, rgba(255,213,0,0.2) 0%, transparent 70%); filter: blur(6px); }
    .shield-lock-icon { width: 100%; height: 100%; color: #FFD500; position: relative; z-index: 10; filter: drop-shadow(0 0 6px rgba(255,213,0,0.4)); }
    
    .login-header-title { font-size: min(28px, 3.2vh); font-weight: 800; letter-spacing: -0.01em; color: var(--text-color); margin-bottom: 5px; text-align: center; }
    .login-header-subtitle { font-size: min(15px, 1.8vh); color: var(--text-muted); margin-bottom: min(24px, 2.8vh); text-align: center; }
    
    /* Inputs */
    .form-group { display: flex; flex-direction: column; gap: 6px; width: 100%; margin-bottom: min(18px, 2vh); }
    .form-label-row { display: flex; justify-content: space-between; align-items: center; }
    .form-label { font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
    .form-forgot { font-size: 12px; font-weight: 700; color: #FFD500; text-decoration: none; }
    .form-forgot:hover { text-decoration: underline; }
    
    .input-wrapper { position: relative; display: flex; align-items: center; width: 100%; }
    .input-icon-left { position: absolute; left: 18px; width: 20px; height: 20px; color: #6b7280; }
    .input-icon-right { position: absolute; right: 18px; width: 20px; height: 20px; color: #6b7280; cursor: pointer; transition: color 0.2s; }
    .input-icon-right:hover { color: var(--text-color); }
    
    .input-field { width: 100%; background: var(--input-bg); border: 1px solid var(--input-border); border-radius: 6px; padding: 13px 14px 13px 48px; color: var(--text-color); font-size: 14.5px; outline: none; transition: all 0.2s; }
    .input-field:focus { border-color: rgba(255,213,0,0.4); box-shadow: 0 0 10px rgba(255,213,0,0.1); }
    .input-field::placeholder { color: var(--input-placeholder); }
    
    /* Checkbox options */
    .options-row { display: flex; align-items: center; width: 100%; margin: 8px 0 min(18px, 2vh); }
    .checkbox-container { display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 13.5px; color: var(--text-muted); font-weight: 600; user-select: none; }
    .checkbox-custom { width: 18px; height: 18px; border: 1px solid rgba(255,213,0,0.5); border-radius: 3px; display: flex; align-items: center; justify-content: center; background: var(--checkbox-custom-bg); transition: all 0.2s; }
    .checkbox-custom svg { width: 12px; height: 12px; color: black; display: none; }
    
    /* Real checkbox hidden */
    .checkbox-hidden { display: none; }
    .checkbox-hidden:checked + .checkbox-custom { background: #FFD500; border-color: #FFD500; }
    .checkbox-hidden:checked + .checkbox-custom svg { display: block; }
    
    /* Button */
    .btn-login { width: 100%; background: #FFD500; color: black; border: none; border-radius: 6px; padding: 14px; font-size: 15px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: background 0.2s; margin-bottom: min(18px, 2vh); }
    .btn-login:hover { background: #facc15; }
    .btn-login svg { width: 20px; height: 20px; }
    
    /* Divider */
    .login-divider { display: flex; align-items: center; width: 100%; margin-bottom: min(18px, 2vh); }
    .divider-line { flex-grow: 1; height: 1px; background: var(--border-color); }
    .divider-text { color: var(--text-muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; padding: 0 14px; }
    
    /* Social Logins */
    .social-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; width: 100%; margin-bottom: min(22px, 2.5vh); }
    .btn-social { background: var(--btn-social-bg); border: 1px solid var(--btn-social-border); border-radius: 6px; padding: 12px; display: flex; align-items: center; justify-content: center; gap: 10px; color: var(--btn-social-color); font-size: 13.5px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-social:hover { border-color: rgba(255,255,255,0.15); background: var(--btn-outline-hover-bg); color: var(--text-color); }
    .btn-social svg { width: 18px; height: 18px; }
    
    /* Register Subtext */
    .register-sub { font-size: 13.5px; color: var(--text-muted); font-weight: 500; display: flex; gap: 4px; }
    .register-sub a { color: #FFD500; font-weight: 700; display: flex; align-items: center; gap: 4px; }
    .register-sub a:hover { text-decoration: underline; }
    .register-sub a svg { width: 11px; height: 11px; }
    
    /* Enterprise Bottom Badges */
    .badge-section { background: var(--navbar-bg); border-top: 1px solid var(--border-color); width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; margin-top: 0; transition: background-color 0.3s, border-color 0.3s; }
    .badge-container { max-width: 1600px; margin: 0 auto; padding: 1.5vh 48px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    .badge-box { display: flex; align-items: center; gap: 12px; border-right: 1px solid var(--border-color); padding-right: 8px; }
    .badge-box:last-child { border-right: none; }
    .badge-icon-box { color: #FFD500; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; }
    .badge-icon-box svg { width: 18px; height: 18px; }
    .badge-text-box { display: flex; flex-direction: column; gap: 1px; }
    .badge-title { font-size: 11px; font-weight: 700; color: var(--text-color); }
    .badge-desc { font-size: 9px; color: var(--text-dark-gray); }
    
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
    .login-footer-links {
        display: flex;
        gap: 16px;
    }
    .login-footer-links a {
        color: var(--text-dark-gray);
        transition: color 0.2s;
    }
    .login-footer-links a:hover {
        color: #FFD500;
    }
    .login-footer-social {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .login-footer-social a {
        color: var(--text-dark-gray);
        transition: color 0.2s;
    }
    .login-footer-social a:hover {
        color: #FFD500;
    }
    .login-footer-social svg {
        width: 14px;
        height: 14px;
    }
    /* Role Selector */
    .role-selector { display: flex; gap: 8px; margin-bottom: 24px; width: 100%; background: rgba(255,255,255,0.03); padding: 4px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.05); }
    .role-btn { flex: 1; text-align: center; padding: 8px; font-size: 11px; font-weight: 700; color: #9ca3af; cursor: pointer; border-radius: 6px; transition: all 0.2s; border: 1px solid transparent; }
    .role-btn.active { background: rgba(255,213,0,0.1); color: #FFD500; border-color: rgba(255,213,0,0.3); }
    .role-btn:hover:not(.active) { color: white; background: rgba(255,255,255,0.05); }
</style>
@endsection

@section('content')
<div class="login-container">
    <!-- Left: Smarter Insights & Defect Illustration -->
    <div class="login-left">
        <h1 class="login-title">Smarter Insights.<br><span style="color: #FFD500;">Safer Roads.</span></h1>
        <p class="login-desc">RoadHealth AI uses advanced artificial intelligence to detect road defects, assess conditions, and help you make data-driven maintenance decisions.</p>
        

        <!-- Interactive Defect Overlays superimposed on road -->
        <div class="defect-overlay-container">
            <img src="/images/road-bg.png" class="defect-road-img" alt="Road scanning preview">
            
            <!-- Pothole scanning circle target -->
            <div class="scanner-circle"></div>
            
            <!-- Patch callout -->
            <div class="defect-callout callout-patch">
                <span class="callout-title">PATCH</span>
                <span class="callout-sub">Severity: Low</span>
            </div>
            <div class="callout-line line-patch"></div>
            
            <!-- Pothole callout -->
            <div class="defect-callout callout-pothole">
                <span class="callout-title" style="color:#FFD500;">POTHOLE</span>
                <span class="callout-sub" style="color:white; font-weight:700;">Severity: High</span>
            </div>
            <div class="callout-line line-pothole"></div>
            
            <!-- Crack callout -->
            <div class="defect-callout callout-crack">
                <span class="callout-title">CRACK</span>
                <span class="callout-sub">Severity: Medium</span>
            </div>
            <div class="callout-line line-crack"></div>
        </div>
        
        <!-- Testimonial citation card -->
        <div class="testi-card">
            <div class="testi-quote">
                <svg fill="currentColor" viewBox="0 0 24 24"><path d="M13 14.725c0-5.141 3.892-10.519 10-11.725l.944 2c-3.077 1.183-4.944 3.42-4.944 5.211.944.062 2 .875 2 2.789 0 2.1-1.575 4-4 4-2.425 0-4-2.125-4-4.275zm-13 0c0-5.141 3.892-10.519 10-11.725l.944 2c-3.077 1.183-4.944 3.42-4.944 5.211.944.062 2 .875 2 2.789 0 2.1-1.575 4-4 4-2.43 0-4-2.125-4-4.275z"/></svg>
                RoadHealth AI has completely transformed how we monitor and maintain our road infrastructure.
            </div>
            <div class="testi-profile">
                <div class="testi-user">
                    <img src="https://ui-avatars.com/api/?name=Arjun+Verma&background=random" alt="Arjun Verma" class="testi-avatar">
                    <div>
                        <div class="testi-name">Arjun Verma</div>
                        <div class="testi-role">Municipal Engineer</div>
                    </div>
                </div>
                <div class="testi-stars">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right: High-tech Shield Lock box -->
    <div class="login-right">
        <div class="login-glass-box">
            <div class="shield-lock-container" style="width: auto; height: auto; margin-bottom: 2vh;">
                <img src="{{ asset('images/logo.png') }}" alt="RoadHealth AI Logo" style="height: 70px; width: auto; object-fit: contain; filter: drop-shadow(0 0 8px rgba(255,213,0,0.3));">
            </div>
            
            <h2 class="login-header-title">Log In to Your Account</h2>
            <p class="login-header-subtitle">Access your dashboard and continue your journey</p>
            

            <!-- Login Page Role Selector -->
            <div class="role-selector">
                <div class="role-btn active" onclick="selectRole('citizen')">Citizen</div>
                <div class="role-btn" onclick="selectRole('staff')">Gov Staff</div>
                <div class="role-btn" onclick="selectRole('officer')">Gov Officer</div>
                <div class="role-btn" onclick="selectRole('admin')">Admin</div>
            </div>
            
            <form id="login-form" style="width: 100%;">
                @csrf
                <!-- Email field -->
                <div class="form-group">
                    <div class="form-label-row">
                        <label class="form-label">Email Address</label>
                    </div>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <input type="email" id="email" class="input-field" placeholder="Enter your email" required>
                    </div>
                </div>
                
                <!-- Password field -->
                <div class="form-group">
                    <div class="form-label-row">
                        <label class="form-label">Password</label>
                        <a href="#" class="form-forgot" onclick="alert('Password reset link sent to your registered government email address.');">Forgot Password?</a>
                    </div>
                    <div class="input-wrapper">
                        <svg class="input-icon-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <input type="password" id="password" class="input-field" placeholder="Enter your password" required>
                        <svg id="toggle-password" class="input-icon-right" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                </div>
                
                <!-- Remember me checkbox -->
                <div class="options-row">
                    <label class="checkbox-container">
                        <input type="checkbox" class="checkbox-hidden" checked>
                        <span class="checkbox-custom">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                        Remember Me
                    </label>
                </div>
                
                <!-- Log In button -->
                <button type="submit" class="btn-login">
                    Log In
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
            
            <div class="login-divider">
                <div class="divider-line"></div>
                <div class="divider-text">or continue with</div>
                <div class="divider-line"></div>
            </div>
            
            <!-- Social logins -->
            <div class="social-row">
                <button class="btn-social" onclick="simulateSocialAuth('Google');">
                    <!-- Google G Icon -->
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.85c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Google
                </button>
                <button class="btn-social" onclick="simulateSocialAuth('Microsoft');">
                    <!-- Microsoft Icon -->
                    <svg viewBox="0 0 23 23" fill="currentColor">
                        <rect x="0" y="0" width="11" height="11" fill="#f25022"/>
                        <rect x="12" y="0" width="11" height="11" fill="#7fba00"/>
                        <rect x="0" y="12" width="11" height="11" fill="#00a4ef"/>
                        <rect x="12" y="12" width="11" height="11" fill="#ffb900"/>
                    </svg>
                    Microsoft
                </button>
                <button class="btn-social" onclick="simulateSocialAuth('GitHub');">
                    <!-- GitHub SVG -->
                    <svg fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.167 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.577.688.479C19.138 20.164 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                    GitHub
                </button>
            </div>
            
            <div class="register-sub">
                New here?
                <a href="{{ url('/register') }}">
                    Create an account
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
        <!-- YouTube Icon -->
        <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
    </div>
</div>

<script>
function simulateSocialAuth(provider) {
    alert(`Connecting to official ${provider} Single-Sign-On Gateway... Requesting municipal token clearance.`);
    // Simulate successful login
    submitLoginDetails();
}

let currentSelectedRole = 'citizen';

function selectRole(role) {
    document.querySelectorAll('.role-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    currentSelectedRole = role;
}

function submitLoginDetails() {
    if (!currentSelectedRole) {
        alert('Please select your role (Citizen, Gov Staff, Gov Officer, or Admin) before logging in.');
        return;
    }
    const csrfTokenEl = document.querySelector('meta[name="csrf-token"]') || document.querySelector('input[name="_token"]');
    const csrfToken = csrfTokenEl ? (csrfTokenEl.getAttribute('content') || csrfTokenEl.value) : '';
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const remember = document.querySelector('.checkbox-hidden').checked;
    
    fetch('/login', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin',
        body: JSON.stringify({ _token: csrfToken, email, password, remember, role: currentSelectedRole })
    })
    .then(res => {
        if (!res.ok) {
            return res.json().then(errData => { throw errData; });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            window.location.href = '/dashboard';
        } else {
            alert(data.message || 'Login failed. Please check your credentials.');
        }
    })
    .catch(err => {
        if (err.message && (err.message.includes('CSRF') || err.message.includes('token') || err.message.includes('mismatch'))) {
            alert('Your session has expired or the security token is invalid. The page will now reload to refresh your session.');
            window.location.reload();
        } else if (err.errors) {
            const firstError = Object.values(err.errors)[0][0];
            alert('Login Failed: ' + firstError);
        } else if (err.message) {
            alert(err.message);
        } else {
            alert('An error occurred. Please try again.');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Hide footer from login page layout to match screenshot exactly
    const footerElement = document.querySelector('.footer');
    if (footerElement) {
        footerElement.style.display = 'none';
    }

    // Toggle Password Visibility
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            this.style.color = isPassword ? '#FFD500' : '#6b7280';
        });
    }

    // Handle Form Submission
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            submitLoginDetails();
        });
    }
});
</script>
@endsection
