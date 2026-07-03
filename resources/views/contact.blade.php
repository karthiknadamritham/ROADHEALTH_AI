@extends('layouts.app')

@section('title', 'Contact Us')

@section('styles')
<style>
    /* Contact Hero Section */
    .contact-hero { position: relative; padding: 100px 48px; border-bottom: 1px solid var(--border-color); overflow: hidden; background: var(--bg-color); min-height: 380px; display: flex; align-items: center; }
    
    .hero-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; overflow: hidden; }
    .hero-bg img { width: 100%; height: 100%; object-fit: cover; opacity: 0.2; }
    .gradient-y { position: absolute; inset: 0; background: linear-gradient(180deg, var(--bg-color) 0%, transparent 40%, transparent 60%, var(--bg-color) 100%); }
    .gradient-x { position: absolute; inset: 0; background: radial-gradient(circle at 50% 50%, transparent 10%, var(--bg-color) 90%); }
    
    .hero-content { position: relative; z-index: 10; max-width: 1600px; margin: 0 auto; width: 100%; display: flex; justify-content: space-between; align-items: center; gap: 48px; }
    
    .hero-text { flex: 1.2; }
    .contact-tag { color: #FFD500; font-size: 11px; font-weight: 800; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 16px; display: block; }
    .contact-title { font-size: 56px; font-weight: 800; color: var(--text-color); letter-spacing: -0.02em; margin-bottom: 24px; line-height: 1.1; }
    .contact-desc { color: var(--text-muted); font-size: 16px; max-width: 550px; line-height: 1.6; }
 
    /* Hero Right Features */
    .hero-features { flex: 1.5; display: flex; gap: 32px; justify-content: flex-end; }
    .feature-item { display: flex; align-items: center; gap: 16px; background: var(--card-bg); border: 1px solid var(--border-color); padding: 16px 24px; border-radius: 12px; backdrop-filter: blur(8px); }
    .feature-icon { width: 44px; height: 44px; border-radius: 50%; border: 1px solid rgba(255,213,0,0.3); display: flex; align-items: center; justify-content: center; color: #FFD500; flex-shrink: 0; background: rgba(255,213,0,0.02); }
    .feature-icon svg { width: 20px; height: 20px; }
    .feature-text { display: flex; flex-direction: column; }
    .feature-title { font-size: 13px; font-weight: 700; color: var(--text-color); margin-bottom: 4px; white-space: nowrap; }
    .feature-desc { font-size: 11px; color: var(--text-muted); line-height: 1.4; max-width: 140px; }
 
    /* Main Section / Grid */
    .main-section { max-width: 1600px; margin: -50px auto 64px; padding: 0 48px; position: relative; z-index: 20; }
    .contact-panel { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; display: grid; grid-template-columns: 1.3fr 1fr 1.3fr; gap: 40px; padding: 48px; box-shadow: 0 30px 60px -15px rgba(0,0,0,0.15); }
 
    /* Form Column */
    .form-title { display: flex; align-items: center; gap: 12px; font-size: 20px; font-weight: 700; color: var(--text-color); margin-bottom: 8px; }
    .form-title svg { width: 24px; height: 24px; color: #FFD500; }
    .form-subtitle { color: var(--text-dark-gray); font-size: 13px; margin-bottom: 32px; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-group { display: flex; flex-direction: column; position: relative; }
    .input-icon { position: absolute; left: 16px; top: 14px; width: 16px; height: 16px; color: var(--text-dark-gray); pointer-events: none; }
    .form-input { background: var(--input-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 14px 16px 14px 44px; color: var(--text-color); font-size: 13px; font-family: 'Inter', sans-serif; outline: none; transition: all 0.2s; }
    .form-input:focus { border-color: #FFD500; background: rgba(255,213,0,0.02); }
    .form-textarea { min-height: 140px; resize: none; }
    
    .form-btn { background: #FFD500; color: black; border: none; border-radius: 8px; padding: 14px 28px; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.2s; width: 100%; }
    .form-btn:hover { background: #facc15; }
    .form-btn svg { width: 16px; height: 16px; }
 
    /* Info Column */
    .info-title { display: flex; align-items: center; gap: 12px; font-size: 20px; font-weight: 700; color: var(--text-color); margin-bottom: 8px; }
    .info-title svg { width: 24px; height: 24px; color: #FFD500; }
    .info-subtitle { color: var(--text-dark-gray); font-size: 13px; margin-bottom: 32px; }
    
    .info-list { display: flex; flex-direction: column; gap: 24px; }
    .info-item { display: flex; align-items: flex-start; gap: 16px; }
    .info-icon { width: 36px; height: 36px; border-radius: 8px; background: rgba(255,213,0,0.05); border: 1px solid rgba(255,213,0,0.15); display: flex; align-items: center; justify-content: center; color: #FFD500; flex-shrink: 0; }
    .info-icon svg { width: 18px; height: 18px; }
    .info-text { display: flex; flex-direction: column; gap: 4px; }
    .info-lbl { font-size: 14px; font-weight: 700; color: var(--text-color); }
    .info-val { font-size: 13px; color: var(--text-muted); line-height: 1.4; }
    .info-subtext { font-size: 11px; color: var(--text-dark-gray); }
 
    /* Map Column */
    .map-box { border-radius: 12px; overflow: hidden; position: relative; border: 1px solid var(--border-color); background: var(--input-bg); display: flex; flex-direction: column; height: 100%; min-height: 400px; }
    .map-bg { flex-grow: 1; position: relative; overflow: hidden; }
    .map-pin { position: absolute; top: 52%; left: 48%; transform: translate(-50%, -50%); display: flex; flex-direction: column; align-items: center; z-index: 10; }
    .map-pin svg { width: 28px; height: 28px; color: #FFD500; filter: drop-shadow(0 0 8px rgba(255,213,0,0.6)); }
    
    .map-tooltip { position: absolute; bottom: 62%; left: 48%; transform: translateX(-50%); background: var(--card-bg); border: 1px solid rgba(255,213,0,0.3); padding: 12px; border-radius: 8px; z-index: 20; width: 210px; backdrop-filter: blur(8px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .map-tooltip-title { font-size: 12px; font-weight: 700; color: #FFD500; margin-bottom: 4px; }
    .map-tooltip-desc { font-size: 11px; color: var(--text-muted); line-height: 1.4; }
    
    .map-footer { border-top: 1px solid var(--border-color); padding: 16px; background: var(--card-bg); display: flex; justify-content: center; }
    .btn-outline-large { width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; font-size: 13px; font-weight: 600; color: var(--text-color); background: transparent; border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s; }
    .btn-outline-large:hover { background: var(--btn-outline-hover-bg); border-color: var(--border-color); }
    .btn-outline-large svg { width: 16px; height: 16px; color: #FFD500; }
 
    /* Bottom Quick Actions Grid */
    .actions-section { max-width: 1600px; margin: 0 auto 80px; padding: 0 48px; }
    .actions-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .action-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; display: flex; flex-direction: column; transition: all 0.2s; position: relative; }
    .action-card:hover { border-color: rgba(255,213,0,0.3); transform: translateY(-3px); }
    
    .action-icon { width: 44px; height: 44px; border-radius: 10px; background: rgba(255,213,0,0.05); display: flex; align-items: center; justify-content: center; color: #FFD500; margin-bottom: 16px; }
    .action-icon svg { width: 22px; height: 22px; }
    .action-title { font-size: 15px; font-weight: 700; color: var(--text-color); margin-bottom: 8px; }
    .action-desc { font-size: 12px; color: var(--text-muted); line-height: 1.5; margin-bottom: 20px; flex-grow: 1; }
    
    .btn-yellow-link { display: inline-flex; align-items: center; gap: 6px; background: #FFD500; color: black; font-size: 12px; font-weight: 700; padding: 8px 16px; border: none; border-radius: 6px; cursor: pointer; transition: all 0.2s; width: fit-content; }
    .btn-yellow-link:hover { background: #facc15; }
    .btn-yellow-link svg { width: 14px; height: 14px; }
</style>
@endsection

@section('content')
<!-- Contact Hero -->
<div class="contact-hero">
    <div class="hero-bg">
        <img src="/images/road-bg.png" alt="Road Background" />
        <div class="gradient-x"></div>
        <div class="gradient-y"></div>
    </div>
    
    <div class="hero-content">
        <div class="hero-text">
            <span class="contact-tag">CONTACT US</span>
            <h1 class="contact-title">We're Here <span style="color: #FFD500;">to Help</span></h1>
            <p class="contact-desc">Have questions or need support? Reach out to our team or connect with your nearest authorities. We're always happy to help.</p>
        </div>
        
        <div class="hero-features">
            <!-- Feature 1 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div class="feature-text">
                    <span class="feature-title">Customer Support</span>
                    <span class="feature-desc">We're available 24/7 to assist you.</span>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div class="feature-text">
                    <span class="feature-title">Email Us</span>
                    <span class="feature-desc">Drop us an email anytime.</span>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="feature-text">
                    <span class="feature-title">Response Time</span>
                    <span class="feature-desc">We typically reply within 2 hours.</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Contact Grid -->
<div class="main-section">
    <div class="contact-panel">
        
        <!-- Left: Send Message Form -->
        <div>
            <div class="form-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                Send Us a Message
            </div>
            <p class="form-subtitle">Fill out the form below and our team will get back to you shortly.</p>
            
            @php
                $user = auth()->user();
            @endphp
            <form id="contact-form" action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <input type="text" name="name" placeholder="Full Name" class="form-input" value="{{ $user ? $user->name : '' }}" {{ $user ? 'readonly' : '' }} required />
                    </div>
                    <div class="form-group">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <input type="email" name="email" placeholder="Email Address" class="form-input" value="{{ $user ? $user->email : '' }}" {{ $user ? 'readonly' : '' }} required />
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <input type="tel" name="phone" placeholder="Phone Number" class="form-input" value="{{ $user ? $user->phone : '' }}" />
                    </div>
                    <div class="form-group">
                        <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <input type="text" name="subject" placeholder="Subject" class="form-input" required />
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <select name="territory" class="form-input" style="appearance: none; -webkit-appearance: none; padding-right: 40px; background-color: var(--input-bg);" required>
                        <option value="" disabled selected>Select Target Municipality Jurisdiction...</option>
                        <option value="Delhi Municipal">Delhi Municipal Jurisdiction</option>
                        <option value="Bengaluru Municipal">Bengaluru Municipal Jurisdiction</option>
                        <option value="Mumbai Municipal">Mumbai Municipal Jurisdiction</option>
                        <option value="Chennai Municipal">Chennai Municipal Jurisdiction</option>
                        <option value="Other Municipal">Other Municipal Jurisdiction</option>
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 24px;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="top: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    <textarea name="message" placeholder="Message" class="form-input form-textarea" required></textarea>
                </div>
                
                <button type="submit" class="form-btn">
                    Send Message
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>

        <!-- Middle: Contact Info -->
        <div>
            <div class="info-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Contact Information
            </div>
            <p class="info-subtitle">Choose the best way to reach us.</p>
            
            <div class="info-list">
                <!-- item 1 -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="info-text">
                        <span class="info-lbl">General Inquiries</span>
                        <span class="info-val">info@roadhealthai.com</span>
                        <span class="info-subtext">For general questions and information</span>
                    </div>
                </div>
                
                <!-- item 2 -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="info-text">
                        <span class="info-lbl">Sales & Partnerships</span>
                        <span class="info-val">sales@roadhealthai.com</span>
                        <span class="info-subtext">For business inquiries and partnerships</span>
                    </div>
                </div>
                
                <!-- item 3 -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div class="info-text">
                        <span class="info-lbl">Support Team</span>
                        <span class="info-val">support@roadhealthai.com</span>
                        <span class="info-subtext">For technical support and assistance</span>
                    </div>
                </div>
                
                <!-- item 4 -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div class="info-text">
                        <span class="info-lbl">Phone</span>
                        <span class="info-val">+91 98765 43210</span>
                        <span class="info-subtext">Mon - Sat: 9:00 AM - 6:00 PM IST</span>
                    </div>
                </div>
                
                <!-- item 5 -->
                <div class="info-item">
                    <div class="info-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    </div>
                    <div class="info-text">
                        <span class="info-lbl">Office Address</span>
                        <span class="info-val">RoadHealth AI Technologies Pvt. Ltd.</span>
                        <span class="info-subtext">Bangalore, Karnataka, India - 560001</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Map Panel -->
        <div class="map-box">
            <div class="map-bg">
                <!-- Custom theme-aware SVG Map -->
                <svg id="map-svg" viewBox="0 0 400 300" style="width: 100%; height: 100%;">
                    <rect width="400" height="300" fill="var(--input-bg)"/>
                    
                    <!-- Grid lines -->
                    <line x1="0" y1="50" x2="400" y2="50" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="0" y1="100" x2="400" y2="100" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="0" y1="150" x2="400" y2="150" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="0" y1="200" x2="400" y2="200" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="0" y1="250" x2="400" y2="250" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="50" y1="0" x2="50" y2="300" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="100" y1="0" x2="100" y2="300" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="150" y1="0" x2="150" y2="300" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="200" y1="0" x2="200" y2="300" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="250" y1="0" x2="250" y2="300" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="300" y1="0" x2="300" y2="300" stroke="var(--border-color)" stroke-width="1"/>
                    <line x1="350" y1="0" x2="350" y2="300" stroke="var(--border-color)" stroke-width="1"/>

                    <!-- Water bodies -->
                    <path d="M 0,220 Q 50,210 100,240 T 200,250 L 200,300 L 0,300 Z" fill="var(--btn-social-bg)" opacity="0.5"/>
                    <path d="M 320,50 Q 350,30 380,60 T 400,100 L 400,0 L 300,0 Z" fill="var(--btn-social-bg)" opacity="0.5"/>

                    <!-- Road Network -->
                    <path d="M 50,0 Q 150,120 200,150 T 350,300" fill="none" stroke="var(--border-color)" stroke-width="4"/>
                    <path d="M 50,0 Q 150,120 200,150 T 350,300" fill="none" stroke="var(--text-muted)" stroke-dasharray="5,5" stroke-width="1"/>
                    <path d="M 0,150 Q 120,50 200,150 T 400,150" fill="none" stroke="var(--border-color)" stroke-width="3"/>
                    
                    <path d="M 80,0 L 80,300" fill="none" stroke="var(--border-color)" stroke-width="1.5"/>
                    <path d="M 280,0 L 280,300" fill="none" stroke="var(--border-color)" stroke-width="1.5"/>
                    <path d="M 0,100 L 400,100" fill="none" stroke="var(--border-color)" stroke-width="1.5"/>
                    <path d="M 0,200 L 400,200" fill="none" stroke="var(--border-color)" stroke-width="1.5"/>
                    
                    <!-- Neighborhood labels -->
                    <text x="50" y="240" fill="var(--text-muted)" font-size="8" font-family="sans-serif" font-weight="bold">JAYANAGAR</text>
                    <text x="250" y="270" fill="var(--text-muted)" font-size="8" font-family="sans-serif" font-weight="bold">BTM LAYOUT</text>
                    <text x="310" y="190" fill="var(--text-muted)" font-size="8" font-family="sans-serif" font-weight="bold">HSR LAYOUT</text>
                    <text x="290" y="40" fill="var(--text-muted)" font-size="8" font-family="sans-serif" font-weight="bold">KORAMANGALA</text>
                    <text x="220" y="80" fill="var(--text-muted)" font-size="8" font-family="sans-serif" font-weight="bold">HEBBAL</text>
                    <text x="40" y="80" fill="var(--text-muted)" font-size="8" font-family="sans-serif" font-weight="bold">ASRANGAR</text>
                    <text x="320" y="130" fill="var(--text-muted)" font-size="8" font-family="sans-serif">WHITEFIELD</text>
                </svg>
                
                <!-- Active Map Pin -->
                <div class="map-pin">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>

                <!-- Tooltip -->
                <div class="map-tooltip">
                    <div class="map-tooltip-title">RoadHealth AI Technologies</div>
                    <div class="map-tooltip-desc">Bangalore, Karnataka, India<br>560001</div>
                </div>
            </div>
            
            <div class="map-footer">
                <button id="btn-get-directions" class="btn-outline-large">
                    Get Directions
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Quick Actions Grid -->
<div class="actions-section">
    <div class="actions-grid">
        <!-- Box 1 -->
        <div class="action-card">
            <div class="action-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="action-title">Nearest Authorities</h3>
            <p class="action-desc">Connect with your local government authorities for road related queries.</p>
            <button class="btn-yellow-link" onclick="alert('Scanning local GPS coordinates... Connected successfully to Municipal Corporation Delhi (1.2 km) and NHAI (4.5 km).');">
                Find Authorities
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- Box 2 -->
        <div class="action-card">
            <div class="action-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="action-title">Report an Issue</h3>
            <p class="action-desc">Found a road issue? Report it directly to the concerned department.</p>
            <button class="btn-yellow-link" onclick="window.location.href='/dashboard';">
                Report Now
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- Box 3 -->
        <div class="action-card">
            <div class="action-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="action-title">Email Authority</h3>
            <p class="action-desc">Contact the nearest government authority via email.</p>
            <button class="btn-yellow-link" onclick="alert('Opening official municipal mail dispatch gateway (dispatch@mcd.gov.in)... Form data prepared.');">
                Email Now
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- Box 4 -->
        <div class="action-card">
            <div class="action-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            </div>
            <h3 class="action-title">Call Authority</h3>
            <p class="action-desc">Speak directly with the road maintenance department.</p>
            <button class="btn-yellow-link" onclick="alert('Dialing road maintenance dispatch... Official Helpline: +91 98765 43210. Redirecting call.');">
                Make a Call
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    </div>
</div>

<style>
@keyframes routeAnim {
    to {
        stroke-dashoffset: -20;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Contact Form Submission (AJAX & Toast integration)
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Sending...';
            
            const formData = new FormData(contactForm);
            
            fetch('/contact', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                
                if (data.success) {
                    showToast('success', data.message);
                    contactForm.reset();
                } else {
                    showToast('error', data.error || 'Failed to send message.');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                let errMsg = 'An error occurred. Please try again.';
                if (error.errors) {
                    errMsg = Object.values(error.errors).flat().join(' ');
                } else if (error.message) {
                    errMsg = error.message;
                }
                showToast('error', errMsg);
                console.error('Error:', error);
            });
        });
    }

    function showToast(type, message) {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.cssText = 'position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px;';
            document.body.appendChild(toastContainer);
        }
        
        const toast = document.createElement('div');
        toast.style.cssText = `
            background: rgba(18, 18, 18, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid ${type === 'success' ? '#10b981' : '#ef4444'};
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            color: white;
            padding: 16px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            max-width: 450px;
            transform: translateX(120%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        `;
        
        const icon = type === 'success' 
            ? '<svg fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px; flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            : '<svg fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24" style="width: 20px; height: 20px; flex-shrink: 0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            
        toast.innerHTML = `
            ${icon}
            <div style="flex-grow: 1;">
                <div style="font-weight: 700; margin-bottom: 2px;">${type === 'success' ? 'Success' : 'Error'}</div>
                <div style="color: #9ca3af; font-size: 12px; line-height: 1.4;">${message}</div>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Trigger reflow
        toast.offsetHeight;
        toast.style.transform = 'translateX(0)';
        
        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }

    // 2. Map Directions Overlay Animation
    const btnDirections = document.getElementById('btn-get-directions');
    const mapSvg = document.getElementById('map-svg');
    if (btnDirections && mapSvg) {
        btnDirections.addEventListener('click', function () {
            // Check if route already exists
            let existingRoute = document.getElementById('dynamic-route');
            if (existingRoute) {
                existingRoute.remove();
                btnDirections.innerHTML = 'Get Directions <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>';
                return;
            }

            // Create an animated road path from Jayanagar to Pin
            const routePath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            routePath.setAttribute('id', 'dynamic-route');
            routePath.setAttribute('d', 'M 80,240 Q 150,200 192,156');
            routePath.setAttribute('fill', 'none');
            routePath.setAttribute('stroke', '#FFD500');
            routePath.setAttribute('stroke-width', '3');
            routePath.setAttribute('stroke-dasharray', '6');
            routePath.setAttribute('style', 'filter: drop-shadow(0 0 5px rgba(255,213,0,0.8)); animation: routeAnim 1.5s linear infinite;');

            mapSvg.appendChild(routePath);
            btnDirections.innerHTML = 'Clear Route <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        });
    }
});
</script>
@endsection
