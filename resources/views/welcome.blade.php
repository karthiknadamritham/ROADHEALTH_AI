@extends('layouts.app')

@section('title', 'Home')

@section('styles')
<style>
    /* Hero */
    .hero-bg { position: absolute; top: 0; left: 0; width: 100%; height: 900px; z-index: 0; overflow: hidden; }
    .hero-bg img { width: 100%; height: 100%; object-fit: cover; opacity: 0.7; }
    .gradient-x { position: absolute; inset: 0; background: linear-gradient(to right, var(--bg-color) 0%, rgba(5,5,5,0.95) 40%, transparent 70%); transition: background 0.3s; }
    .gradient-y { position: absolute; inset: 0; background: linear-gradient(to top, var(--bg-color) 0%, rgba(5,5,5,0.4) 30%, transparent 100%); transition: background 0.3s; }
    
    html.light-theme .gradient-x {
        background: linear-gradient(to right, var(--bg-color) 0%, rgba(245,246,248,0.95) 40%, transparent 70%);
    }
    html.light-theme .gradient-y {
        background: linear-gradient(to top, var(--bg-color) 0%, rgba(245,246,248,0.4) 30%, transparent 100%);
    }
    
    .hero-content { position: relative; z-index: 10; display: flex; max-width: 1600px; margin: 0 auto; padding: 48px 48px 96px; min-height: 700px; }
    .hero-left { width: 50%; display: flex; flex-direction: column; justify-content: center; padding-right: 40px; }
    .hero-right { width: 50%; position: relative; min-height: 600px; }
    
    .pill { display: inline-flex; align-items: center; border: 1px solid rgba(255,213,0,0.4); border-radius: 4px; color: #FFD500; font-size: 10px; font-weight: 700; letter-spacing: 0.1em; padding: 6px 12px; margin-bottom: 32px; width: max-content; text-transform: uppercase; }
    .hero-title { font-size: 72px; font-weight: 900; line-height: 1.05; margin-bottom: 24px; letter-spacing: -0.02em; color: var(--text-color); }
    .hero-desc { color: var(--text-muted); font-size: 18px; margin-bottom: 40px; max-width: 500px; line-height: 1.6; }
    .hero-btns { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 32px; }
    .btn-large { padding: 16px 28px; font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px; border-radius: 6px; cursor: pointer; border: none; transition: background 0.2s; }
    .btn-large svg { width: 20px; height: 20px; }
    .btn-primary-large { background: #FFD500; color: black; }
    .btn-primary-large:hover { background: #facc15; }
    .btn-outline-large { background: transparent; border: 1px solid var(--btn-outline-border); color: var(--btn-outline-color); }
    .btn-outline-large:hover { background: var(--btn-outline-hover-bg); }
    .btn-outline-large svg { color: #FFD500; }
    .trust-badge { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-muted); font-weight: 600; letter-spacing: 0.02em; }
    .trust-badge svg { width: 16px; height: 16px; color: #FFD500; }
    
    /* Overlays */
    .ai-line { position: absolute; background: #FFD500; top: 8%; left: 30%; width: 1px; height: 30px; box-shadow: 0 0 8px #FFD500; }
    .ai-label { position: absolute; color: #FFD500; font-weight: 900; font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; background: rgba(0,0,0,0.8); padding: 4px 8px; border: 1px solid rgba(255,213,0,0.5); top: 4%; left: 30%; transform: translateX(-50%); }
    .detect-box { position: absolute; border: 2px solid #FFD500; box-shadow: 0 0 10px rgba(255,213,0,0.3); background: rgba(255,213,0,0.05); backdrop-filter: blur(4px); }
    .detect-label { position: absolute; background-color: rgba(0,0,0,0.8); color: #FFD500; padding: 2px 8px; font-size: 12px; font-weight: 700; border: 1px solid #FFD500; letter-spacing: 0.05em; top: -24px; left: -2px; }
    
    /* Dashboard Card */
    .glass-panel { position: absolute; top: 10%; right: 0; width: 320px; background: var(--card-bg); backdrop-filter: blur(12px); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); transition: background-color 0.3s, border-color 0.3s; }
    .card-title { color: var(--text-muted); font-size: 10px; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 4px; text-align: center; }
    .card-score-text { color: #FFD500; font-size: 36px; font-weight: 900; letter-spacing: 0.05em; text-align: center; text-shadow: 0 0 15px rgba(255,213,0,0.4); margin-bottom: 8px;}
    .card-subtitle { color: var(--text-dark-gray); font-size: 9px; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; text-align: center; margin-bottom: 24px; }
    
    .gauge-wrapper { position: relative; width: 160px; height: 80px; overflow: hidden; margin: 0 auto 24px; }
    .gauge-background { position: absolute; top: 0; left: 0; width: 160px; height: 160px; border-radius: 50%; border: 12px solid var(--input-border); transition: border-color 0.3s; }
    .gauge-progress { position: absolute; top: 0; left: 0; width: 160px; height: 160px; border-radius: 50%; border: 12px solid transparent; border-top-color: #FFD500; border-left-color: #FFD500; transform: rotate(30deg); }
    .gauge-value { position: absolute; bottom: 8px; left: 0; width: 100%; text-align: center; }
    .gauge-value span.num { color: var(--text-color); font-size: 36px; font-weight: 900; transition: color 0.3s; }
    .gauge-value span.den { color: var(--text-dark-gray); font-size: 14px; font-weight: 700; transition: color 0.3s; }
    
    .metric-row { display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid var(--border-color); transition: border-color 0.3s; }
    .metric-row:last-of-type { border-bottom: none; margin-bottom: 24px; padding-bottom: 0; }
    .metric-left { display: flex; align-items: center; gap: 12px; }
    .metric-left svg { width: 20px; height: 20px; color: #FFD500; }
    .metric-label { color: var(--text-dark-gray); font-size: 9px; font-weight: 700; letter-spacing: 0.1em; margin-bottom: 2px; transition: color 0.3s; }
    .metric-val { color: var(--text-color); font-size: 14px; font-weight: 700; transition: color 0.3s; }
    .metric-val.red { color: #ef4444; }
    .metric-icon-small { width: 16px; height: 16px; color: var(--text-dark-gray); transition: color 0.3s; }
    
    .btn-full { width: 100%; padding: 12px; font-size: 14px; font-weight: 700; background: #FFD500; color: black; border: none; border-radius: 6px; display: flex; justify-content: center; align-items: center; gap: 8px; cursor: pointer; transition: background 0.2s; }
    .btn-full:hover { background: #facc15; }
    .btn-full svg { width: 16px; height: 16px; }

    /* Stats */
    .stats-section { position: relative; z-index: 10; background: var(--navbar-bg); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); margin-top: 32px; transition: background-color 0.3s, border-color 0.3s; }
    .stats-container { max-width: 1600px; margin: 0 auto; padding: 40px 48px; display: flex; justify-content: space-between; align-items: center; }
    .stat-item { display: flex; align-items: center; gap: 16px; width: 25%; justify-content: center; border-right: 1px solid var(--border-color); transition: border-color 0.3s; }
    .stat-item:last-child { border-right: none; }
    .stat-item svg { width: 48px; height: 48px; color: #FFD500; flex-shrink: 0; }
    .stat-text { display: flex; flex-direction: column; }
    .stat-num { font-size: 36px; font-weight: 900; color: #FFD500; letter-spacing: -0.02em; line-height: 1; }
    .stat-num small { font-size: 24px; }
    .stat-desc { font-size: 13px; font-weight: 600; color: var(--text-muted); margin-top: 4px; transition: color 0.3s; }
    
    /* Features */
    .features-section { position: relative; z-index: 10; padding: 96px 48px; max-width: 1600px; margin: 0 auto; background: var(--bg-color); transition: background-color 0.3s; }
    .features-header { text-align: center; margin-bottom: 64px; }
    .features-header h4 { color: #FFD500; font-size: 11px; font-weight: 900; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 16px; }
    .features-header h2 { font-size: 36px; font-weight: 700; color: var(--text-color); letter-spacing: -0.02em; transition: color 0.3s; }
    .features-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 20px; }
    .feature-card { background: var(--card-bg); padding: 24px; border-radius: 16px; text-align: center; border: 1px solid var(--border-color); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); transition: all 0.3s; cursor: pointer; }
    .feature-card:hover { border-color: rgba(255,213,0,0.3); background: var(--btn-outline-hover-bg); transform: translateY(-5px); }
    .feature-icon-wrapper { width: 56px; height: 56px; background: rgba(255,213,0,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #FFD500; }
    .feature-icon-wrapper svg { width: 28px; height: 28px; }
    .feature-title { font-size: 15px; font-weight: 700; color: var(--text-color); margin-bottom: 12px; transition: color 0.3s; }
    .feature-desc { font-size: 13px; color: var(--text-muted); line-height: 1.6; transition: color 0.3s; }
</style>
@endsection

@section('content')
<div style="position: relative; background: #050505;">
    <!-- Full-width background image for hero -->
    <div class="hero-bg">
        <img src="/images/road-bg.png" alt="Road Background" />
        <div class="gradient-x"></div>
        <div class="gradient-y"></div>
    </div>

    <!-- Hero Content -->
    <div class="hero-content">
        <div class="hero-left">
            <div class="pill">AI-Powered Road Analysis</div>
            
            <h1 class="hero-title">
                Smart Roads.<br>
                Safer Future.<br>
                <span class="text-yellow">Powered by AI.</span>
            </h1>
            
            <p class="hero-desc">
                RoadHealth AI automatically detects pavement damage, evaluates severity, and delivers actionable insights in seconds from simple road photographs.
            </p>
            
            <div class="hero-btns">
                <!-- Hidden file input -->
                <input type="file" id="hero-file-input" accept="image/*" style="display:none;">
                <button class="btn-large btn-primary-large" id="btn-analyze-hero" onclick="document.getElementById('hero-file-input').click()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span id="btn-analyze-text">Analyze Road Image</span>
                </button>
                <button class="btn-large btn-outline-large" id="btn-camera-hero" style="border-color: #FFD500; color: #FFD500;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span id="btn-camera-text-hero">Use Camera & Location</span>
                </button>
                <button class="btn-large btn-outline-large" onclick="window.location.href='/dashboard'">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    View Live Demo
                </button>
            </div>
            <!-- Upload hint -->
            <div id="upload-hint" style="font-size:12px;color:#6b7280;margin-top:-8px;display:flex;align-items:center;gap:6px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;color:#FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Click the button above to upload any road photo and get instant AI results
            </div>
            <div class="trust-badge" style="margin-top:8px;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                Secure. Accurate. Reliable.
            </div>
        </div>

        <!-- Right Interactive Overlays -->
        <div class="hero-right">



            <!-- Scanning overlay (shown while AI processes) -->
            <div id="scan-overlay" style="display:none;position:absolute;inset:0;background:rgba(0,0,0,0.6);border-radius:16px;display:none;align-items:center;justify-content:center;flex-direction:column;gap:12px;z-index:20;">
                <div style="width:48px;height:48px;border:3px solid rgba(255,213,0,0.3);border-top-color:#FFD500;border-radius:50%;animation:spin 1s linear infinite;"></div>
                <div style="color:#FFD500;font-size:13px;font-weight:800;letter-spacing:0.1em;">ANALYZING...</div>
            </div>

            <div class="glass-panel" id="result-panel">
                <!-- Default state: upload prompt -->
                <div id="panel-default">
                    <div class="card-title" style="font-size:12px;margin-bottom:12px;">INSTANT AI ROAD ANALYSIS</div>
                    <div style="border:2px dashed rgba(255,213,0,0.3);border-radius:10px;padding:20px 16px;text-align:center;cursor:pointer;transition:all 0.2s;margin-bottom:12px;" id="panel-drop" onclick="document.getElementById('hero-file-input').click()" onmouseover="this.style.borderColor='#FFD500'" onmouseout="this.style.borderColor='rgba(255,213,0,0.3)'">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:36px;height:36px;color:#FFD500;margin:0 auto 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <div style="color:white;font-weight:700;font-size:13px;margin-bottom:4px;">Drop Road Photo Here</div>
                        <div style="color:#6b7280;font-size:11px;">or click to browse</div>
                    </div>

                    <!-- Camera Option -->
                    <div id="panel-camera" style="border: 2px dashed rgba(255,213,0,0.25); border-radius: 10px; padding: 20px 16px; text-align: center; cursor: pointer; transition: all 0.2s; background: rgba(255,213,0,0.02); margin-bottom: 16px;" onmouseover="this.style.borderColor='#FFD500'; this.style.backgroundColor='rgba(255,213,0,0.05)'" onmouseout="this.style.borderColor='rgba(255,213,0,0.25)'; this.style.backgroundColor='rgba(255,213,0,0.02)'">
                        <svg fill="none" stroke="#FFD500" viewBox="0 0 24 24" style="width: 36px; height: 36px; margin: 0 auto 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <div style="color: white; font-weight: 700; font-size: 13px; margin-bottom: 4px;">Use Camera & Location</div>
                        <div style="color: #9ca3af; font-size: 11px;">Auto-pins geolocated photo</div>
                    </div>

                    <div style="margin-top:14px;display:flex;flex-direction:column;gap:8px;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#6b7280;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;color:#10b981;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Detects potholes, cracks & surface wear
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#6b7280;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;color:#10b981;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Returns PCI score & severity instantly
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:#6b7280;">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;color:#10b981;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Saves report to your dashboard
                        </div>
                    </div>
                </div>

                <!-- Result state (hidden until analysis done) -->
                <div id="panel-result" style="display:none;">
                    <div class="card-title">ROAD CONDITION</div>
                    <div class="card-score-text" id="res-condition">POOR</div>
                    <div class="card-subtitle">- PCI SCORE -</div>

                    <div class="gauge-wrapper">
                        <div class="gauge-background"></div>
                        <div class="gauge-progress" id="res-gauge"></div>
                        <div class="gauge-value">
                            <span class="num" id="res-pci">42</span><span class="den">/100</span>
                        </div>
                    </div>

                    <div class="metric-row">
                        <div class="metric-left">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                            <div>
                                <div class="metric-label">DETECTED ISSUES</div>
                                <div class="metric-val" id="res-issues">3</div>
                            </div>
                        </div>
                        <svg class="metric-icon-small" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>

                    <div class="metric-row">
                        <div class="metric-left">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            <div>
                                <div class="metric-label">SEVERITY</div>
                                <div class="metric-val" id="res-severity" style="color:#ef4444;">HIGH</div>
                            </div>
                        </div>
                    </div>

                    <div class="metric-row">
                        <div class="metric-left">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <div class="metric-label">ANALYSIS TIME</div>
                                <div class="metric-val" id="res-time">—</div>
                            </div>
                        </div>
                    </div>

                    <button class="btn-full" id="res-report-btn">
                        View Full Report
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </button>
                    <button class="btn-full" id="res-register-btn" style="background:#10b981; color:white; margin-top:8px;">
                        Register Problem
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <button onclick="resetHero()" style="width:100%;background:transparent;border:1px solid rgba(255,255,255,0.1);color:#9ca3af;padding:8px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;margin-top:8px;">↩ Analyze Another</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-container">
            <div class="stat-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <div class="stat-text">
                    <div class="stat-num">1.2M+</div>
                    <div class="stat-desc">Images Analyzed</div>
                </div>
            </div>
            
            <div class="stat-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                <div class="stat-text">
                    <div class="stat-num">45K+</div>
                    <div class="stat-desc">Kilometers Assessed</div>
                </div>
            </div>
            
            <div class="stat-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                <div class="stat-text">
                    <div class="stat-num">98.7%</div>
                    <div class="stat-desc">Detection Accuracy</div>
                </div>
            </div>
            
            <div class="stat-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="stat-text">
                    <div class="stat-num">< 3<small>sec</small></div>
                    <div class="stat-desc">Average Analysis Time</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="features-section">
        <div class="features-header">
            <h4>Powerful Features</h4>
            <h2>Everything you need for smarter road management</h2>
        </div>
             <div class="features-grid">
            <!-- Feature 1 -->
            <div class="feature-card" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                <div class="feature-icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </div>
                <h3 class="feature-title">Damage Detection</h3>
                <p class="feature-desc">AI detects potholes, cracks, patches and more.</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="feature-card" onclick="window.location.href='/how-it-works'">
                <div class="feature-icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h3 class="feature-title">Severity Assessment</h3>
                <p class="feature-desc">Advanced algorithms determine damage severity levels.</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="feature-card" onclick="window.location.href='/network'">
                <div class="feature-icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="feature-title">GIS Mapping</h3>
                <p class="feature-desc">Visualize condition on interactive maps.</p>
            </div>
            
            <!-- Feature 4 -->
            <div class="feature-card" onclick="window.location.href='/dashboard'">
                <div class="feature-icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <h3 class="feature-title">Analytics Dashboard</h3>
                <p class="feature-desc">Real-time insights and performance metrics.</p>
            </div>
            
            <!-- Feature 5 -->
            <div class="feature-card" onclick="window.location.href='/reports'">
                <div class="feature-icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="feature-title">Reports & Export</h3>
                <p class="feature-desc">Generate professional reports and export data.</p>
            </div>
 
            <!-- Feature 6 -->
            <div class="feature-card" onclick="window.location.href='/reports'">
                <div class="feature-icon-wrapper">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="feature-title">History Tracking</h3>
                <p class="feature-desc">Track inspection history and monitor road health.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Register Problem -->
<div class="modal-overlay" id="modal-register-problem" style="position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 1000;">
    <div class="modal-box" style="background: #0a0a0a; border: 1px solid rgba(255,213,0,0.3); border-radius: 16px; padding: 32px; width: 500px; max-width: 90%; box-shadow: 0 0 30px rgba(255,213,0,0.15); display: flex; flex-direction: column; gap: 20px; position: relative;">
        <button class="modal-close" id="close-register-modal" style="position: absolute; top: 20px; right: 20px; color: #6b7280; cursor: pointer; background: none; border: none;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px; height:20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="modal-title" style="font-size: 20px; font-weight: 800; color: white; display: flex; align-items: center; gap: 12px;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px; height: 24px; color: #FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Register Road Problem
        </div>
        <p style="color: #9ca3af; font-size: 13px; line-height: 1.4; margin-top: -8px;">Provide registration details. Registered issues are forwarded immediately to municipal officers.</p>
        
        <form id="home-register-form" style="display: flex; flex-direction: column; gap: 16px;">
            <input type="hidden" id="reg-analysis-id">
            
            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Problem Title *</label>
                <input type="text" id="reg-title" placeholder="E.g., Deep potholes near intersection" required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Location / Address *</label>
                <input type="text" id="reg-location" placeholder="E.g., MG Road, Sector 4" required style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Landmark</label>
                <input type="text" id="reg-landmark" placeholder="E.g., Near metro station pillar 42" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; color: white; font-size: 14px; outline: none; font-family:'Inter',sans-serif;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 6px;">
                <label style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase;">Remarks / Message</label>
                <textarea id="reg-remarks" placeholder="Provide extra context, severity or safety warnings for the repair team..." style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: white; padding: 12px; font-size: 13px; font-family:'Inter',sans-serif; height: 100px; resize: none; outline: none;"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 10px;">
                <button type="button" class="btn btn-outline" id="btn-cancel-register" style="padding: 10px 20px; border-radius: 8px; color: white; border: 1px solid rgba(255,255,255,0.1); background: transparent; cursor: pointer; font-weight: 600;">Cancel</button>
                <button type="submit" class="btn" style="padding: 10px 20px; border-radius: 8px; color: black; background: #FFD500; border: none; font-weight: 700; cursor: pointer;">Register &amp; Report</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<style>
@keyframes spin { to { transform: rotate(360deg); } }
.glass-panel { transition: all 0.4s ease; }
</style>
<script>
const heroBg     = document.querySelector('.hero-bg img');
const fileInput  = document.getElementById('hero-file-input');
const scanOverlay= document.getElementById('scan-overlay');
const panelDef   = document.getElementById('panel-default');
const panelRes   = document.getElementById('panel-result');
const heroRight  = document.querySelector('.hero-right');
let lastReportUrl = '/reports';

fileInput.addEventListener('change', function() {
    if (!this.files[0]) return;
    const file = this.files[0];
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            runAnalysis(file, position.coords.latitude, position.coords.longitude, "Uploaded Location");
        }, function(error) {
            runAnalysis(file);
        }, { timeout: 5000 });
    } else {
        runAnalysis(file);
    }
});

// Drag-and-drop on the hero area
document.querySelector('.hero-right').addEventListener('dragover', e => e.preventDefault());
document.querySelector('.hero-right').addEventListener('drop', e => {
    e.preventDefault();
    const f = e.dataTransfer.files[0];
    if (f && f.type.startsWith('image/')) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                runAnalysis(f, position.coords.latitude, position.coords.longitude, "Uploaded Location");
            }, function(error) {
                runAnalysis(f);
            }, { timeout: 5000 });
        } else {
            runAnalysis(f);
        }
    }
});

// Camera Integration with Auto Live Location
function launchCamera() {
    if (navigator.geolocation) {
        const heroBtn = document.getElementById('btn-camera-text-hero');
        const cameraPanel = document.getElementById('panel-camera');
        const ogHeroHtml = heroBtn ? heroBtn.innerHTML : 'Use Camera & Location';
        const ogPanelHtml = cameraPanel ? cameraPanel.innerHTML : '';
        
        if (heroBtn) heroBtn.textContent = 'Acquiring location...';
        if (cameraPanel) cameraPanel.innerHTML = '<div style="color:#FFD500; font-size:12px; font-weight:700; padding: 20px 0;">Acquiring live location...</div>';
        
        navigator.geolocation.getCurrentPosition(function(position) {
            if (heroBtn) heroBtn.innerHTML = ogHeroHtml;
            if (cameraPanel) cameraPanel.innerHTML = ogPanelHtml;
            
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const loc = "Live Camera Location";
            
            // Open mobile camera natively
            const camInput = document.createElement('input');
            camInput.type = 'file';
            camInput.accept = 'image/*';
            camInput.capture = 'environment';
            camInput.onchange = function(e) {
                if (camInput.files.length > 0) {
                    runAnalysis(camInput.files[0], lat, lng, loc);
                }
            };
            camInput.click();
        }, function(error) {
            if (heroBtn) heroBtn.innerHTML = ogHeroHtml;
            if (cameraPanel) cameraPanel.innerHTML = ogPanelHtml;
            alert("Live location access is required to use the camera. Please allow location access in browser settings.");
        });
    } else {
        alert("Geolocation is not supported by your browser.");
    }
}

// Bind camera buttons
document.addEventListener('DOMContentLoaded', function() {
    const btnCameraHero = document.getElementById('btn-camera-hero');
    const panelCamera = document.getElementById('panel-camera');
    
    if (btnCameraHero) {
        btnCameraHero.addEventListener('click', launchCamera);
    }
    if (panelCamera) {
        panelCamera.addEventListener('click', launchCamera);
    }

    // Register Modal bindings
    const regModal = document.getElementById('modal-register-problem');
    const btnRegister = document.getElementById('res-register-btn');
    const closeRegModal = document.getElementById('close-register-modal');
    const cancelRegModal = document.getElementById('btn-cancel-register');
    const regForm = document.getElementById('home-register-form');

    if (btnRegister) {
        btnRegister.addEventListener('click', function() {
            regModal.style.display = 'flex';
        });
    }

    const closeHandler = () => { regModal.style.display = 'none'; };
    if (closeRegModal) closeRegModal.addEventListener('click', closeHandler);
    if (cancelRegModal) cancelRegModal.addEventListener('click', closeHandler);

    if (regForm) {
        regForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('reg-analysis-id').value;
            const title = document.getElementById('reg-title').value;
            const location = document.getElementById('reg-location').value;
            const landmark = document.getElementById('reg-landmark').value;
            const remarks = document.getElementById('reg-remarks').value;

            const submitBtn = regForm.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Registering...';
            submitBtn.disabled = true;

            const fd = new FormData();
            fd.append('title', title);
            fd.append('location', location);
            fd.append('landmark', landmark);
            fd.append('remarks', remarks);
            fd.append('json', '1');
            fd.append('_token', document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '');

            fetch('/reports/' + id + '/register', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: fd
            })
            .then(r => {
                if(!r.ok) throw new Error('Failed to register');
                return r.json();
            })
            .then(data => {
                regModal.style.display = 'none';
                btnRegister.textContent = '✓ Registered';
                btnRegister.style.background = '#10b981';
                btnRegister.disabled = true;
                alert('Success! Problem has been registered and forwarded to municipal officers.');
            })
            .catch(err => {
                console.error(err);
                alert('Registration failed. Please try again.');
                submitBtn.textContent = 'Register & Report';
                submitBtn.disabled = false;
            });
        });
    }
});

function runAnalysis(file, lat = null, lng = null, loc = null) {
    const t0 = Date.now();

    // Show uploaded image as hero background
    const reader = new FileReader();
    reader.onload = e => { heroBg.src = e.target.result; };
    reader.readAsDataURL(file);

    // Show scanning spinner
    scanOverlay.style.display = 'flex';
    panelDef.style.display    = 'none';
    panelRes.style.display    = 'none';
    document.getElementById('btn-analyze-text').textContent = 'Analyzing...';

    // POST directly to Laravel analyze endpoint with JSON request
    const fd = new FormData();
    fd.append('road_image', file);
    fd.append('json', '1');
    if (lat) fd.append('latitude', lat);
    if (lng) fd.append('longitude', lng);
    if (loc) fd.append('location', loc);
    fd.append('_token', document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '');

    fetch('/analyze', {
        method: 'POST',
        headers: {
            'Accept': 'application/json'
        },
        body: fd
    })
        .then(r => {
            if (!r.ok) throw new Error('Network response was not ok');
            return r.json();
        })
        .then(data => {
            const elapsed = ((Date.now() - t0) / 1000).toFixed(2);
            showResult(data, elapsed);
        })
        .catch(err => {
            console.error('Analysis error details:', err);
            alert('Analysis failed. Please make sure both Laravel and Python servers are running.');
            resetHero();
        });
}

function showResult(data, elapsed) {
    scanOverlay.style.display = 'none';
    panelRes.style.display    = 'block';
    document.getElementById('btn-analyze-text').textContent = 'Analyze Road Image';

    const condition = (data.condition || 'Unknown').toUpperCase();
    const pci       = data.pci_score || 0;
    const severity  = (data.severity || 'Unknown').toUpperCase();
    const issues    = data.total_defects ?? 0;

    document.getElementById('res-condition').textContent = condition;
    document.getElementById('res-pci').textContent       = pci;
    document.getElementById('res-issues').textContent    = issues;
    document.getElementById('res-severity').textContent  = severity;
    document.getElementById('res-time').textContent      = elapsed + ' sec';

    // Color severity
    const sevEl = document.getElementById('res-severity');
    if (condition === 'INVALID') {
        sevEl.style.color = '#6b7280';
    } else {
        sevEl.style.color = severity === 'HIGH' ? '#ef4444' : severity === 'MEDIUM' ? '#f59e0b' : '#10b981';
    }

    // Color condition
    const condEl = document.getElementById('res-condition');
    if (condition === 'INVALID') {
        condEl.style.color = '#6b7280';
        document.getElementById('res-pci').textContent = 'N/A';
        document.querySelector('.gauge-value span.den').textContent = 'NOT A ROAD';
        document.querySelector('.gauge-value span.den').style.fontSize = '10px';
    } else {
        condEl.style.color = pci < 55 ? '#ef4444' : pci < 75 ? '#f59e0b' : '#10b981';
        document.querySelector('.gauge-value span.den').textContent = '/100';
        document.querySelector('.gauge-value span.den').style.fontSize = '14px';
    }

    // Gauge color
    const gaugeColor = condition === 'INVALID' ? '#6b7280' : (pci < 55 ? '#ef4444' : pci < 75 ? '#f59e0b' : '#10b981');
    document.getElementById('res-gauge').style.borderTopColor  = gaugeColor;
    document.getElementById('res-gauge').style.borderLeftColor = gaugeColor;

    // Save only the DB ID to sessionStorage (instant, no lag!)
    sessionStorage.setItem('rh_last_report_id', data.id || '');

    // Populate registration form fields
    document.getElementById('reg-analysis-id').value = data.id || '';
    document.getElementById('reg-location').value = data.location || '';
    document.getElementById('reg-title').value = '';
    document.getElementById('reg-landmark').value = '';
    document.getElementById('reg-remarks').value = '';
    
    const regBtn = document.getElementById('res-register-btn');
    if (regBtn) {
        regBtn.style.display = 'flex';
        regBtn.textContent = 'Register Problem';
        regBtn.style.background = '#10b981';
        regBtn.disabled = false;
    }

    // "View Full Report" → /dashboard/report-export
    document.getElementById('res-report-btn').onclick = () => {
        window.location.href = '/dashboard/report-export' + (data.id ? '?id=' + data.id : '');
    };
}

function resetHero() {
    heroBg.src = '/images/road-bg.png';
    scanOverlay.style.display = 'none';
    panelDef.style.display    = 'block';
    panelRes.style.display    = 'none';
    fileInput.value = '';
    document.getElementById('btn-analyze-text').textContent = 'Analyze Road Image';
}
</script>
@endsection
@endsection
