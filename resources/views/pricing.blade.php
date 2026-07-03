@extends('layouts.app')

@section('title', 'Pricing')

@section('styles')
<style>
    .pricing-header { text-align: center; padding: 64px 24px 48px; max-width: 800px; margin: 0 auto; }
    .pricing-tag { color: #FFD500; font-size: 11px; font-weight: 800; letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 16px; display: block; }
    .pricing-title { font-size: 48px; font-weight: 800; line-height: 1.1; margin-bottom: 24px; letter-spacing: -0.02em; }
    .pricing-desc { color: var(--text-muted); font-size: 16px; line-height: 1.6; }
    
    /* Toggle */
    .billing-toggle { display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 64px; }
    .toggle-lbl { font-size: 14px; font-weight: 600; color: var(--text-muted); }
    .toggle-switch { width: 56px; height: 32px; background: var(--input-bg); border-radius: 16px; position: relative; cursor: pointer; border: 1px solid var(--border-color); }
    .toggle-knob { position: absolute; top: 4px; left: 4px; width: 22px; height: 22px; background: #FFD500; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.15); }
    .save-badge { background: #FFD500; color: black; font-size: 10px; font-weight: 800; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; }

    /* Pricing Grid */
    .pricing-grid { display: flex; justify-content: center; gap: 16px; padding: 0 24px; max-width: 1600px; margin: 0 auto 64px; align-items: stretch; }
    
    .price-card { flex: 1; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px 24px; display: flex; flex-direction: column; position: relative; transition: transform 0.3s; }
    .price-card:hover { transform: translateY(-5px); border-color: rgba(255,213,0,0.3); }
    
    /* Popular Card */
    .card-popular { border-color: #FFD500; background: linear-gradient(180deg, rgba(255,213,0,0.03) 0%, var(--card-bg) 100%); z-index: 10; box-shadow: 0 20px 40px -10px rgba(255,213,0,0.15); transform: scale(1.02); }
    .card-popular:hover { transform: scale(1.02) translateY(-5px); border-color: #FFD500; }
    .popular-badge { position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #FFD500; color: black; font-size: 10px; font-weight: 800; padding: 6px 16px; border-radius: 12px; letter-spacing: 0.1em; text-transform: uppercase; white-space: nowrap; }
    
    /* Authority Card */
    .card-authority { background: linear-gradient(180deg, rgba(255,213,0,0.05) 0%, rgba(255,213,0,0.01) 100%); border-color: rgba(255,213,0,0.3); }
    .authority-header { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 8px; color: #FFD500; font-size: 14px; font-weight: 800; letter-spacing: 0.05em; }
    .authority-header svg { width: 20px; height: 20px; }
    
    /* Card Content */
    .tier-name { color: var(--text-color); font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
    .card-popular .tier-name, .card-authority .tier-name { text-align: center; color: #FFD500; }
    
    .tier-desc { color: var(--text-muted); font-size: 11px; margin-bottom: 24px; min-height: 16px; }
    .card-authority .tier-desc { text-align: center; }
    
    .price-wrap { display: flex; align-items: baseline; gap: 4px; margin-bottom: 24px; }
    .card-authority .price-wrap { justify-content: center; }
    .price-currency { font-size: 24px; font-weight: 700; color: var(--text-color); }
    .price-amount { font-size: 40px; font-weight: 800; color: var(--text-color); line-height: 1; }
    .price-period { font-size: 12px; color: var(--text-dark-gray); font-weight: 500; }
    
    .btn-plan { width: 100%; padding: 12px; border-radius: 6px; font-size: 13px; font-weight: 700; text-align: center; cursor: pointer; transition: all 0.2s; margin-bottom: 32px; }
    .btn-outline { background: transparent; border: 1px solid var(--btn-outline-border); color: var(--btn-outline-color); }
    .btn-outline:hover { background: var(--btn-outline-hover-bg); }
    .btn-solid { background: #FFD500; border: none; color: black; }
    .btn-solid:hover { background: #facc15; }
    
    .feature-list { display: flex; flex-direction: column; gap: 12px; flex-grow: 1; margin-bottom: 24px; }
    .feat-item { display: flex; align-items: flex-start; gap: 8px; font-size: 11px; color: var(--text-muted); line-height: 1.4; }
    .feat-icon { width: 14px; height: 14px; flex-shrink: 0; margin-top: 1px; }
    .feat-check { color: #FFD500; }
    .feat-cross { color: #ef4444; }
    
    .card-footer { padding-top: 24px; border-top: 1px solid var(--border-color); display: flex; gap: 12px; align-items: flex-start; }
    .card-footer svg { width: 24px; height: 24px; color: #FFD500; flex-shrink: 0; opacity: 0.8; }
    .card-footer p { color: var(--text-dark-gray); font-size: 10px; line-height: 1.5; }

    /* Trust Section */
    .trust-section { max-width: 1600px; margin: 0 auto 64px; padding: 0 24px; display: flex; gap: 24px; align-items: stretch; }
    .trust-badges-container { flex: 2; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 32px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
    .trust-item { display: flex; gap: 16px; align-items: center; }
    .trust-item svg { width: 36px; height: 36px; color: #FFD500; flex-shrink: 0; }
    .trust-item h4 { color: var(--text-color); font-size: 14px; font-weight: 700; margin-bottom: 4px; }
    .trust-item p { color: var(--text-muted); font-size: 11px; line-height: 1.4; }
    
    .custom-plan-box { flex: 1; background: var(--card-bg); border: 1px solid rgba(255,213,0,0.3); border-radius: 12px; padding: 24px; display: flex; flex-direction: column; justify-content: center; }
    .custom-plan-box h4 { color: #FFD500; font-size: 14px; font-weight: 700; margin-bottom: 8px; }
    .custom-plan-box p { color: var(--text-muted); font-size: 11px; line-height: 1.5; margin-bottom: 16px; }
    .btn-custom { background: #FFD500; color: black; font-size: 12px; font-weight: 700; padding: 8px 16px; border: none; border-radius: 4px; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; width: max-content; }
    
    .bottom-note { text-align: center; color: var(--text-dark-gray); font-size: 11px; margin-bottom: 64px; }
</style>
@endsection

@section('content')
<div class="pricing-header">
    <span class="pricing-tag">PRICING PLANS</span>
    <h1 class="pricing-title">Simple, Transparent & <span style="color: #FFD500;">Scalable Pricing</span></h1>
    <p class="pricing-desc">Choose the perfect plan to analyze, manage and improve your road infrastructure with AI.</p>
</div>

<div class="billing-toggle">
    <span class="toggle-lbl" style="color: var(--text-color);">Monthly</span>
    <div class="toggle-switch">
        <div class="toggle-knob"></div>
    </div>
    <span class="toggle-lbl">Yearly</span>
    <span class="save-badge">Save 20%</span>
</div>

<div class="pricing-grid">
    <!-- FREE -->
    <div class="price-card">
        <div class="tier-name" style="color: var(--text-color);">FREE</div>
        <div class="tier-desc">For Individuals Getting Started</div>
        <div class="price-wrap">
            <span class="price-currency">₹</span>
            <span class="price-amount">0</span>
            <span class="price-period">/month</span>
        </div>
        <button class="btn-plan btn-outline">Get Started Free</button>
        
        <div class="feature-list">
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 5 Road Image Analyses / month</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Basic AI Condition Assessment</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Standard Reports</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> History (Last 7 Days)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Download Images & Reports</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Community Support</div>
            <div class="feat-item"><svg class="feat-icon feat-cross" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> No API Access</div>
            <div class="feat-item"><svg class="feat-icon feat-cross" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> No Bulk Upload</div>
            <div class="feat-item"><svg class="feat-icon feat-cross" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> No Priority Support</div>
        </div>
        
        <div class="card-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            <p>Perfect for students, researchers and individuals exploring RoadHealth AI.</p>
        </div>
    </div>

    <!-- LOW TIER -->
    <div class="price-card">
        <div class="tier-name">LOW TIER</div>
        <div class="tier-desc">For Small Teams & Consultants</div>
        <div class="price-wrap">
            <span class="price-currency">₹</span>
            <span class="price-amount">499</span>
            <span class="price-period">/month</span>
        </div>
        <button class="btn-plan btn-solid">Choose Plan</button>
        
        <div class="feature-list">
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 100 Road Image Analyses / month</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Advanced AI Condition Assessment</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Detailed PDFs & Analytics</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> History (Last 3 Months)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Export Data (Excel/CSV)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Email Support</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Basic API Access</div>
            <div class="feat-item"><svg class="feat-icon feat-cross" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Bulk Upload (Limited)</div>
            <div class="feat-item"><svg class="feat-icon feat-cross" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> No Priority Support</div>
        </div>
        
        <div class="card-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <p>Best for small teams, consultants and local agencies.</p>
        </div>
    </div>

    <!-- MEDIUM TIER -->
    <div class="price-card card-popular">
        <div class="popular-badge">MOST POPULAR</div>
        <div class="tier-name">MEDIUM TIER</div>
        <div class="tier-desc">For Growing Organizations</div>
        <div class="price-wrap">
            <span class="price-currency">₹</span>
            <span class="price-amount">1,499</span>
            <span class="price-period">/month</span>
        </div>
        <button class="btn-plan btn-solid">Choose Plan</button>
        
        <div class="feature-list">
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 500 Road Image Analyses / month</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Advanced AI + Crack & Pothole Detection</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Detailed Reports with Recommendations</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> History (Last 12 Months)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Bulk Upload (Up to 500 images)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> GIS Map Integration</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> API Access</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Priority Email & Chat Support</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Team Members (Up to 5)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Custom Branding (Reports)</div>
        </div>
        
        <div class="card-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            <p>Ideal for growing organizations that need more power and collaboration.</p>
        </div>
    </div>

    <!-- TOP TIER -->
    <div class="price-card">
        <div class="tier-name">TOP TIER</div>
        <div class="tier-desc">For Enterprises & Large Projects</div>
        <div class="price-wrap">
            <span class="price-currency">₹</span>
            <span class="price-amount">4,999</span>
            <span class="price-period">/month</span>
        </div>
        <button class="btn-plan btn-solid">Choose Plan</button>
        
        <div class="feature-list">
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited Road Image Analyses</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> AI + All Defect Detection</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Advanced Analytics & Dashboards</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited History</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Bulk Upload (Up to 5,000 images)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> GIS Map + Heatmaps</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Full API Access</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Priority Support (24/7)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Team Members (Up to 20)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Custom Workflows</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Data Export (All Formats)</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> SLA & Uptime Guarantee</div>
        </div>
        
        <div class="card-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <p>Built for enterprises that demand performance, scale and reliability.</p>
        </div>
    </div>

    <!-- AUTHORITY LEVEL -->
    <div class="price-card card-authority">
        <div class="authority-header">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
            AUTHORITY LEVEL
        </div>
        <div class="tier-desc">For Government Authorities Only</div>
        <div class="price-wrap">
            <span class="price-amount" style="font-size: 32px;">Custom Pricing</span>
        </div>
        <button class="btn-plan btn-solid">Contact Sales</button>
        
        <div class="feature-list">
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited Everything</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Nationwide / Citywide Deployment</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Multi-User & Department Access</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Advanced AI + Custom Models</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Real-time Road Monitoring</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> GIS, Heatmaps & Predictive Analytics</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Integration with Government Systems</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Dedicated Account Manager</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> On-Premise / Private Cloud Option</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Training & Capacity Building</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> SLA, Security & Compliance</div>
            <div class="feat-item"><svg class="feat-icon feat-check" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 24/7 Priority Support</div>
        </div>
        
        <div class="card-footer">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <p>Tailored solutions for government agencies to manage roads at scale.</p>
        </div>
    </div>
</div>

<div class="trust-section">
    <div class="trust-badges-container">
        <div class="trust-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            <div>
                <h4>Secure & Reliable</h4>
                <p>Enterprise-grade security to protect your critical data.</p>
            </div>
        </div>
        <div class="trust-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
            <div>
                <h4>Scalable & Flexible</h4>
                <p>Upgrade, downgrade or customize your plan anytime.</p>
            </div>
        </div>
        <div class="trust-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div>
                <h4>Real-time Insights</h4>
                <p>Get instant AI insights to make smarter decisions.</p>
            </div>
        </div>
        <div class="trust-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            <div>
                <h4>Priority Support</h4>
                <p>We're here to help you 24/7, always.</p>
            </div>
        </div>
    </div>
    
    <div class="custom-plan-box">
        <h4>Need a Custom Plan?</h4>
        <p>Contact our team to build a solution that fits your exact requirements.</p>
        <button class="btn-custom">Contact Sales &rarr;</button>
    </div>
</div>

<p class="bottom-note">All plans include access to core AI engine, regular updates and continuous improvements.</p>
@endsection
