@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'Road Network Map')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    body { background: #050505; overflow: hidden; font-family: 'Inter', sans-serif; }
    .network-layout { display: flex; height: calc(100vh - 73px); }

    /* Sidebar - Copy from dashboard */
    .sidebar { width: 220px; min-width: 220px; background: #0a0a0a; border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; flex-shrink: 0; }
    .sidebar-nav { padding: 12px 10px; flex-grow: 1; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
    .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #9ca3af; font-size: 13px; font-weight: 500; border-radius: 8px; transition: all 0.2s; cursor: pointer; text-decoration: none; }
    .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
    .nav-item.active { background: rgba(255,213,0,0.12); color: #FFD500; font-weight: 700; }
    .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .nav-badge { margin-left: auto; background: #FFD500; color: black; font-size: 9px; font-weight: 800; padding: 2px 6px; border-radius: 10px; }
    .nav-divider { height: 1px; background: rgba(255,255,255,0.05); margin: 6px 10px; }
    .nav-badge.ai { background: rgba(255,213,0,0.15); color: #FFD500; border: 1px solid rgba(255,213,0,0.3); }

    /* Main Area */
    .main-area { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; position: relative; }
    
    /* Topbar */
    .topbar { height: 64px; border-bottom: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; padding: 0 32px; background: #0a0a0a; flex-shrink: 0; justify-content: space-between; }
    .topbar h2 { color: white; font-size: 18px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
    .topbar-actions { display: flex; align-items: center; gap: 24px; }
    .legend-box { display: flex; align-items: center; gap: 16px; }
    .legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #9ca3af; }
    
    .theme-toggle { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 4px; display: flex; align-items: center; cursor: pointer; position: relative; width: 64px; height: 30px; }
    .theme-toggle::before { content: ''; position: absolute; left: 4px; top: 4px; width: 20px; height: 20px; background: #FFD500; border-radius: 50%; transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1); box-shadow: 0 0 10px rgba(255,213,0,0.5); }
    .theme-toggle.light-mode::before { transform: translateX(34px); background: #3b82f6; box-shadow: 0 0 10px rgba(59,130,246,0.5); }
    .theme-toggle svg { width: 14px; height: 14px; position: absolute; z-index: 2; pointer-events: none; }
    .theme-toggle .icon-moon { left: 7px; color: black; }
    .theme-toggle .icon-sun { right: 7px; color: #9ca3af; }
    .theme-toggle.light-mode .icon-moon { color: #9ca3af; }
    .theme-toggle.light-mode .icon-sun { color: white; }

    /* Map Container */
    #map { flex-grow: 1; width: 100%; z-index: 1; background: #050505; }

    /* User Live Location Marker */
    .user-marker {
        width: 16px;
        height: 16px;
        background: #3b82f6;
        border: 3px solid white;
        border-radius: 50%;
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.8);
        position: relative;
    }
    .user-marker::after {
        content: '';
        position: absolute;
        top: -6px;
        left: -6px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid #3b82f6;
        animation: pulseBlue 2s infinite ease-out;
    }

    @keyframes pulseBlue {
        0% { transform: scale(0.5); opacity: 1; }
        100% { transform: scale(2.5); opacity: 0; }
    }

    /* Authority Marker */
    .auth-marker {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFD500;
        color: black;
        border: 2px solid white;
        border-radius: 8px;
        font-weight: 900;
        font-size: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    }
    
    /* Popup styling */
    .leaflet-popup-content-wrapper {
        background: #0a0a0a;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
    }
    .leaflet-popup-tip {
        background: #0a0a0a;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    .popup-content h4 { margin: 0 0 4px; font-size: 14px; color: #FFD500; }
    .popup-content p { margin: 0 0 4px; font-size: 11px; color: #d1d5db; }
</style>
@endsection

@section('content')
<div class="network-layout">
    
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="main-area">
        <header class="topbar">
            <h2>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;color:#FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                National Road Network
            </h2>
            <div class="topbar-actions">
                <div class="legend-box">
                    <div class="legend-item"><span style="width: 12px; height: 12px; background: #3b82f6; border-radius: 50%; display: inline-block;"></span> Your Live Location</div>
                    <div class="legend-item"><span style="width: 14px; height: 14px; background: #FFD500; border-radius: 4px; display: inline-block;"></span> Dependent Authorities</div>
                </div>
                
                <div style="width: 1px; height: 24px; background: rgba(255,255,255,0.1);"></div>
                
                <div class="theme-toggle" id="mapThemeToggle" title="Toggle Map Theme">
                    <svg class="icon-moon" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    <svg class="icon-sun" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4.22 2.366a1 1 0 011.415 0l.707.707a1 1 0 11-1.414 1.415l-.707-.707a1 1 0 010-1.415zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-2.366 4.22a1 1 0 010 1.415l-.707.707a1 1 0 11-1.414-1.415l.707-.707a1 1 0 011.415 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm-4.22-2.366a1 1 0 01-1.415 0l-.707-.707a1 1 0 111.414-1.415l.707.707a1 1 0 010 1.415zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zm2.366-4.22a1 1 0 010-1.415l.707-.707a1 1 0 111.414 1.415l-.707.707a1 1 0 01-1.415 0zM10 5a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
        </header>

        <div id="map"></div>
    </main>

</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Default center to India (Nagpur - center point)
    var map = L.map('map').setView([21.1458, 79.0882], 5);

    var darkLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 20
    });
    
    var lightLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 20
    });

    // Start with dark layer
    darkLayer.addTo(map);

    // Theme Toggle Logic
    var isLightMode = false;
    document.getElementById('mapThemeToggle').addEventListener('click', function() {
        isLightMode = !isLightMode;
        if(isLightMode) {
            this.classList.add('light-mode');
            map.removeLayer(darkLayer);
            lightLayer.addTo(map);
        } else {
            this.classList.remove('light-mode');
            map.removeLayer(lightLayer);
            darkLayer.addTo(map);
        }
    });

    // 1. Plot India's Road Dependent Authorities
    var authorities = [
        { name: 'NHAI Headquarters', type: 'National Highway Authority', location: 'New Delhi', lat: 28.5833, lng: 77.0360, contact: '011-25074100' },
        { name: 'MoRTH', type: 'Ministry of Road Transport', location: 'New Delhi', lat: 28.6139, lng: 77.2090, contact: '011-23719023' },
        { name: 'BBMP Head Office', type: 'Municipal Corporation', location: 'Bengaluru', lat: 12.9652, lng: 77.5857, contact: '080-22210033' },
        { name: 'BMC Headquarters', type: 'Municipal Corporation', location: 'Mumbai', lat: 18.9388, lng: 72.8354, contact: '022-22620251' },
        { name: 'PWD Secretariat', type: 'Public Works Dept', location: 'Chennai', lat: 13.0631, lng: 80.2828, contact: '044-25672200' },
        { name: 'KMC Head Office', type: 'Municipal Corporation', location: 'Kolkata', lat: 22.5601, lng: 88.3512, contact: '033-22861000' },
        { name: 'GHMC Head Office', type: 'Municipal Corporation', location: 'Hyderabad', lat: 17.4239, lng: 78.4738, contact: '040-23225397' },
        { name: 'PWD Rajasthan', type: 'Public Works Dept', location: 'Jaipur', lat: 26.9124, lng: 75.7873, contact: '0141-2227306' }
    ];

    authorities.forEach(function(auth) {
        var icon = L.divIcon({
            html: `<div class="auth-marker" style="width: 24px; height: 24px;">GOV</div>`,
            className: '',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        var popupContent = `
            <div class="popup-content">
                <h4>${auth.name}</h4>
                <p><b>Type:</b> ${auth.type}</p>
                <p><b>City:</b> ${auth.location}</p>
                <p><b>Emergency Contact:</b> ${auth.contact}</p>
            </div>
        `;

        L.marker([auth.lat, auth.lng], {icon: icon})
            .addTo(map)
            .bindPopup(popupContent);
    });

    // 2. Plot User's Live Location
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var userLat = position.coords.latitude;
            var userLng = position.coords.longitude;
            
            // Fly to user location
            map.flyTo([userLat, userLng], 10, { duration: 2 });

            var userIcon = L.divIcon({
                html: `<div class="user-marker"></div>`,
                className: '',
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });

            var userPopup = `
                <div class="popup-content" style="text-align: center;">
                    <h4 style="color: #3b82f6;">Your Live Location</h4>
                    <p>Broadcasting to network</p>
                </div>
            `;

            L.marker([userLat, userLng], {icon: userIcon, zIndexOffset: 1000})
                .addTo(map)
                .bindPopup(userPopup)
                .openPopup();
                
        }, function(error) {
            console.error("Error getting user location:", error);
            console.warn("Could not access your live location.");
        });
    } else {
        console.warn("Geolocation is not supported by this browser.");
    }
});
</script>
@endsection
