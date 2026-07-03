<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.add('light-theme');
            }
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RoadHealth AI - @yield('title', 'AI-Powered Pavement Intelligence')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #050505;
            --text-color: #ffffff;
            --navbar-bg: #050505;
            --border-color: rgba(255, 255, 255, 0.05);
            --card-bg: rgba(10, 10, 10, 0.8);
            --text-muted: #9ca3af;
            --text-dark-gray: #6b7280;
            --btn-outline-border: #4b5563;
            --btn-outline-color: #ffffff;
            --btn-outline-hover-bg: rgba(255, 255, 255, 0.05);
            --btn-icon-color: #9ca3af;
            --btn-icon-border: #374151;
            --btn-icon-hover-color: #ffffff;
            
            --input-bg: #080808;
            --input-border: rgba(255,255,255,0.08);
            --input-placeholder: #4b5563;
            --checkbox-custom-bg: rgba(255,213,0,0.02);
            --testi-card-bg: rgba(10,10,10,0.6);
            --btn-social-bg: #0a0a0a;
            --btn-social-border: rgba(255,255,255,0.05);
            --btn-social-color: #d1d5db;
        }
        
        html.light-theme,
        body.light-theme {
            --bg-color: #f5f6f8;
            --text-color: #111827;
            --navbar-bg: #ffffff;
            --border-color: rgba(0, 0, 0, 0.08);
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-muted: #4b5563;
            --text-dark-gray: #888888;
            --btn-outline-border: #cbd5e1;
            --btn-outline-color: #111827;
            --btn-outline-hover-bg: rgba(0, 0, 0, 0.04);
            --btn-icon-color: #4b5563;
            --btn-icon-border: #cbd5e1;
            --btn-icon-hover-color: #111827;
            
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-placeholder: #94a3b8;
            --checkbox-custom-bg: rgba(255,213,0,0.05);
            --testi-card-bg: rgba(255, 255, 255, 0.9);
            --btn-social-bg: #ffffff;
            --btn-social-border: #cbd5e1;
            --btn-social-color: #334155;
        }

        /* Global Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-color); color: var(--text-color); -webkit-font-smoothing: antialiased; overflow-x: hidden; transition: background-color 0.3s, color 0.3s; }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        svg { max-width: 100%; max-height: 100%; }
        
        /* Typography */
        .text-yellow { color: #FFD500; }
        .text-gray { color: var(--text-muted); }
        .text-dark-gray { color: var(--text-dark-gray); }
        .text-center { text-align: center; }
        
        /* Layout Utilities */
        .container { max-width: 1600px; margin: 0 auto; padding: 0 48px; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .justify-center { justify-content: center; }
        .flex-col { flex-direction: column; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .gap-4 { gap: 16px; }
        .gap-6 { gap: 24px; }
        .gap-8 { gap: 32px; }
        .w-full { width: 100%; }
        .h-full { height: 100%; }
        .relative { position: relative; }
        .absolute { position: absolute; }
        
        /* Buttons */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; border-radius: 6px; cursor: pointer; transition: all 0.2s; border: none; }
        .btn-primary { background: #FFD500; color: black; padding: 10px 20px; font-size: 14px; }
        .btn-primary:hover { background: #facc15; }
        .btn-outline { background: transparent; border: 1px solid var(--btn-outline-border); color: var(--btn-outline-color); padding: 10px 20px; font-size: 14px; }
        .btn-outline:hover { background: var(--btn-outline-hover-bg); }
        .btn-icon { padding: 10px; color: var(--btn-icon-color); border: 1px solid var(--btn-icon-border); border-radius: 50%; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .btn-icon:hover { color: var(--btn-icon-hover-color); border-color: var(--btn-icon-hover-color); }
        .btn-icon svg { width: 16px; height: 16px; }
        
        /* Navbar */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 48px; border-bottom: 1px solid var(--border-color); background: var(--navbar-bg); position: relative; z-index: 50; transition: background-color 0.3s, border-color 0.3s; }
        .nav-logo { display: flex; align-items: center; gap: 12px; font-size: 24px; font-weight: 700; color: var(--text-color); letter-spacing: -0.02em; }
        .nav-logo svg { width: 32px; height: 32px; color: #FFD500; }
        .nav-logo .tagline { font-size: 10px; color: var(--text-dark-gray); font-weight: 500; display: block; margin-top: -4px; letter-spacing: 0; }
        .nav-links { display: flex; gap: 32px; font-size: 14px; font-weight: 500; color: var(--text-muted); }
        .nav-links a { position: relative; padding: 8px 0; transition: color 0.2s; }
        .nav-links a:hover { color: var(--text-color); }
        .nav-links a.active { color: var(--text-color); }
        .nav-links a.active::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: #FFD500; }
        .nav-actions { display: flex; align-items: center; gap: 16px; }
        
        /* Footer */
        .footer { background: var(--navbar-bg); border-top: 1px solid var(--border-color); padding: 64px 48px 24px; margin-top: 64px; transition: background-color 0.3s, border-color 0.3s; }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1.5fr; gap: 40px; margin-bottom: 64px; max-width: 1600px; margin-left: auto; margin-right: auto; }
        .footer-col h3 { color: var(--text-color); font-size: 14px; font-weight: 700; margin-bottom: 24px; }
        .footer-col ul { display: flex; flex-direction: column; gap: 12px; }
        .footer-col a { color: var(--text-muted); font-size: 13px; transition: color 0.2s; }
        .footer-col a:hover { color: #FFD500; }
        .footer-logo { font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 16px; color: var(--text-color); }
        .footer-logo svg { width: 24px; height: 24px; color: #FFD500; }
        .footer-desc { color: var(--text-dark-gray); font-size: 13px; line-height: 1.6; max-width: 250px; }
        .footer-contact { display: flex; align-items: center; gap: 8px; color: var(--text-muted); font-size: 13px; margin-bottom: 12px; }
        .footer-contact svg { width: 16px; height: 16px; color: var(--text-dark-gray); }
        .footer-social { display: flex; gap: 16px; margin-top: 24px; }
        .footer-social a { color: var(--text-dark-gray); transition: color 0.2s; }
        .footer-social a:hover { color: #FFD500; }
        .footer-social svg { width: 20px; height: 20px; }
        .footer-bottom { border-top: 1px solid var(--border-color); padding-top: 24px; display: flex; justify-content: space-between; align-items: center; color: var(--text-dark-gray); font-size: 12px; max-width: 1600px; margin: 0 auto; }
        .footer-bottom-links { display: flex; gap: 24px; }
        
        /* User Profile in Navbar (for logged in states) */
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border-color); }
        .user-info { display: flex; flex-direction: column; }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text-color); }
        .user-role { font-size: 11px; color: var(--text-dark-gray); }

        /* Notification Dropdown */
        .notif-bell-wrap { position: relative; cursor: pointer; }
        .notif-bell { background: transparent; color: var(--text-muted); cursor: pointer; transition: color 0.2s; }
        .notif-bell:hover { color: #FFD500; }
        .notif-bell svg { width: 20px; height: 20px; }
        .notif-badge { position: absolute; top: 0; right: 0; background: #ef4444; color: white; font-size: 9px; font-weight: 800; padding: 2px 5px; border-radius: 10px; border: 2px solid var(--bg-color); }
        
        .notif-dropdown { position: absolute; top: calc(100% + 12px); right: -10px; width: 340px; background: rgba(10, 10, 10, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(255, 213, 0, 0.2); border-radius: 12px; box-shadow: 0 16px 40px rgba(0, 0, 0, 0.6); opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); z-index: 100; overflow: hidden; }
        .notif-dropdown.active { opacity: 1; visibility: visible; transform: translateY(0); }
        
        .notif-header { padding: 16px 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.05); display: flex; justify-content: space-between; align-items: center; }
        .notif-title { font-size: 14px; font-weight: 700; color: white; display: flex; align-items: center; gap: 8px; }
        .notif-mark-read { font-size: 12px; color: #FFD500; cursor: pointer; font-weight: 600; }
        .notif-mark-read:hover { text-decoration: underline; }
        
        .notif-list { max-height: 320px; overflow-y: auto; }
        .notif-list::-webkit-scrollbar { width: 4px; }
        .notif-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        
        .notif-item { padding: 16px 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.03); display: flex; gap: 12px; transition: background 0.2s; cursor: pointer; position: relative; }
        .notif-item:hover { background: rgba(255, 255, 255, 0.03); }
        .notif-item.unread::before { content: ''; position: absolute; left: 8px; top: 24px; width: 6px; height: 6px; border-radius: 50%; background: #FFD500; }
        
        .notif-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .notif-icon.info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .notif-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .notif-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .notif-icon.critical { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .notif-icon svg { width: 18px; height: 18px; }
        
        .notif-content { display: flex; flex-direction: column; gap: 4px; }
        .notif-text { font-size: 13px; color: #e5e7eb; line-height: 1.4; }
        .notif-time { font-size: 11px; color: #6b7280; font-weight: 500; }
        
        .notif-footer { padding: 12px; border-top: 1px solid rgba(255, 255, 255, 0.05); text-align: center; }
        .notif-view-all { background: transparent; border: none; color: #9ca3af; font-size: 13px; font-weight: 600; cursor: pointer; transition: color 0.2s; width: 100%; padding: 8px; border-radius: 6px; }
        .notif-view-all:hover { background: rgba(255, 255, 255, 0.03); color: white; }

        /* Unified Light Mode Stylesheet Overrides */
        html.light-theme,
        html.light-theme body,
        body.light-theme {
            background-color: var(--bg-color) !important;
            background: var(--bg-color) !important;
            color: var(--text-color) !important;
        }

        /* Hero Area Overrides on Welcome Page */
        html.light-theme main > div[style*="background"] {
            background: var(--bg-color) !important;
            background-color: var(--bg-color) !important;
        }
        
        html.light-theme .hero-bg img {
            opacity: 0.25 !important;
        }
        
        html.light-theme .ai-label {
            background: rgba(255, 255, 255, 0.95) !important;
            color: #b28000 !important;
            border-color: rgba(255, 213, 0, 0.6) !important;
        }
        
        html.light-theme .detect-label {
            background-color: rgba(255, 255, 255, 0.95) !important;
            color: #b28000 !important;
            border-color: #FFD500 !important;
        }
        
        html.light-theme .detect-box {
            background: rgba(255, 213, 0, 0.08) !important;
        }
        
        html.light-theme .trust-badge {
            color: #374151 !important;
        }
        
        html.light-theme #upload-hint {
            color: #4b5563 !important;
        }

        /* Sidebar & Sidebar components */
        html.light-theme .sidebar {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-right: 1px solid rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .sidebar-nav .nav-item {
            color: #4b5563 !important;
        }
        
        html.light-theme .sidebar-nav .nav-item:hover {
            background: rgba(0, 0, 0, 0.04) !important;
            color: #111827 !important;
        }
        
        html.light-theme .sidebar-nav .nav-item.active {
            background: rgba(255, 213, 0, 0.2) !important;
            color: #b28000 !important;
            font-weight: 700 !important;
            border-left-color: #FFD500 !important;
        }
        
        html.light-theme .sidebar-premium {
            background: linear-gradient(135deg, rgba(255, 213, 0, 0.15) 0%, rgba(255, 213, 0, 0.03) 100%) !important;
            border: 1px solid rgba(255, 213, 0, 0.4) !important;
        }
        
        html.light-theme .sidebar-premium h4 {
            color: #111827 !important;
        }
        
        html.light-theme .sidebar-premium p {
            color: #4b5563 !important;
        }
        
        html.light-theme .sidebar-lang {
            border-top: 1px solid rgba(0, 0, 0, 0.08) !important;
            color: #4b5563 !important;
        }
        
        html.light-theme .sidebar-lang:hover {
            color: #111827 !important;
        }
        
        html.light-theme .nav-divider {
            background: rgba(0, 0, 0, 0.08) !important;
        }

        /* Topbar and Header Layouts */
        html.light-theme .topbar {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .topbar-title,
        html.light-theme .topbar h2,
        html.light-theme .topbar h1 {
            color: #111827 !important;
        }
        
        html.light-theme .topbar-sub {
            color: #6b7280 !important;
        }
        
        html.light-theme .search-bar {
            background: rgba(0, 0, 0, 0.03) !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .search-bar svg {
            color: #4b5563 !important;
        }
        
        html.light-theme .search-bar input {
            color: #111827 !important;
        }
        
        html.light-theme .search-bar input::placeholder {
            color: #94a3b8 !important;
        }
        
        /* Main Content Container wrappers */
        html.light-theme .main-area,
        html.light-theme .dashboard-layout,
        html.light-theme .result-layout,
        html.light-theme .maint-layout,
        html.light-theme .dashboard-content,
        html.light-theme .content-scrollable,
        html.light-theme .main-scrollable {
            background: var(--bg-color) !important;
            background-color: var(--bg-color) !important;
        }

        /* Headings & Text */
        html.light-theme h1,
        html.light-theme h2,
        html.light-theme h3,
        html.light-theme h4,
        html.light-theme h5,
        html.light-theme h6,
        html.light-theme .page-header h1,
        html.light-theme .header-left h1,
        html.light-theme .topbar-title {
            color: #111827 !important;
        }
        
        html.light-theme .text-muted,
        html.light-theme .text-gray,
        html.light-theme .stat-title,
        html.light-theme .kpi-lbl,
        html.light-theme .control-lbl,
        html.light-theme .meta-label,
        html.light-theme .in-lbl,
        html.light-theme .chip-label,
        html.light-theme .pci-label,
        html.light-theme .field-label,
        html.light-theme .header-left p,
        html.light-theme .loc-lbl {
            color: #4b5563 !important;
        }

        /* Cards & Panels */
        html.light-theme .card,
        html.light-theme .panel,
        html.light-theme .cc-panel,
        html.light-theme .kpi-card,
        html.light-theme .stat-card,
        html.light-theme .auth-item,
        html.light-theme .staff-item,
        html.light-theme .insight-box,
        html.light-theme .recom-box,
        html.light-theme .detection-item,
        html.light-theme .gov-logos,
        html.light-theme .modal-box,
        html.light-theme .card-wrap,
        html.light-theme .auth-badge,
        html.light-theme .bottom-flow-wrap,
        html.light-theme .info-card,
        html.light-theme .upload-card,
        html.light-theme .demo-badge,
        html.light-theme .v-hist-item,
        html.light-theme .v-chart-card {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03) !important;
        }
        
        html.light-theme .card-title,
        html.light-theme .panel-title,
        html.light-theme .cc-title,
        html.light-theme .modal-title,
        html.light-theme .auth-name,
        html.light-theme .staff-name,
        html.light-theme .meta-value,
        html.light-theme .pci-number,
        html.light-theme .chip-value,
        html.light-theme .in-val:not(.red),
        html.light-theme .stat-value,
        html.light-theme .kpi-val:not(.red),
        html.light-theme .det-label,
        html.light-theme .loc-title,
        html.light-theme .flow-step-lbl,
        html.light-theme .db-title-col h2,
        html.light-theme .metric-val:not(.red),
        html.light-theme .v-hist-title,
        html.light-theme .v-gauge-text span[style*="color: var(--text-color)"] {
            color: #111827 !important;
        }
        
        html.light-theme .loc-val,
        html.light-theme .summary-item .val,
        html.light-theme .flow-step-sub,
        html.light-theme .v-hist-sub {
            color: #374151 !important;
        }

        /* KPI / Widget Indicators styling overrides */
        html.light-theme .kpi-icon,
        html.light-theme .stat-icon,
        html.light-theme .auth-icon:not(.orange):not(.blue) {
            background: rgba(0, 0, 0, 0.04) !important;
            color: #4b5563 !important;
        }
        
        html.light-theme .kpi-icon.red {
            background: rgba(239, 68, 68, 0.1) !important;
            color: #ef4444 !important;
        }
        
        html.light-theme .kpi-card.critical {
            background: rgba(239, 68, 68, 0.03) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
        }
        
        html.light-theme .insight-box.warning {
            background: rgba(239, 68, 68, 0.03) !important;
            border-color: rgba(239, 68, 68, 0.2) !important;
        }

        /* Forms, inputs, select selectors */
        html.light-theme select,
        html.light-theme input[type="text"],
        html.light-theme input[type="password"],
        html.light-theme input[type="email"],
        html.light-theme textarea,
        html.light-theme .select-staff,
        html.light-theme .field-input,
        html.light-theme .field-textarea,
        html.light-theme .field-select,
        html.light-theme .form-input,
        html.light-theme .input-field {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #111827 !important;
        }
        
        html.light-theme select:focus,
        html.light-theme input[type="text"]:focus,
        html.light-theme textarea:focus,
        html.light-theme .select-staff:focus,
        html.light-theme .field-input:focus,
        html.light-theme .field-textarea:focus,
        html.light-theme .field-select:focus,
        html.light-theme .form-input:focus,
        html.light-theme .input-field:focus {
            border-color: #FFD500 !important;
            box-shadow: 0 0 0 2px rgba(255, 213, 0, 0.2) !important;
        }

        /* Tables layout override styling */
        html.light-theme table th {
            border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
            color: #4b5563 !important;
            background: rgba(0, 0, 0, 0.02) !important;
        }
        
        html.light-theme table td {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
            color: #374151 !important;
        }
        
        html.light-theme table tr:hover td {
            background: rgba(0, 0, 0, 0.02) !important;
        }
        
        html.light-theme td .td-scan div div {
            color: #111827 !important;
        }

        /* Lists & Items (Request queues, timelines, staff list) */
        html.light-theme .req-item {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
            background: #ffffff !important;
        }
        
        html.light-theme .req-item:hover {
            background: rgba(0, 0, 0, 0.02) !important;
        }
        
        html.light-theme .req-item.active {
            background: rgba(255, 213, 0, 0.08) !important;
        }
        
        html.light-theme .req-id {
            color: #111827 !important;
        }
        
        html.light-theme .req-loc {
            color: #4b5563 !important;
        }
        
        html.light-theme .cc-header {
            background: rgba(0, 0, 0, 0.02) !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .status-timeline-line {
            background: rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .status-dot {
            border-color: #ffffff !important;
            background: rgba(0, 0, 0, 0.1) !important;
        }
        
        html.light-theme .status-node.active .status-dot {
            background: #FFD500 !important;
        }
        
        html.light-theme .status-node.active .status-desc {
            color: #111827 !important;
        }
        
        html.light-theme .status-desc {
            color: #6b7280 !important;
        }
        
        html.light-theme .status-time {
            color: #9ca3af !important;
        }
        
        html.light-theme .status-node.pending-node .status-desc {
            color: #9ca3af !important;
        }
        
        html.light-theme .summary-item {
            border-bottom-color: rgba(0, 0, 0, 0.05) !important;
        }

        /* Buttons & Actions in light theme */
        html.light-theme .btn-reports,
        html.light-theme .btn-outline,
        html.light-theme .btn-back,
        html.light-theme .zoom-tool {
            color: #374151 !important;
            border: 1px solid #cbd5e1 !important;
            background: #ffffff !important;
        }
        
        html.light-theme .btn-reports:hover,
        html.light-theme .btn-outline:hover,
        html.light-theme .btn-back:hover,
        html.light-theme .zoom-tool:hover {
            background: rgba(0, 0, 0, 0.04) !important;
            color: #111827 !important;
            border-color: #94a3b8 !important;
        }
        
        html.light-theme .action-btn,
        html.light-theme .panel-action,
        html.light-theme .f-link {
            color: #b28000 !important;
        }
        
        html.light-theme .action-btn:hover,
        html.light-theme .panel-action:hover,
        html.light-theme .f-link:hover {
            color: #ff9900 !important;
        }
        
        html.light-theme .nav-badge-ai {
            background: #b28000 !important;
            color: white !important;
        }

        /* Maps overlays and items styling */
        html.light-theme .map-container {
            background: #e2e8f0 !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .map-svg,
        html.light-theme .map-img {
            opacity: 0.85 !important;
        }
        
        html.light-theme .map-overlay {
            background: linear-gradient(0deg, #ffffff 0%, transparent 40%) !important;
        }
        
        html.light-theme .map-overlay-lbl {
            background: rgba(255, 255, 255, 0.95) !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .map-overlay-lbl div {
            color: #111827 !important;
        }

        /* Upload and drop zones */
        html.light-theme .scan-upload-zone,
        html.light-theme .drop-zone,
        html.light-theme .upload-drag-box,
        html.light-theme .v-dropzone,
        html.light-theme #panel-camera {
            border: 2px dashed rgba(0, 0, 0, 0.15) !important;
            background: rgba(0, 0, 0, 0.01) !important;
        }
        
        html.light-theme .scan-upload-zone:hover,
        html.light-theme .drop-zone:hover,
        html.light-theme .drop-zone.drag-over,
        html.light-theme .upload-drag-box:hover,
        html.light-theme .v-dropzone:hover,
        html.light-theme #panel-camera:hover {
            border-color: #FFD500 !important;
            background: rgba(255, 213, 0, 0.04) !important;
        }
        
        html.light-theme .scan-upload-zone div,
        html.light-theme .drop-zone div:not(.format-badge),
        html.light-theme .upload-drag-box span,
        html.light-theme .v-dropzone div {
            color: #4b5563 !important;
        }
        
        html.light-theme .scan-upload-zone #upload-prompt div:first-child,
        html.light-theme .drop-zone div[style*="font-weight: 800"] {
            color: #111827 !important;
        }
        
        html.light-theme .format-badge {
            background: rgba(0, 0, 0, 0.05) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            color: #4b5563 !important;
        }
        
        html.light-theme .step-num {
            background: rgba(255, 213, 0, 0.15) !important;
            border-color: rgba(255, 213, 0, 0.4) !important;
            color: #b28000 !important;
        }
        
        html.light-theme .demo-badge {
            background: rgba(255, 213, 0, 0.05) !important;
            border-color: rgba(255, 213, 0, 0.2) !important;
        }
        
        html.light-theme .v-progress {
            background: rgba(0, 0, 0, 0.08) !important;
        }

        /* AI Assistant specific elements overrides */
        html.light-theme .chat-box {
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            background: rgba(0, 0, 0, 0.01) !important;
        }
        
        html.light-theme .chat-bubble {
            background: #e5e7eb !important;
            color: #111827 !important;
        }
        
        html.light-theme .chat-bubble.ai {
            background: rgba(255, 213, 0, 0.1) !important;
            border: 1px solid rgba(255, 213, 0, 0.2) !important;
            color: #111827 !important;
        }
        
        html.light-theme .chat-input-wrap input {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #111827 !important;
        }

        /* Interactive metrics/buttons on Citizen panel */
        html.light-theme .metric-btn-box {
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        }
        
        html.light-theme .metric-btn-box:hover {
            background: rgba(0, 0, 0, 0.01) !important;
            border-color: rgba(0, 0, 0, 0.15) !important;
        }
        
        html.light-theme .metric-btn-box.active {
            background: rgba(255, 213, 0, 0.05) !important;
            border-color: rgba(255, 213, 0, 0.4) !important;
        }
        
        html.light-theme .metric-val {
            color: #111827 !important;
        }

        /* Modals and overlays overrides */
        html.light-theme .modal-overlay {
            background: rgba(0, 0, 0, 0.5) !important;
        }
        
        html.light-theme .modal-box {
            background: #ffffff !important;
            border-color: rgba(255, 213, 0, 0.5) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        }
        
        html.light-theme .modal-box p {
            color: #4b5563 !important;
        }
        
        html.light-theme .modal-box span {
            color: #111827 !important;
        }
        
        html.light-theme .modal-box span[id^="rep-"] {
            color: #111827 !important;
        }
        
        html.light-theme .modal-box div[id^="rep-"] {
            color: #111827 !important;
        }
        
        html.light-theme .modal-close {
            color: #6b7280 !important;
        }
        
        html.light-theme .modal-close:hover {
            color: #111827 !important;
        }
        
        html.light-theme .scan-active-line {
            box-shadow: 0 0 10px #FFD500 !important;
        }

        /* Flow steps nodes styling */
        html.light-theme .flow-step-icon {
            background: #ffffff !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .flow-steps::after {
            background: rgba(0, 0, 0, 0.08) !important;
        }

        /* Notifications & Notification list panel page overrides */
        html.light-theme .notif-dropdown {
            background: rgba(255, 255, 255, 0.98) !important;
            border-color: rgba(255, 213, 0, 0.4) !important;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.1) !important;
        }
        
        html.light-theme .notif-header {
            border-bottom-color: rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .notif-title {
            color: #111827 !important;
        }
        
        html.light-theme .notif-mark-read {
            color: #b28000 !important;
        }
        
        html.light-theme .notif-item {
            border-bottom-color: rgba(0, 0, 0, 0.04) !important;
        }
        
        html.light-theme .notif-item:hover {
            background: rgba(0, 0, 0, 0.02) !important;
        }
        
        html.light-theme .notif-text {
            color: #374151 !important;
        }
        
        html.light-theme .notif-time {
            color: #6b7280 !important;
        }
        
        html.light-theme .notif-footer {
            border-top-color: rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .notif-view-all {
            color: #4b5563 !important;
        }
        
        html.light-theme .notif-view-all:hover {
            background: rgba(0, 0, 0, 0.02) !important;
            color: #111827 !important;
        }

        /* Onboarding register pipeline specific rules */
        html.light-theme .pipeline-container {
            background: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
            box-shadow: inset 0 0 40px rgba(0,0,0,0.05) !important;
        }
        
        html.light-theme .pipeline-step {
            background: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .pipeline-step.active {
            border-color: #FFD500 !important;
            background: rgba(255, 213, 0, 0.03) !important;
        }
        
        html.light-theme .pipeline-step.completed {
            border-color: rgba(34,197,94,0.4) !important;
            background: rgba(34,197,94,0.03) !important;
        }
        
        html.light-theme .pipeline-step.pending .step-icon {
            background: rgba(0, 0, 0, 0.05) !important;
            color: #6b7280 !important;
            border-color: rgba(0, 0, 0, 0.1) !important;
        }
        
        html.light-theme .step-title {
            color: #111827 !important;
        }
        
        html.light-theme .step-desc {
            color: #4b5563 !important;
        }
        
        html.light-theme .role-tab {
            color: #4b5563 !important;
        }
        
        html.light-theme .role-tab.active {
            background: #FFD500 !important;
            color: black !important;
        }
        
        html.light-theme .role-tab:hover:not(.active) {
            color: #111827 !important;
            background: rgba(0, 0, 0, 0.02) !important;
        }
        
        html.light-theme .pipeline-connector {
            background: rgba(0, 0, 0, 0.08) !important;
        }

        /* Onboarding login overlay specific rules */
        html.light-theme .defect-overlay-container {
            background: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
            box-shadow: inset 0 0 40px rgba(0,0,0,0.05) !important;
        }
        
        html.light-theme .defect-road-img {
            opacity: 0.85 !important;
        }
        
        html.light-theme .defect-callout {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
        }
        
        html.light-theme .callout-sub {
            color: #4b5563 !important;
        }
        
        html.light-theme .callout-patch,
        html.light-theme .callout-crack {
            border-color: rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .callout-line:not(.line-pothole) {
            background: rgba(0, 0, 0, 0.15) !important;
        }
        
        html.light-theme .testi-card {
            background: #ffffff !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .testi-quote,
        html.light-theme .testi-name,
        html.light-theme .testi-role {
            color: #111827 !important;
        }
        
        html.light-theme .testi-avatar {
            background: #f3f4f6 !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .role-selector {
            background: rgba(0, 0, 0, 0.02) !important;
            border-color: rgba(0, 0, 0, 0.08) !important;
        }
        
        html.light-theme .role-btn {
            color: #4b5563 !important;
        }
        
        html.light-theme .role-btn.active {
            background: rgba(255, 213, 0, 0.2) !important;
            color: #b28000 !important;
            border-color: rgba(255, 213, 0, 0.4) !important;
        }
        
        html.light-theme .role-btn:hover:not(.active) {
            color: #111827 !important;
            background: rgba(0, 0, 0, 0.03) !important;
        }
    </style>
    @yield('styles')
</head>
<body>
    @if(!isset($hideNavbar))
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-logo">
            <img src="{{ asset('images/logo.png') }}" alt="RoadHealth AI Logo" style="height: 36px; width: auto; object-fit: contain;">
            <div>
                RoadHealth AI
                <span class="tagline">AI-Powered Pavement Intelligence</span>
            </div>
        </div>
        
        <div class="nav-links">
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
            <a href="/features" class="{{ request()->is('features') ? 'active' : '' }}">Features</a>
            <a href="/how-it-works" class="{{ request()->is('how-it-works') ? 'active' : '' }}">How It Works</a>
            <a href="/dashboard" class="{{ (request()->is('dashboard*') || request()->is('upload*') || request()->is('reports*')) ? 'active' : '' }}">Dashboard</a>
            <a href="/pricing" class="{{ request()->is('pricing') ? 'active' : '' }}">Pricing</a>
            <a href="/about" class="{{ request()->is('about') ? 'active' : '' }}">About Us</a>
            <a href="/contact" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a>
        </div>
        
        <div class="nav-actions">
            <button class="btn-icon" id="theme-toggle" title="Toggle Light/Dark Theme">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>
            
            @auth
                <!-- Logged in state for specific pages based on reference images -->
                @include('partials.notification-bell')
                <div class="user-profile" style="margin-left: 8px; border-left: 1px solid rgba(255,255,255,0.1); padding-left: 16px;">
                    <img src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=random' }}" alt="User" class="user-avatar">
                    <div class="user-info">
                        <span class="user-name">{{ auth()->user()->name }}</span>
                        <span class="user-role">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>
                <button class="btn btn-primary" onclick="window.location.href='/logout';" style="margin-left: 16px;">Log Out</button>
            @else
                <!-- Logged out state -->
                <button class="btn btn-outline" onclick="window.location.href='/login';">Login</button>
                <button class="btn btn-primary" onclick="window.location.href='/register';">
                    Get Started
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            @endauth
        </div>
    </nav>
    @endif

    <main>
        @yield('content')
    </main>

    @if(!isset($hideFooter))
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col">
                <div class="footer-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="RoadHealth AI Logo" style="height: 28px; width: auto; object-fit: contain;">
                    RoadHealth AI
                </div>
                <p class="footer-desc">
                    RoadHealth AI helps organizations make smarter decisions and build better roads with the power of artificial intelligence.
                </p>
            </div>
            <div class="footer-col">
                <h3>Product</h3>
                <ul>
                    <li><a href="/features">Features</a></li>
                    <li><a href="/how-it-works">How It Works</a></li>
                    <li><a href="/pricing">Pricing</a></li>
                    <li><a href="/dashboard">Dashboard</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Company</h3>
                <ul>
                    <li><a href="/about">About Us</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">News & Updates</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Resources</h3>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">API</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Get in Touch</h3>
                <div class="footer-contact">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    contact@roadhealthai.com
                </div>
                <div class="footer-contact">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    +91 98765 43210
                </div>
                <div class="footer-social">
                    <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                    <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div>&copy; 2025 RoadHealth AI. All rights reserved.</div>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </footer>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('theme-toggle');
            if (themeToggle) {
                // Update SVG icon based on current theme
                const updateIcon = () => {
                    const isLight = document.documentElement.classList.contains('light-theme') || document.body.classList.contains('light-theme');
                    themeToggle.innerHTML = isLight 
                        ? `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>` // Sun icon
                        : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>`; // Moon icon
                };

                // Initialize state from documentElement class (applied by head script)
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme === 'light') {
                    document.body.classList.add('light-theme');
                    updateIcon();
                }

                themeToggle.addEventListener('click', function () {
                    document.documentElement.classList.toggle('light-theme');
                    document.body.classList.toggle('light-theme');
                    const currentTheme = document.body.classList.contains('light-theme') ? 'light' : 'dark';
                    localStorage.setItem('theme', currentTheme);
                    updateIcon();
                    
                    // Dispatch a global event so that child views can update their theme states if needed
                    window.dispatchEvent(new Event('themeChanged'));
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
