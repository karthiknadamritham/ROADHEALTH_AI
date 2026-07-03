@php $hideFooter = true; @endphp
@extends('layouts.app')
@section('title', 'Upload & Analyze Road Image')

@section('styles')
<style>
    body { background: #050505; overflow: hidden; }
    .upload-layout { display: flex; height: calc(100vh - 73px); }

    /* Sidebar — same as dashboard */
    .sidebar { width: 220px; min-width: 220px; background: #0a0a0a; border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-nav { padding: 12px 10px; flex-grow: 1; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.12); color: #FFD500; font-weight: 700; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-badge { margin-left: auto; background: #FFD500; color: black; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 10px; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 10px; }

    /* Main content */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
    .topbar { height: 64px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; padding: 0 32px; background: #0a0a0a; flex-shrink: 0; gap: 12px; }
    .topbar h2 { color: white; font-size: 18px; font-weight: 700; }
    .topbar-sub { color: #6b7280; font-size: 13px; }

    .upload-content { flex-grow: 1; overflow-y: auto; padding: 40px; display: flex; gap: 32px; align-items: flex-start; }

    /* Upload Card */
    .upload-card { flex: 1; background: #0a0a0a; border: 1px solid rgba(255,255,255,0.06); border-radius: 16px; padding: 36px; display: flex; flex-direction: column; gap: 24px; }
    .card-title { font-size: 18px; font-weight: 800; color: white; display: flex; align-items: center; gap: 10px; }
    .card-title svg { width: 22px; height: 22px; color: #FFD500; }

    /* Drop zone */
    .drop-zone { border: 2px dashed rgba(255,213,0,0.25); border-radius: 12px; padding: 48px 24px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; cursor: pointer; transition: all 0.3s; position: relative; background: rgba(255,213,0,0.01); min-height: 240px; }
    .drop-zone:hover, .drop-zone.drag-over { border-color: #FFD500; background: rgba(255,213,0,0.04); }
    .drop-zone input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .drop-icon { width: 56px; height: 56px; color: #FFD500; }
    .drop-title { color: white; font-size: 16px; font-weight: 700; }
    .drop-sub { color: #6b7280; font-size: 13px; text-align: center; line-height: 1.5; }
    .drop-formats { display: flex; gap: 8px; margin-top: 4px; }
    .format-badge { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; padding: 3px 8px; font-size: 11px; color: #9ca3af; font-weight: 600; }

    /* Preview */
    #preview-box { display: none; flex-direction: column; gap: 12px; }
    #preview-img { width: 100%; height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1); }
    .preview-name { font-size: 12px; color: #9ca3af; }

    /* Location input */
    .form-label { font-size: 12px; font-weight: 700; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; display: block; }
    .form-input { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px 16px; color: white; font-size: 14px; outline: none; transition: border-color 0.2s; font-family: 'Inter', sans-serif; }
    .form-input:focus { border-color: rgba(255,213,0,0.4); }
    .form-input::placeholder { color: #4b5563; }

    /* Submit button */
    .btn-analyze { width: 100%; background: #FFD500; color: black; border: none; border-radius: 8px; padding: 15px; font-size: 15px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.2s; letter-spacing: 0.02em; }
    .btn-analyze:hover { background: #facc15; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(255,213,0,0.25); }
    .btn-analyze:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }
    .btn-analyze svg { width: 20px; height: 20px; }

    /* Error alert */
    .error-alert { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 8px; padding: 12px 16px; color: #ef4444; font-size: 13px; font-weight: 600; }

    /* Info panel */
    .info-panel { width: 340px; display: flex; flex-direction: column; gap: 20px; }
    .info-card { background: #0a0a0a; border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; padding: 24px; }
    .info-card-title { font-size: 14px; font-weight: 700; color: white; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    .info-card-title svg { width: 16px; height: 16px; color: #FFD500; }

    .how-steps { display: flex; flex-direction: column; gap: 14px; }
    .step { display: flex; gap: 12px; align-items: flex-start; }
    .step-num { width: 24px; height: 24px; border-radius: 50%; background: rgba(255,213,0,0.1); border: 1px solid rgba(255,213,0,0.3); color: #FFD500; font-size: 11px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .step-text { font-size: 13px; color: #9ca3af; line-height: 1.4; }
    .step-text strong { color: white; }

    .detect-list { display: flex; flex-direction: column; gap: 8px; }
    .detect-item { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #d1d5db; }
    .detect-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }



    /* Loading overlay */
    .loading-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.9); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 9999; flex-direction: column; gap: 24px; }
    .loading-overlay.active { display: flex; }
    .loading-spinner { width: 64px; height: 64px; border: 3px solid rgba(255,213,0,0.2); border-top-color: #FFD500; border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-title { color: white; font-size: 20px; font-weight: 800; letter-spacing: 0.02em; }
    .loading-sub { color: #9ca3af; font-size: 14px; text-align: center; }
    .loading-steps { display: flex; flex-direction: column; gap: 8px; margin-top: 8px; }
    .loading-step { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #6b7280; transition: color 0.3s; }
    .loading-step.done { color: #10b981; }
    .loading-step svg { width: 14px; height: 14px; }
    
    /* Map Styles */
    #upload-map {
        height: 250px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.1);
        margin-top: 8px;
    }

    /* Custom Alert Modal */
    .custom-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.25s ease; z-index: 9999;
    }
    .custom-modal-overlay.active { opacity: 1; pointer-events: auto; }
    .custom-modal-content {
        background: rgba(15, 15, 15, 0.95); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 32px; width: 90%; max-width: 420px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.6); transform: scale(0.9); transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .custom-modal-overlay.active .custom-modal-content { transform: scale(1); }
    .modal-icon {
        width: 56px; height: 56px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;
    }
    .modal-icon svg { width: 28px; height: 28px; color: #ef4444; }
    .custom-modal-content h3 { color: white; font-size: 18px; font-weight: 800; margin-bottom: 10px; }
    .custom-modal-content p { color: #9ca3af; font-size: 13px; line-height: 1.6; margin-bottom: 24px; }
    .modal-btn-confirm {
        width: 100%; background: #ef4444; border: none; color: white; border-radius: 8px; padding: 12px 0; font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s;
    }
    .modal-btn-confirm:hover { background: #dc2626; }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endsection

@section('content')
<div class="upload-layout">
    @include('partials.sidebar')

    <!-- Main -->
    <main class="main-area">
        <div class="topbar">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;color:#FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            <h2>Upload & Analyze</h2>
            <span class="topbar-sub">— AI Pavement Condition Scan</span>
        </div>

        <div class="upload-content">
            <!-- Upload Form Card -->
            <div class="upload-card">
                <div class="card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    Road Image Analysis
                </div>

                @if($errors->has('ai'))
                    <div class="error-alert">
                        ⚠️ {{ $errors->first('ai') }}
                    </div>
                @endif
                @if($errors->has('road_image'))
                    <div class="error-alert">
                        {{ $errors->first('road_image') }}
                    </div>
                @endif

                <form id="upload-form" method="POST" action="{{ route('analyze') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Location (Step 1) -->
                    <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px;">
                        <label class="form-label" style="font-size: 14px; font-weight: 700; color: #FFD500;">Step 1: Pin Location (For Manual Uploads)</label>
                        <p style="font-size: 11px; color: #9ca3af; margin-top: -4px;">Required only if you are uploading an existing image. The live camera feature will auto-pin your location.</p>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" class="form-input" id="location-search" placeholder="E.g., MG Road, Bangalore" style="flex-grow: 1;">
                            <button type="button" id="btn-search-loc" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 8px; padding: 0 16px; cursor: pointer; transition: background 0.2s;">Search</button>
                        </div>
                        <div id="upload-map"></div>
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        <input type="hidden" name="location" id="location" value="Unknown Location">
                    </div>

                    <!-- Upload (Step 2) -->
                    <label class="form-label" style="font-size: 14px; font-weight: 700; color: #FFD500; margin-bottom: 8px; display: block;">Step 2: Upload or Capture Image</label>
                    
                    <div id="upload-actions-wrap" style="display: flex; gap: 16px; margin-bottom: 24px;">
                        <!-- Drop Zone -->
                        <div class="drop-zone" id="drop-zone" style="flex: 1; margin: 0;">
                            <input type="file" name="road_image" id="road-image-input" accept="image/*" required>
                            <svg class="drop-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <div class="drop-title">Drag & Drop</div>
                            <div class="drop-sub">or click to browse</div>
                        </div>

                        <!-- Camera Zone -->
                        <div id="camera-zone" style="flex: 1; border: 1px dashed rgba(255,213,0,0.4); border-radius: 12px; background: rgba(255,213,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; padding: 24px;">
                            <svg fill="none" stroke="#FFD500" viewBox="0 0 24 24" style="width: 32px; height: 32px; margin-bottom: 12px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div style="color: white; font-weight: 700; margin-bottom: 4px;">Use Camera</div>
                            <div style="color: #9ca3af; font-size: 11px; text-align: center;">Requires live location</div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div id="preview-box" style="margin-bottom: 24px;">
                        <img id="preview-img" src="" alt="Preview">
                        <div class="preview-name" id="preview-name"></div>
                        <button type="button" id="btn-clear" style="background:transparent;border:1px solid rgba(255,255,255,0.1);color:#9ca3af;padding:6px 12px;border-radius:6px;font-size:12px;cursor:pointer;">✕ Remove Image</button>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-analyze" id="btn-analyze" disabled>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Run AI Analysis
                    </button>
                </form>
            </div>

            <!-- Info Panel -->
            <div class="info-panel">


                <!-- How it works -->
                <div class="info-card">
                    <div class="info-card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        How It Works
                    </div>
                    <div class="how-steps">
                        <div class="step">
                            <div class="step-num">1</div>
                            <div class="step-text"><strong>Upload</strong> a road photo taken from any device</div>
                        </div>
                        <div class="step">
                            <div class="step-num">2</div>
                            <div class="step-text"><strong>AI Analyzes</strong> the image via the Python YOLO engine</div>
                        </div>
                        <div class="step">
                            <div class="step-num">3</div>
                            <div class="step-text"><strong>Get PCI Score</strong>, severity, and detected defect types</div>
                        </div>
                        <div class="step">
                            <div class="step-num">4</div>
                            <div class="step-text"><strong>Report Saved</strong> to database for dashboard tracking</div>
                        </div>
                    </div>
                </div>

                <!-- What gets detected -->
                <div class="info-card">
                    <div class="info-card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        AI Detects
                    </div>
                    <div class="detect-list">
                        <div class="detect-item"><div class="detect-dot" style="background:#ef4444;"></div>Potholes</div>
                        <div class="detect-item"><div class="detect-dot" style="background:#f59e0b;"></div>Alligator Cracks</div>
                        <div class="detect-item"><div class="detect-dot" style="background:#f59e0b;"></div>Longitudinal Cracks</div>
                        <div class="detect-item"><div class="detect-dot" style="background:#6b7280;"></div>Transverse Cracks</div>
                        <div class="detect-item"><div class="detect-dot" style="background:#3b82f6;"></div>Surface Wear</div>
                        <div class="detect-item"><div class="detect-dot" style="background:#8b5cf6;"></div>Patched Areas</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-title">Analyzing Road Image...</div>
    <div class="loading-sub">AI model is processing your upload</div>
    <div class="loading-steps">
        <div class="loading-step" id="step-upload">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Uploading image to server...
        </div>
        <div class="loading-step" id="step-ai">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Running AI defect detection...
        </div>
        <div class="loading-step" id="step-save">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Saving report to database...
        </div>
    </div>
</div>

<!-- Custom Error Modal -->
<div class="custom-modal-overlay" id="error-modal">
    <div class="custom-modal-content">
        <div class="modal-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3 id="error-modal-title">Action Required</h3>
        <p id="error-modal-message"></p>
        <button class="modal-btn-confirm" onclick="document.getElementById('error-modal').classList.remove('active')">Understood</button>
    </div>
</div>

<script>
function showCustomAlert(title, message) {
    document.getElementById('error-modal-title').innerText = title;
    document.getElementById('error-modal-message').innerHTML = message;
    document.getElementById('error-modal').classList.add('active');
}
const fileInput   = document.getElementById('road-image-input');
const dropZone    = document.getElementById('drop-zone');
const previewBox  = document.getElementById('preview-box');
const previewImg  = document.getElementById('preview-img');
const previewName = document.getElementById('preview-name');
const btnAnalyze  = document.getElementById('btn-analyze');
const btnClear    = document.getElementById('btn-clear');
const form        = document.getElementById('upload-form');
const loadingOverlay = document.getElementById('loading-overlay');

function showPreview(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        previewName.textContent = `${file.name}  (${(file.size/1024/1024).toFixed(2)} MB)`;
        document.getElementById('upload-actions-wrap').style.display = 'none';
        previewBox.style.display = 'flex';
        btnAnalyze.disabled = false;
    };
    reader.readAsDataURL(file);
}

// Enforce Location Selection Before Upload
fileInput.addEventListener('click', function(e) {
    if (!document.getElementById('latitude').value) {
        e.preventDefault();
        showCustomAlert("Location Required", "Please pin the exact location on the map (Step 1) before uploading an image.");
    }
});

fileInput.addEventListener('change', () => showPreview(fileInput.files[0]));

dropZone.addEventListener('dragover', e => { 
    e.preventDefault(); 
    if(document.getElementById('latitude').value) {
        dropZone.classList.add('drag-over'); 
    }
});
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    if (!document.getElementById('latitude').value) {
        showCustomAlert("Location Required", "Please pin the exact location on the map (Step 1) before dropping an image.");
        return;
    }
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        showPreview(file);
    }
});

// Camera Integration with Auto Live Location
document.getElementById('camera-zone').addEventListener('click', function() {
    if (navigator.geolocation) {
        const ogHtml = this.innerHTML;
        this.innerHTML = '<div style="color:#FFD500; font-size:12px; font-weight:700;">Acquiring live location...</div>';
        
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('camera-zone').innerHTML = ogHtml;
            
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            
            // Pin to map
            map.flyTo([lat, lng], 16);
            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);
            
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('location').value = "Live Camera Location";

            // Open mobile camera natively
            var camInput = document.createElement('input');
            camInput.type = 'file';
            camInput.accept = 'image/*';
            camInput.capture = 'environment';
            camInput.onchange = function(e) {
                if (camInput.files.length > 0) {
                    const dt = new DataTransfer();
                    dt.items.add(camInput.files[0]);
                    fileInput.files = dt.files; // sync with main form input
                    showPreview(camInput.files[0]);
                }
            };
            camInput.click();
        }, function(error) {
            document.getElementById('camera-zone').innerHTML = ogHtml;
            showCustomAlert(
                "Permission Blocked", 
                "Live location access is required to use the camera, but your browser is blocking it.<br><br><strong style='color: white;'>How to fix:</strong><br>Click the 🔒 lock icon next to the URL at the top of your browser, change Location to 'Allow', and refresh the page."
            );
        });
    } else {
        showCustomAlert("Not Supported", "Geolocation is not supported by your browser.");
    }
});

btnClear.addEventListener('click', () => {
    fileInput.value = '';
    previewBox.style.display = 'none';
    document.getElementById('upload-actions-wrap').style.display = 'flex';
    btnAnalyze.disabled = true;
});

form.addEventListener('submit', () => {
    loadingOverlay.classList.add('active');
    // Animate steps
    setTimeout(() => document.getElementById('step-upload').classList.add('done'), 600);
    setTimeout(() => document.getElementById('step-ai').classList.add('done'), 1800);
    setTimeout(() => document.getElementById('step-save').classList.add('done'), 2800);
});
</script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('upload-map').setView([28.6139, 77.2090], 11);
    var marker = null;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    
    // Attempt to geolocate user to center map
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            map.setView([position.coords.latitude, position.coords.longitude], 13);
        });
    }

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker([lat, lng]).addTo(map);

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Optional: reverse geocode to get name, but for now just use "Map Selection"
        if(document.getElementById('location').value === "Unknown Location" && document.getElementById('location-search').value === "") {
             document.getElementById('location').value = lat.toFixed(4) + ", " + lng.toFixed(4);
        }
    });

    // Location Search Logic using Nominatim
    document.getElementById('btn-search-loc').addEventListener('click', searchLocation);
    document.getElementById('location-search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchLocation();
        }
    });

    function searchLocation() {
        var query = document.getElementById('location-search').value;
        if (!query) return;

        var btn = document.getElementById('btn-search-loc');
        btn.textContent = "...";

        fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=5&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                btn.textContent = "Search";
                if (data && data.length > 0) {
                    // Try to find an exact place node (city/town/village) instead of a large administrative boundary
                    var bestResult = data.find(d => d.class === 'place') || data[0];

                    var lat = parseFloat(bestResult.lat);
                    var lon = parseFloat(bestResult.lon);
                    var name = bestResult.display_name.split(',')[0]; // get the primary name part

                    map.flyTo([lat, lon], 14);

                    if (marker) map.removeLayer(marker);
                    marker = L.marker([lat, lon]).addTo(map);
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;
                    document.getElementById('location').value = name;
                } else {
                    showCustomAlert("Not Found", "Location not found. Try a different search term.");
                }
            })
            .catch(err => {
                btn.textContent = "Search";
                console.error("Geocoding error:", err);
                showCustomAlert("Error", "Network error occurred while searching location. Please try again.");
            });
    }
</script>
@endsection
