@extends('layouts.app')

@section('title', 'About Us')

@section('styles')
<style>
    /* Hero Section */
    .about-hero { padding: 96px 48px 48px; max-width: 1600px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-color); }
    .ah-left { flex: 1; max-width: 600px; }
    .ah-tag { color: #FFD500; font-size: 11px; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 16px; display: block; }
    .ah-title { font-size: 56px; font-weight: 800; line-height: 1.1; margin-bottom: 24px; letter-spacing: -0.02em; }
    .ah-desc { color: var(--text-muted); font-size: 16px; line-height: 1.6; }
    
    .ah-right { flex: 1; display: flex; justify-content: center; position: relative; }
    .phone-mockup { width: 280px; height: 580px; background: var(--input-bg); border: 8px solid var(--border-color); border-radius: 36px; position: relative; z-index: 10; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.15); }
    .phone-notch { position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 120px; height: 24px; background: var(--border-color); border-radius: 0 0 12px 12px; z-index: 20; }
    .app-header { padding: 36px 16px 16px; display: flex; justify-content: space-between; align-items: center; }
    .app-logo { display: flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 700; color: var(--text-color); }
    .app-logo svg { width: 14px; height: 14px; color: #FFD500; }
    
    .app-img-wrapper { margin: 0 16px; height: 160px; background: url('https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?ixlib=rb-4.0.3&w=400&q=80') center/cover; border-radius: 8px; border: 1px solid var(--border-color); position: relative; }
    .app-scan-line { position: absolute; left: 0; top: 50%; width: 100%; height: 1px; background: #FFD500; box-shadow: 0 0 8px #FFD500; }
    
    .app-condition { text-align: center; padding: 24px 0; }
    .app-cond-lbl { color: var(--text-muted); font-size: 10px; font-weight: 700; margin-bottom: 4px; }
    .app-cond-val { color: #10b981; font-size: 16px; font-weight: 800; letter-spacing: 0.1em; }
    
    .app-gauge-wrap { display: flex; justify-content: center; margin-bottom: 24px; }
    .app-gauge { width: 120px; height: 120px; border-radius: 50%; border: 6px solid var(--border-color); position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .app-gauge-fill { position: absolute; inset: -6px; border-radius: 50%; border: 6px solid transparent; border-top-color: #10b981; border-right-color: #10b981; transform: rotate(45deg); }
    .app-score { color: var(--text-color); font-size: 28px; font-weight: 800; line-height: 1; }
    .app-score-den { color: var(--text-dark-gray); font-size: 10px; }
    .app-score-lbl { color: var(--text-dark-gray); font-size: 8px; font-weight: 700; margin-top: 4px; }
    
    .app-issues { padding: 0 16px; }
    .app-issues-lbl { color: var(--text-muted); font-size: 10px; font-weight: 700; margin-bottom: 8px; }
    .app-issue-item { display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--text-color); }
    .app-issue-dot { width: 6px; height: 6px; border-radius: 50%; background: #10b981; }
    
    .glow-ripple { position: absolute; bottom: 40px; left: 50%; transform: translateX(-50%); width: 300px; height: 80px; z-index: 0; }
    .glow-ring { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotateX(70deg); border: 2px solid rgba(255,213,0,0.5); border-radius: 50%; box-shadow: 0 0 20px rgba(255,213,0,0.2); }
    .glow-ring:nth-child(1) { width: 100px; height: 100px; animation: ripple 3s linear infinite; }
    .glow-ring:nth-child(2) { width: 200px; height: 200px; animation: ripple 3s linear 1s infinite; }
    .glow-ring:nth-child(3) { width: 300px; height: 300px; animation: ripple 3s linear 2s infinite; }
    
    @keyframes ripple { 0% { transform: translate(-50%, -50%) rotateX(70deg) scale(0.8); opacity: 1; } 100% { transform: translate(-50%, -50%) rotateX(70deg) scale(1.5); opacity: 0; } }
 
    /* Mission Section */
    .mission-section { padding: 64px 48px; max-width: 1200px; margin: 0 auto; text-align: center; }
    .ms-tag { color: #FFD500; font-size: 13px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 16px; }
    .ms-desc { color: var(--text-muted); font-size: 16px; line-height: 1.6; max-width: 700px; margin: 0 auto 64px; }
    
    .mission-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 64px; }
    .ms-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 32px 24px; transition: all 0.3s; }
    .ms-card:hover { border-color: rgba(255,213,0,0.3); transform: translateY(-5px); }
    .ms-icon { width: 48px; height: 48px; margin: 0 auto 24px; color: #FFD500; display: flex; align-items: center; justify-content: center; }
    .ms-icon svg { width: 100%; height: 100%; stroke-width: 1.5; }
    .ms-title { color: var(--text-color); font-size: 18px; font-weight: 700; margin-bottom: 16px; }
    .ms-text { color: var(--text-muted); font-size: 13px; line-height: 1.5; }
 
    /* Stats Banner */
    .stats-banner { max-width: 1200px; margin: 0 auto 96px; border: 1px solid var(--border-color); border-radius: 12px; padding: 32px 48px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; background: var(--card-bg); }
    .sb-item { display: flex; align-items: center; justify-content: center; gap: 16px; border-right: 1px solid var(--border-color); }
    .sb-item:last-child { border-right: none; }
    .sb-icon { width: 40px; height: 40px; color: #FFD500; }
    .sb-text { display: flex; flex-direction: column; align-items: flex-start; }
    .sb-val { color: #FFD500; font-size: 28px; font-weight: 800; line-height: 1; margin-bottom: 4px; }
    .sb-lbl { color: var(--text-color); font-size: 12px; font-weight: 500; }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="about-hero">
    <div class="ah-left">
        <span class="ah-tag">ABOUT US</span>
        <h1 class="ah-title">Building Smarter Roads for a <span style="color: #FFD500;">Safer Tomorrow</span></h1>
        <p class="ah-desc">RoadHealth AI is an advanced artificial intelligence platform that automatically analyzes road photographs to assess pavement condition with high accuracy and speed. Our mission is to help organizations and authorities make data-driven decisions, optimize maintenance and build better roads for everyone.</p>
    </div>
    
    <div class="ah-right">
        <!-- Glow Ripples behind phone -->
        <div class="glow-ripple">
            <div class="glow-ring"></div>
            <div class="glow-ring"></div>
            <div class="glow-ring"></div>
        </div>
        
        <!-- Phone Mockup -->
        <div class="phone-mockup">
            <div class="phone-notch"></div>
            
            <div class="app-header">
                <div class="app-logo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    RoadHealth <span style="color: #FFD500;">AI</span>
                </div>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #6b7280;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            
            <div class="app-img-wrapper">
                <div class="app-scan-line"></div>
                <svg viewBox="0 0 100 100" style="position: absolute; inset: 0; width: 100%; height: 100%;">
                    <path d="M50 80 L30 100 M50 80 L70 100 M50 80 L50 40 M40 60 L60 60" stroke="#FFD500" stroke-width="0.5" fill="none" stroke-dasharray="2 2" opacity="0.5"/>
                </svg>
            </div>
            
            <div class="app-condition">
                <div class="app-cond-lbl">Condition</div>
                <div class="app-cond-val">GOOD</div>
            </div>
            
            <div class="app-gauge-wrap">
                <div class="app-gauge">
                    <div class="app-gauge-fill"></div>
                    <span class="app-score">82</span>
                    <span class="app-score-den">/100</span>
                    <span class="app-score-lbl">PCI SCORE</span>
                </div>
            </div>
            
            <div class="app-issues">
                <div class="app-issues-lbl">Detected Issues</div>
                <div class="app-issue-item">
                    <div class="app-issue-dot"></div>
                    1 Minor Crack
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mission Section -->
<div class="mission-section">
    <div class="ms-tag">OUR MISSION</div>
    <p class="ms-desc">To empower road management with intelligent technology that delivers accurate insights, improves efficiency and ensures safer infrastructure for communities.</p>
    
    <div class="mission-grid">
        <div class="ms-card">
            <div class="ms-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
            </div>
            <h3 class="ms-title">Our Mission</h3>
            <p class="ms-text">Use AI and computer vision to automate pavement assessment and support proactive maintenance.</p>
        </div>
        
        <div class="ms-card">
            <div class="ms-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
            <h3 class="ms-title">Our Vision</h3>
            <p class="ms-text">A world where every road is safe, smart and sustainable through intelligent infrastructure.</p>
        </div>
        
        <div class="ms-card">
            <div class="ms-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <h3 class="ms-title">Our Commitment</h3>
            <p class="ms-text">Deliver accurate, reliable and actionable insights with innovation and integrity.</p>
        </div>
        
        <div class="ms-card">
            <div class="ms-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="ms-title">Who We Serve</h3>
            <p class="ms-text">Municipalities, government agencies, engineers, consultants and infrastructure owners.</p>
        </div>
    </div>
</div>

<!-- Stats Banner -->
<div class="stats-banner">
    <div class="sb-item">
        <svg class="sb-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        <div class="sb-text">
            <span class="sb-val">1.2M+</span>
            <span class="sb-lbl">Road Images Analyzed</span>
        </div>
    </div>
    <div class="sb-item">
        <svg class="sb-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        <div class="sb-text">
            <span class="sb-val">120+</span>
            <span class="sb-lbl">Cities Served</span>
        </div>
    </div>
    <div class="sb-item">
        <svg class="sb-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        <div class="sb-text">
            <span class="sb-val">98.7%</span>
            <span class="sb-lbl">Detection Accuracy</span>
        </div>
    </div>
    <div class="sb-item">
        <svg class="sb-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div class="sb-text">
            <span class="sb-val">&lt; 3 <span style="font-size: 16px;">sec</span></span>
            <span class="sb-lbl">Average Analysis Time</span>
        </div>
    </div>
</div>
@endsection
