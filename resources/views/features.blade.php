@extends('layouts.app')

@section('title', 'Features')

@section('styles')
<style>
    /* Hero Section */
    .features-hero { padding: 64px 48px; max-width: 1600px; margin: 0 auto; display: flex; gap: 48px; align-items: stretch; border-bottom: 1px solid var(--border-color); }
    .fh-left { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .fh-tag { color: #FFD500; font-size: 11px; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 16px; display: block; }
    .fh-title { font-size: 48px; font-weight: 800; line-height: 1.1; margin-bottom: 24px; letter-spacing: -0.02em; }
    .fh-desc { color: var(--text-muted); font-size: 16px; line-height: 1.6; max-width: 500px; }
    
    .fh-right { flex: 1.2; position: relative; display: flex; align-items: stretch; }
    .fh-stats-box { flex: 1; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; display: flex; align-items: flex-start; justify-content: space-between; position: relative; overflow: hidden; }
    .wave-bg { position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: radial-gradient(ellipse at bottom, rgba(255,213,0,0.15) 0%, transparent 70%); z-index: 0; }
    /* CSS generated wave lines */
    .wave-line { position: absolute; bottom: 20%; left: -10%; width: 120%; height: 40%; border-bottom: 1px solid rgba(255,213,0,0.2); border-radius: 50%; z-index: 0; }
    .wave-line:nth-child(2) { bottom: 15%; border-bottom: 1px solid rgba(255,213,0,0.4); transform: rotate(2deg); }
    .wave-line:nth-child(3) { bottom: 25%; border-bottom: 1px solid rgba(255,213,0,0.1); transform: rotate(-2deg); }
    
    .fh-stat { position: relative; z-index: 10; display: flex; flex-direction: column; align-items: flex-start; gap: 8px; }
    .fh-stat-top { display: flex; align-items: center; gap: 8px; }
    .fh-stat-icon { color: #FFD500; width: 24px; height: 24px; }
    .fh-stat-val { font-size: 20px; font-weight: 800; color: var(--text-color); }
    .fh-stat-lbl { color: var(--text-muted); font-size: 12px; font-weight: 500; margin-left: 32px; }

    /* Features Grid */
    .features-grid { padding: 48px; max-width: 1600px; margin: 0 auto; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    .f-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; transition: all 0.3s; cursor: pointer; }
    .f-card:hover { border-color: rgba(255,213,0,0.3); transform: translateY(-5px); box-shadow: 0 10px 30px -10px rgba(255,213,0,0.1); }
    .f-content { padding: 24px; flex-grow: 1; display: flex; flex-direction: column; position: relative; z-index: 10; }
    .f-icon-box { width: 40px; height: 40px; border: 1px solid rgba(255,213,0,0.3); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; background: rgba(255,213,0,0.05); }
    .f-icon-box svg { width: 20px; height: 20px; color: #FFD500; }
    .f-title { font-size: 16px; font-weight: 700; color: var(--text-color); margin-bottom: 12px; }
    .f-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; margin-bottom: 16px; flex-grow: 1; }
    .f-link { color: #FFD500; font-size: 13px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 4px; }
    .f-link:hover { text-decoration: underline; }
    
    .f-visual { height: 180px; position: relative; overflow: hidden; background: var(--input-bg); border-top: 1px solid var(--border-color); }
    
    /* Visual 1: Damage Detection */
    .v-damage { background: url('https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?ixlib=rb-4.0.3&w=600&q=80') center/cover; width: 100%; height: 100%; position: relative; opacity: 0.8; }
    .v-box { position: absolute; border: 1px dashed #FFD500; background: rgba(255,213,0,0.1); }
    
    /* Visual 2: Severity */
    .v-gauge-wrapper { display: flex; align-items: center; justify-content: center; height: 100%; padding: 24px; }
    .v-gauge { position: relative; width: 120px; height: 120px; border-radius: 50%; border: 8px solid var(--border-color); }
    .v-gauge-fill { position: absolute; top: -8px; left: -8px; right: -8px; bottom: -8px; border-radius: 50%; border: 8px solid transparent; border-top-color: #FFD500; border-right-color: #FFD500; transform: rotate(15deg); }
    .v-gauge-text { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    
    /* Visual 3: GIS Mapping */
    .v-map { width: 100%; height: 100%; background: var(--input-bg); position: relative; }
    .v-map-grid { position: absolute; inset: 0; background-image: linear-gradient(var(--border-color) 1px, transparent 1px), linear-gradient(90deg, var(--border-color) 1px, transparent 1px); background-size: 20px 20px; transform: perspective(500px) rotateX(45deg); opacity: 0.5; }
    .v-map-path { position: absolute; width: 100%; height: 100%; }
    .v-pin { position: absolute; width: 12px; height: 12px; background: #FFD500; border-radius: 50%; box-shadow: 0 0 10px #FFD500; }
    
    /* Visual 4: Analytics */
    .v-analytics { padding: 16px; height: 100%; display: flex; flex-direction: column; gap: 12px; }
    .v-chart-card { background: var(--bg-color); border-radius: 6px; border: 1px solid var(--border-color); padding: 8px; flex: 1; display: flex; align-items: center; }
    .v-line-chart { width: 100%; height: 40px; border-bottom: 1px solid var(--border-color); position: relative; }
    .v-donut { width: 40px; height: 40px; border-radius: 50%; background: conic-gradient(#FFD500 0% 40%, #ef4444 40% 70%, #3b82f6 70% 100%); margin-right: 12px; }
    
    /* Visual 5: Reports */
    .v-report { height: 100%; display: flex; align-items: center; justify-content: center; background: var(--input-bg); }
    .v-doc { width: 120px; height: 150px; background: white; border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.15); padding: 12px; transform: rotate(-5deg); transition: transform 0.3s; }
    .f-card:hover .v-doc { transform: rotate(0deg) scale(1.05); }
    .v-doc-header { border-bottom: 2px solid #FFD500; padding-bottom: 4px; margin-bottom: 8px; display: flex; align-items: center; gap: 4px; }
    .v-doc-title { font-size: 6px; font-weight: 800; color: black; }
    .v-doc-line { height: 4px; background: #e5e7eb; margin-bottom: 4px; border-radius: 2px; }
    .v-doc-img { width: 100%; height: 30px; background: #d1d5db; margin: 6px 0; border-radius: 2px; }
    
    /* Visual 6: Upload */
    .v-upload { height: 100%; padding: 16px; }
    .v-dropzone { height: 100%; border: 1px dashed var(--border-color); border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: rgba(255,255,255,0.02); }
    .v-progress { width: 80%; height: 4px; background: var(--border-color); border-radius: 2px; margin-top: 16px; position: relative; overflow: hidden; }
    .v-progress-bar { position: absolute; left: 0; top: 0; height: 100%; width: 65%; background: #FFD500; }
    
    /* Visual 7: History */
    .v-history { padding: 16px; height: 100%; }
    .v-hist-item { display: flex; align-items: center; gap: 12px; padding: 8px; background: var(--bg-color); border-radius: 6px; margin-bottom: 8px; border: 1px solid var(--border-color); }
    .v-hist-img { width: 24px; height: 24px; border-radius: 4px; background: #333; }
    .v-hist-text { flex-grow: 1; }
    .v-hist-title { font-size: 10px; color: var(--text-color); font-weight: 600; }
    .v-hist-sub { font-size: 8px; color: var(--text-dark-gray); }
    
    /* Visual 8: Security */
    .v-security { height: 100%; display: flex; align-items: center; justify-content: center; position: relative; }
    .v-shield { width: 80px; height: 90px; border: 2px solid #FFD500; border-radius: 0 0 40px 40px; display: flex; align-items: center; justify-content: center; position: relative; box-shadow: 0 0 20px rgba(255,213,0,0.2); }
    .v-shield::before { content: ''; position: absolute; inset: -10px; border: 1px solid rgba(255,213,0,0.3); border-radius: 0 0 45px 45px; }
    .v-shield::after { content: ''; position: absolute; inset: -20px; border: 1px dashed rgba(255,213,0,0.1); border-radius: 0 0 50px 50px; animation: spin 10s linear infinite; }
    
    @keyframes spin { 100% { transform: rotate(360deg); } }

    /* CTA Section */
    .cta-banner { max-width: 1600px; margin: 48px auto 96px; padding: 32px 48px; border: 1px solid var(--border-color); border-radius: 12px; display: flex; justify-content: space-between; align-items: center; background: var(--card-bg); }
    .cta-left { display: flex; align-items: center; gap: 24px; }
    .cta-icon { width: 48px; height: 48px; color: #FFD500; }
    .cta-title { font-size: 24px; font-weight: 700; color: var(--text-color); margin-bottom: 4px; }
    .cta-desc { font-size: 14px; color: var(--text-muted); }
    .cta-actions { display: flex; gap: 16px; }
</style>
@endsection

@section('content')
<div class="features-hero">
    <div class="fh-left">
        <span class="fh-tag">FEATURES</span>
        <h1 class="fh-title">Powerful Features for<br>Smarter <span style="color: #FFD500;">Road Management</span></h1>
        <p class="fh-desc">RoadHealth AI combines advanced artificial intelligence with intuitive tools to deliver accurate, fast and actionable pavement insights.</p>
    </div>
    <div class="fh-right">
        <div class="fh-stats-box">
            <div class="wave-bg"></div>
            <div class="wave-line"></div>
            <div class="wave-line"></div>
            <div class="wave-line"></div>
            
            <div class="fh-stat">
                <div class="fh-stat-top">
                    <svg class="fh-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="fh-stat-val">1.2M+</span>
                </div>
                <div class="fh-stat-lbl">Images Analyzed</div>
            </div>
            
            <div class="fh-stat">
                <div class="fh-stat-top">
                    <svg class="fh-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="fh-stat-val">98.7%</span>
                </div>
                <div class="fh-stat-lbl">Detection Accuracy</div>
            </div>
            
            <div class="fh-stat">
                <div class="fh-stat-top">
                    <svg class="fh-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="fh-stat-val">2.34 sec</span>
                </div>
                <div class="fh-stat-lbl">Average Analysis Time</div>
            </div>
            
            <div class="fh-stat">
                <div class="fh-stat-top">
                    <svg class="fh-stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span class="fh-stat-val">120+</span>
                </div>
                <div class="fh-stat-lbl">Cities Served</div>
            </div>
        </div>
    </div>
</div>

<div class="features-grid">
    <!-- Card 1 -->
    <div class="f-card">
        <div class="f-visual" style="order: 2;">
            <div class="v-damage">
                <div class="v-box" style="top: 20%; left: 30%; width: 40px; height: 30px;"></div>
                <div class="v-box" style="top: 50%; left: 60%; width: 60px; height: 20px;"></div>
                <div class="v-box" style="top: 70%; left: 20%; width: 80px; height: 40px;"></div>
            </div>
        </div>
        <div class="f-content" style="order: 1;">
            <div class="f-icon-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
            <h3 class="f-title">AI Damage Detection</h3>
            <p class="f-desc">Automatically detects potholes, cracks, patches, raveling, and other pavement distresses with high precision.</p>
            <a href="#" class="f-link">Learn more &rarr;</a>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="f-card">
        <div class="f-visual" style="order: 2;">
            <div class="v-gauge-wrapper">
                <div class="v-gauge">
                    <div class="v-gauge-fill"></div>
                    <div class="v-gauge-text">
                        <span style="color: var(--text-muted); font-size: 8px; font-weight: 700; letter-spacing: 0.1em;">PCI SCORE</span>
                        <span style="color: var(--text-color); font-size: 28px; font-weight: 800; line-height: 1; margin: 4px 0;">42</span>
                        <span style="color: var(--text-dark-gray); font-size: 10px;">/100</span>
                    </div>
                </div>
                <div style="position: absolute; bottom: 24px; color: #ef4444; font-weight: 800; font-size: 12px; letter-spacing: 0.1em;">POOR</div>
            </div>
        </div>
        <div class="f-content" style="order: 1;">
            <div class="f-icon-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <h3 class="f-title">Severity Assessment</h3>
            <p class="f-desc">Evaluates the severity of each detected issue and calculates overall pavement condition index for better prioritization.</p>
            <a href="#" class="f-link">Learn more &rarr;</a>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="f-card">
        <div class="f-visual" style="order: 2;">
            <div class="v-map">
                <div class="v-map-grid"></div>
                <svg class="v-map-path" viewBox="0 0 200 150">
                    <path d="M20 130 L80 90 L120 110 L180 40" fill="none" stroke="#FFD500" stroke-width="2" stroke-dasharray="4 4" />
                    <path d="M40 30 L90 70 L150 60" fill="none" stroke="#FFD500" stroke-width="2" stroke-dasharray="4 4" opacity="0.5"/>
                </svg>
                <div class="v-pin" style="top: 35px; left: 175px;"></div>
                <div class="v-pin" style="top: 105px; left: 115px;"></div>
                <div class="v-pin" style="top: 65px; left: 85px; opacity: 0.5;"></div>
            </div>
        </div>
        <div class="f-content" style="order: 1;">
            <div class="f-icon-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <h3 class="f-title">GIS Mapping & Location</h3>
            <p class="f-desc">Map inspection results with geolocation data to visualize road conditions across multiple regions.</p>
            <a href="#" class="f-link">Learn more &rarr;</a>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="f-card">
        <div class="f-visual" style="order: 2;">
            <div class="v-analytics">
                <div class="v-chart-card">
                    <div style="flex-grow: 1;">
                        <div style="font-size: 8px; color: var(--text-muted); margin-bottom: 8px;">Monthly Trend</div>
                        <svg viewBox="0 0 100 30" style="width: 100%; height: 20px;">
                            <path d="M0 25 L20 15 L40 20 L60 5 L80 15 L100 2" fill="none" stroke="#FFD500" stroke-width="1.5"/>
                        </svg>
                    </div>
                </div>
                <div class="v-chart-card">
                    <div class="v-donut"></div>
                    <div style="display: flex; flex-direction: column; gap: 4px; flex-grow: 1;">
                        <div style="height: 4px; width: 80%; background: var(--border-color); border-radius: 2px;"></div>
                        <div style="height: 4px; width: 60%; background: var(--border-color); border-radius: 2px;"></div>
                        <div style="height: 4px; width: 40%; background: var(--border-color); border-radius: 2px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="f-content" style="order: 1;">
            <div class="f-icon-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
            </div>
            <h3 class="f-title">Analytics Dashboard</h3>
            <p class="f-desc">Interactive dashboards and charts to track performance, monitor trends and support data-driven decisions.</p>
            <a href="#" class="f-link">Learn more &rarr;</a>
        </div>
    </div>

    <!-- Card 5 -->
    <div class="f-card">
        <div class="f-content" style="order: 1;">
            <div class="f-icon-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="f-title">Automated Reports</h3>
            <p class="f-desc">Generate professional PDF reports with images, findings, severity levels and recommended actions.</p>
            <a href="#" class="f-link">Learn more &rarr;</a>
        </div>
        <div class="f-visual" style="order: 2;">
            <div class="v-report">
                <div class="v-doc">
                    <div class="v-doc-header">
                        <svg viewBox="0 0 24 24" fill="#FFD500" style="width: 8px; height: 8px;"><circle cx="12" cy="12" r="12"/></svg>
                        <span class="v-doc-title">INSPECTION REPORT</span>
                    </div>
                    <div class="v-doc-line" style="width: 100%;"></div>
                    <div class="v-doc-line" style="width: 80%;"></div>
                    <div class="v-doc-img" style="background: url('https://images.unsplash.com/photo-1515162816999-a0c47dc192f7?ixlib=rb-4.0.3&w=200&q=80') center/cover;"></div>
                    <div class="v-doc-line" style="width: 100%;"></div>
                    <div class="v-doc-line" style="width: 100%;"></div>
                    <div class="v-doc-line" style="width: 60%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 6 -->
    <div class="f-card">
        <div class="f-content" style="order: 1;">
            <div class="f-icon-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            </div>
            <h3 class="f-title">Bulk Upload & Processing</h3>
            <p class="f-desc">Upload multiple road images at once and process them in seconds to save time and improve productivity.</p>
            <a href="#" class="f-link">Learn more &rarr;</a>
        </div>
        <div class="f-visual" style="order: 2;">
            <div class="v-upload">
                <div class="v-dropzone" id="interactive-dropzone" style="cursor: pointer;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 32px; height: 32px; color: #9ca3af; margin-bottom: 8px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    <div id="dropzone-text1" style="font-size: 10px; color: #d1d5db; margin-bottom: 4px;">Drag & Drop Images</div>
                    <div id="dropzone-text2" style="font-size: 8px; color: #6b7280;">or Click to Upload</div>
                    <div class="v-progress">
                        <div class="v-progress-bar" id="dropzone-progress-bar" style="width: 65%; transition: width 0.8s ease-out;"></div>
                    </div>
                    <div id="dropzone-pct" style="font-size: 8px; color: #FFD500; margin-top: 4px; width: 80%; text-align: right;">65%</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 7 -->
    <div class="f-card">
        <div class="f-content" style="order: 1;">
            <div class="f-icon-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="f-title">Inspection History</h3>
            <p class="f-desc">Access and manage all past inspections, compare results over time and monitor road health continuously.</p>
            <a href="#" class="f-link">Learn more &rarr;</a>
        </div>
        <div class="f-visual" style="order: 2;">
            <div class="v-history">
                <div style="font-size: 9px; color: #9ca3af; margin-bottom: 8px; font-weight: 600;">Recent Inspections</div>
                <div class="v-hist-item">
                    <div class="v-hist-img" style="background: url('https://ui-avatars.com/api/?name=AV&background=random') center/cover;"></div>
                    <div class="v-hist-text">
                        <div class="v-hist-title">NH-48, Delhi</div>
                        <div class="v-hist-sub">24 May 2024</div>
                    </div>
                    <svg style="width: 12px; height: 12px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
                <div class="v-hist-item">
                    <div class="v-hist-img" style="background: url('https://ui-avatars.com/api/?name=MG&background=random') center/cover;"></div>
                    <div class="v-hist-text">
                        <div class="v-hist-title">MG Road, Mumbai</div>
                        <div class="v-hist-sub">22 May 2024</div>
                    </div>
                    <svg style="width: 12px; height: 12px; color: #6b7280;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
                <div class="v-hist-item" style="justify-content: center; padding: 4px; background: transparent; border: none;">
                    <span style="font-size: 9px; color: #FFD500; font-weight: 600;">View All</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 8 -->
    <div class="f-card">
        <div class="f-content" style="order: 1;">
            <div class="f-icon-box">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <h3 class="f-title">Secure & Reliable</h3>
            <p class="f-desc">Enterprise-grade security to protect your data with role-based access, encrypted storage and regular backups.</p>
            <a href="#" class="f-link">Learn more &rarr;</a>
        </div>
        <div class="f-visual" style="order: 2;">
            <div class="v-security">
                <div class="v-shield">
                    <svg fill="none" stroke="#FFD500" viewBox="0 0 24 24" style="width: 32px; height: 32px; stroke-width: 1.5;"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cta-banner">
    <div class="cta-left">
        <svg class="cta-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        <div>
            <h2 class="cta-title">Ready to transform your road inspections?</h2>
            <p class="cta-desc">Join thousands of organizations using RoadHealth AI for smarter road management.</p>
        </div>
    </div>
    <div class="cta-actions">
        <button class="btn btn-primary" onclick="window.location.href='/dashboard';" style="padding: 14px 28px; font-weight: 700; font-size: 15px; cursor: pointer;">Get Started Now &rarr;</button>
        <button class="btn btn-outline" onclick="window.location.href='/contact';" style="padding: 14px 28px; font-weight: 700; font-size: 15px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Request Demo
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Bulk Upload Interactive Simulation
    const dropzone = document.getElementById('interactive-dropzone');
    const progressBar = document.getElementById('dropzone-progress-bar');
    const progressPct = document.getElementById('dropzone-pct');
    const text1 = document.getElementById('dropzone-text1');
    const text2 = document.getElementById('dropzone-text2');
    
    let isUploaded = false;

    if (dropzone) {
        dropzone.addEventListener('click', function () {
            if (isUploaded) {
                // Reset state
                progressBar.style.width = '65%';
                progressPct.innerText = '65%';
                text1.innerText = 'Drag & Drop Images';
                text2.innerText = 'or Click to Upload';
                isUploaded = false;
                return;
            }

            // Simulate file selection
            text1.innerText = 'Uploading 4 images...';
            text2.style.display = 'none';
            progressBar.style.width = '100%';
            
            let count = 65;
            const interval = setInterval(() => {
                count += 5;
                if (count > 100) {
                    clearInterval(interval);
                    text1.innerText = 'Scan Complete!';
                    text2.innerText = '4 files successfully classified.';
                    text2.style.display = 'block';
                    text2.style.color = '#10b981';
                    isUploaded = true;
                    alert('Bulk Upload Complete! 4 road scans successfully queued in your inspection dashboard.');
                } else {
                    progressPct.innerText = count + '%';
                }
            }, 100);
        });
    }

    // 2. Redirect page handlers for each features card
    const cards = document.querySelectorAll('.f-card');
    const pageUrls = [
        '/',
        '/reports',
        '/network',
        '/dashboard',
        '/reports',
        '/upload',
        '/reports',
        '/about'
    ];

    cards.forEach((card, index) => {
        // Set href on the link
        const link = card.querySelector('.f-link');
        if (link && pageUrls[index]) {
            link.setAttribute('href', pageUrls[index]);
            link.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Add click listener to the entire card
        card.addEventListener('click', function() {
            if (pageUrls[index]) {
                window.location.href = pageUrls[index];
            }
        });
    });
});
</script>
@endsection
