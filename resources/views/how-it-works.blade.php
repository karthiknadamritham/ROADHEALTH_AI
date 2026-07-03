@extends('layouts.app')

@section('title', 'How It Works')

@section('styles')
<style>
    .hiw-header { text-align: center; padding: 64px 24px 48px; max-width: 800px; margin: 0 auto; }
    .hiw-tag { color: #FFD500; font-size: 11px; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 16px; display: block; }
    .hiw-title { font-size: 48px; font-weight: 800; line-height: 1.1; margin-bottom: 24px; letter-spacing: -0.02em; }
    .hiw-desc { color: var(--text-muted); font-size: 16px; line-height: 1.6; }

    /* Horizontal Flow Section */
    .flow-section { max-width: 1600px; margin: 0 auto 80px; padding: 0 48px; position: relative; }
    .flow-container { display: flex; justify-content: space-between; align-items: stretch; position: relative; gap: 16px; }
    
    /* Flow Connectors */
    .flow-arrow { display: flex; align-items: center; justify-content: center; color: rgba(255,213,0,0.4); font-size: 24px; font-weight: 700; flex-shrink: 0; }
    
    .flow-card { flex: 1; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; position: relative; }
    .flow-card:hover { border-color: rgba(255,213,0,0.3); }
    
    .flow-step-num { font-size: 10px; font-weight: 800; color: #FFD500; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 12px; }
    .flow-title { font-size: 16px; font-weight: 700; color: var(--text-color); margin-bottom: 16px; }
    
    .flow-subgrid { display: flex; flex-direction: column; gap: 8px; flex-grow: 1; }
    .flow-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; background: var(--input-bg); border: 1px solid var(--border-color); border-radius: 8px; font-size: 12px; color: var(--text-muted); }
    .flow-item svg { width: 18px; height: 18px; color: #FFD500; flex-shrink: 0; }

    /* AI Model Engine Box Special Styling */
    .flow-card.special { border: 1px solid rgba(255,213,0,0.4); background: linear-gradient(180deg, rgba(255,213,0,0.03) 0%, var(--card-bg) 100%); }
    .flow-card.special .flow-item { background: rgba(255,213,0,0.02); border-color: rgba(255,213,0,0.2); }

    /* Pipeline Architecture Grid */
    .pipe-section { max-width: 1600px; margin: 0 auto 96px; padding: 0 48px; }
    .pipe-header { text-align: center; margin-bottom: 48px; }
    .pipe-tag { color: #FFD500; font-size: 11px; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 12px; display: block; }
    .pipe-title { font-size: 32px; font-weight: 800; color: var(--text-color); }
    
    .pipe-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .pipe-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; display: flex; flex-direction: column; transition: all 0.3s; }
    .pipe-card:hover { border-color: rgba(255,213,0,0.3); transform: translateY(-5px); }
    
    .pipe-icon { width: 48px; height: 48px; background: rgba(255,213,0,0.05); border: 1px solid rgba(255,213,0,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #FFD500; margin-bottom: 24px; }
    .pipe-icon svg { width: 24px; height: 24px; }
    .pipe-title-card { font-size: 18px; font-weight: 700; color: var(--text-color); margin-bottom: 12px; }
    .pipe-desc { font-size: 13px; color: var(--text-muted); line-height: 1.5; }

    /* CTA Section */
    .cta-banner { max-width: 1600px; margin: 0 auto 96px; padding: 64px 48px; border: 1px solid var(--border-color); border-radius: 24px; text-align: center; background: var(--card-bg); position: relative; overflow: hidden; }
    .cta-glow { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 400px; height: 200px; background: radial-gradient(ellipse, rgba(255,213,0,0.1) 0%, transparent 70%); z-index: 0; }
    .cta-content { position: relative; z-index: 10; }
    .cta-title { font-size: 36px; font-weight: 800; color: var(--text-color); margin-bottom: 16px; }
    .cta-desc { color: var(--text-muted); font-size: 16px; max-width: 600px; margin: 0 auto 32px; line-height: 1.6; }
    .cta-btns { display: flex; gap: 16px; justify-content: center; }
</style>
@endsection

@section('content')
<div class="hiw-header">
    <span class="hiw-tag">PROCESS</span>
    <h1 class="hiw-title">How RoadHealth AI <span style="color: #FFD500;">Works</span></h1>
    <p class="hiw-desc">From raw data capture to institutional decision making. Our complete end-to-end data pipeline makes road assessment seamless.</p>
</div>

<!-- Horizontal Flow Section -->
<div class="flow-section">
    <div class="flow-container">
        <!-- Step 1 -->
        <div class="flow-card">
            <span class="flow-step-num">STEP 01</span>
            <h3 class="flow-title">Input Source</h3>
            <div class="flow-subgrid">
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Smartphone Photos
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Dashcam Footage
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Drone Surveys
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Survey Vehicles
                </div>
            </div>
        </div>

        <div class="flow-arrow">&rarr;</div>

        <!-- Step 2 -->
        <div class="flow-card">
            <span class="flow-step-num">STEP 02</span>
            <h3 class="flow-title">Data Ingestion</h3>
            <div class="flow-subgrid">
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
                    Cloud Upload
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    API Pipeline
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Metadata Extraction
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    GPS Alignment
                </div>
            </div>
        </div>

        <div class="flow-arrow">&rarr;</div>

        <!-- Step 3 -->
        <div class="flow-card special">
            <span class="flow-step-num" style="color: #FFD500;">STEP 03</span>
            <h3 class="flow-title" style="color: #FFD500;">AI Model Engine</h3>
            <div class="flow-subgrid">
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    Defect Segmentation
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zM9 16a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                    Distress Classification
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    PCI Grade Calculator
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Anomaly Mitigation
                </div>
            </div>
        </div>

        <div class="flow-arrow">&rarr;</div>

        <!-- Step 4 -->
        <div class="flow-card">
            <span class="flow-step-num">STEP 04</span>
            <h3 class="flow-title">Decision Support</h3>
            <div class="flow-subgrid">
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                    GIS Database
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Maintenance Planner
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Budget Optimizer
                </div>
            </div>
        </div>

        <div class="flow-arrow">&rarr;</div>

        <!-- Step 5 -->
        <div class="flow-card">
            <span class="flow-step-num">STEP 05</span>
            <h3 class="flow-title">Actionable Outcomes</h3>
            <div class="flow-subgrid">
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Repair Orders
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2z"></path></svg>
                    Cost Estimation
                </div>
                <div class="flow-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Analytics Reports
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pipeline Architecture Section -->
<div class="pipe-section">
    <div class="pipe-header">
        <span class="pipe-tag">ARCHITECTURE</span>
        <h2 class="pipe-title">Our Specialized AI Pipeline</h2>
    </div>
    
    <div class="pipe-grid">
        <!-- Card 1 -->
        <div class="pipe-card">
            <div class="pipe-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="pipe-title-card">Image Segmentation</h3>
            <p class="pipe-desc">Extracts road pixel surfaces from background noise, removing sky, trees, vehicles and buildings to isolate pavement condition accurately.</p>
        </div>

        <!-- Card 2 -->
        <div class="pipe-card">
            <div class="pipe-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="pipe-title-card">Anomaly Detection</h3>
            <p class="pipe-desc">Identifies surface distresses in real-time, pinpointing structural anomalies, cracks, potholes, raveling and patching using deep learning.</p>
        </div>

        <!-- Card 3 -->
        <div class="pipe-card">
            <div class="pipe-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <h3 class="pipe-title-card">Severity Classification</h3>
            <p class="pipe-desc">Categorizes structural distresses into low, medium and high severity scales based on dimensions, depth and safety risk parameters.</p>
        </div>

        <!-- Card 4 -->
        <div class="pipe-card">
            <div class="pipe-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <h3 class="pipe-title-card">PCI Grade Calculator</h3>
            <p class="pipe-desc">Computes standardized Pavement Condition Index (PCI) metrics (0-100) per section, calculating structural grade scores seamlessly.</p>
        </div>

        <!-- Card 5 -->
        <div class="pipe-card">
            <div class="pipe-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="pipe-title-card">Maintenance Optimization</h3>
            <p class="pipe-desc">Uses heuristic models to match distresses with specific repair methods and calculates cost estimates based on current municipal rates.</p>
        </div>

        <!-- Card 6 -->
        <div class="pipe-card">
            <div class="pipe-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="pipe-title-card">API Dispatcher</h3>
            <p class="pipe-desc">Sends structured JSON payloads, GPS metadata, overlays and PDF assessment reports to active government GIS systems.</p>
        </div>
    </div>
</div>

<div class="cta-banner">
    <div class="cta-glow"></div>
    <div class="cta-content">
        <h2 class="cta-title">Experience the Speed of AI</h2>
        <p class="cta-desc">Try RoadHealth AI today and see how easy it is to automate your road condition assessments and optimize public infrastructure budgets.</p>
        <div class="cta-btns">
            <button class="btn btn-primary" onclick="window.location.href='/dashboard';" style="padding: 14px 28px; font-weight: 700; font-size: 15px; cursor: pointer;">Create Free Account</button>
            <button class="btn btn-outline" onclick="window.location.href='/dashboard';" style="padding: 14px 28px; font-weight: 700; font-size: 15px; cursor: pointer;">View Interactive Demo</button>
        </div>
    </div>
</div>
@endsection
