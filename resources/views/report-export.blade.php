@php $hideFooter = true; @endphp
@extends('layouts.app')
@section('title', 'Full Report & Export')

@section('styles')
<style>
body { background:#050505; overflow:hidden; font-family: 'Inter', sans-serif; }
.rep-layout { display:flex; height:calc(100vh - 73px); }

/* Sidebar */
.sidebar { width:220px; min-width:220px; background:#0a0a0a; border-right:1px solid rgba(255,255,255,0.06); display:flex; flex-direction:column; flex-shrink:0; }
.sidebar-nav { padding:12px 10px; flex-grow:1; display:flex; flex-direction:column; gap:2px; overflow-y:auto; }
.nav-item { display:flex; align-items:center; gap:10px; padding:10px 12px; color:#9ca3af; font-size:13px; font-weight:500; border-radius:8px; transition:all 0.2s; cursor:pointer; }
.nav-item:hover { background:rgba(255,255,255,0.05); color:white; }
.nav-item.active { background:rgba(255,213,0,0.12); color:#FFD500; font-weight:700; }
.nav-item svg { width:18px; height:18px; flex-shrink:0; }
.nav-badge { margin-left:auto; background:#FFD500; color:black; font-size:9px; font-weight:800; padding:2px 6px; border-radius:10px; }
.nav-divider { height:1px; background:rgba(255,255,255,0.05); margin:6px 10px; }

/* Main */
.main-area { flex-grow:1; display:flex; flex-direction:column; overflow:hidden; }
.topbar { height:64px; border-bottom:1px solid rgba(255,255,255,0.05); display:flex; align-items:center; justify-content:space-between; padding:0 32px; background:#0a0a0a; flex-shrink:0; }
.topbar-left { display:flex; align-items:center; gap:12px; }
.topbar h2 { color:white; font-size:18px; font-weight:700; }
.topbar-actions { display:flex; gap:10px; }
.btn-pdf { background:#FFD500; color:black; border:none; padding:10px 20px; border-radius:6px; font-size:13px; font-weight:800; cursor:pointer; display:flex; align-items:center; gap:8px; }
.btn-pdf svg { width:16px; height:16px; }
.btn-back { background:transparent; color:#9ca3af; border:1px solid rgba(255,255,255,0.1); padding:10px 16px; border-radius:6px; font-size:13px; font-weight:600; cursor:pointer; }
.btn-back:hover { color:white; }

.rep-content { flex-grow:1; overflow-y:auto; padding:32px; }

/* Report paper */
#report-paper { background:#0a0a0a; border:1px solid rgba(255,255,255,0.06); border-radius:16px; max-width:860px; margin:0 auto; padding:48px; }

/* Header */
.rp-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:36px; padding-bottom:24px; border-bottom:1px solid rgba(255,255,255,0.08); }
.rp-logo-wrap { display:flex; align-items:center; gap:12px; }
.rp-logo-wrap img { height:48px; }
.rp-logo-text h1 { color:white; font-size:20px; font-weight:900; }
.rp-logo-text p { color:#6b7280; font-size:12px; }
.rp-badge { background:rgba(255,213,0,0.1); border:1px solid rgba(255,213,0,0.3); border-radius:8px; padding:8px 16px; text-align:right; }
.rp-badge-label { color:#6b7280; font-size:10px; font-weight:700; letter-spacing:0.08em; }
.rp-badge-id { color:#FFD500; font-size:18px; font-weight:900; }
.rp-badge-date { color:#9ca3af; font-size:11px; margin-top:2px; }

/* Score section */
.rp-score-section { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-bottom:32px; }
.score-card { background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:12px; padding:20px; text-align:center; }
.sc-label { font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:#6b7280; margin-bottom:8px; }
.sc-value { font-size:36px; font-weight:900; }
.sc-sub { font-size:11px; color:#6b7280; margin-top:4px; }

/* Badge */
.cond-badge { display:inline-block; padding:6px 16px; border-radius:6px; font-size:14px; font-weight:800; }
.badge-poor      { background:rgba(239,68,68,0.15); color:#ef4444; border:1px solid rgba(239,68,68,0.3); }
.badge-fair      { background:rgba(245,158,11,0.15); color:#f59e0b; border:1px solid rgba(245,158,11,0.3); }
.badge-good      { background:rgba(16,185,129,0.15); color:#10b981; border:1px solid rgba(16,185,129,0.3); }
.badge-excellent { background:rgba(59,130,246,0.15); color:#3b82f6; border:1px solid rgba(59,130,246,0.3); }

/* Road image */
.rp-image { width:100%; max-height:260px; object-fit:cover; border-radius:10px; border:1px solid rgba(255,255,255,0.08); margin-bottom:32px; }

/* Detections */
.rp-section-title { font-size:13px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.rp-section-title::after { content:''; flex:1; height:1px; background:rgba(255,255,255,0.06); }
.det-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:32px; }
.det-card { background:rgba(255,255,255,0.02); border:1px solid rgba(255,255,255,0.06); border-radius:8px; padding:14px 16px; display:flex; align-items:center; gap:12px; }
.det-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.det-info { flex-grow:1; }
.det-name { color:white; font-size:13px; font-weight:700; text-transform:capitalize; }
.det-cnt { color:#6b7280; font-size:11px; }
.det-conf { font-size:12px; font-weight:700; }
.conf-track { width:80px; height:4px; background:rgba(255,255,255,0.08); border-radius:2px; overflow:hidden; margin-top:4px; }
.conf-fill { height:100%; border-radius:2px; }

/* Recommendation */
.recom-box { background:rgba(16,185,129,0.05); border:1px solid rgba(16,185,129,0.2); border-radius:10px; padding:16px 20px; display:flex; gap:12px; margin-bottom:32px; }
.recom-box svg { width:20px; height:20px; color:#10b981; flex-shrink:0; margin-top:2px; }
.recom-box p { color:#d1d5db; font-size:14px; line-height:1.6; }

/* Meta table */
.meta-table { width:100%; border-collapse:collapse; margin-bottom:32px; }
.meta-table td { padding:10px 16px; font-size:13px; border-bottom:1px solid rgba(255,255,255,0.05); }
.meta-table td:first-child { color:#6b7280; font-weight:700; width:180px; text-transform:uppercase; font-size:11px; letter-spacing:0.05em; }
.meta-table td:last-child { color:white; font-weight:600; }

/* Footer */
.rp-footer { display:flex; justify-content:space-between; align-items:center; padding-top:20px; border-top:1px solid rgba(255,255,255,0.06); color:#4b5563; font-size:11px; }

/* No data */
#no-data { display:none; text-align:center; padding:80px 24px; color:#6b7280; }

/* Reports List Table styling matching reports page Horizontal Timeline and Dashboard */
.reports-table { width: 100%; border-collapse: collapse; text-align: left; }
.reports-table th { padding: 16px; color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; border-bottom: 1px solid rgba(255,255,255,0.06); }
.reports-table td { padding: 18px 16px; border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: middle; }
.reports-table tr:hover td { background: rgba(255,255,255,0.02); }

.reports-table-sid { color: white; font-weight: 800; font-size: 13.5px; font-family: 'Inter', sans-serif; }
.reports-table-loc { color: #9ca3af; font-size: 13px; font-weight: 500; }

/* PCI Progress Bar */
.pci-bar-wrapper { display: flex; align-items: center; gap: 10px; }
.pci-bar-track { width: 80px; height: 6px; background: rgba(255,255,255,0.08); border-radius: 3px; overflow: hidden; flex-shrink: 0; }
.pci-bar-fill { height: 100%; border-radius: 3px; }
.pci-score-num { font-weight: 800; font-size: 14px; }

/* Condition Outline Badges */
.cond-outline-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; background: transparent; }
.cond-outline-badge.good { color: #10b981; border: 1px solid rgba(16, 185, 129, 0.4); }
.cond-outline-badge.fair { color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.4); }
.cond-outline-badge.poor { color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.4); }
.cond-outline-badge.critical { color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.4); }

/* Action Buttons styling */
.btn-action-view { border: 1px solid #FFD500; color: #FFD500; padding: 6px 14px; border-radius: 4px; font-size: 11px; font-weight: 700; background: transparent; cursor: pointer; transition: all 0.2s ease; text-decoration: none; display: inline-block; text-transform: uppercase; }
.btn-action-view:hover { background: #FFD500; color: black; }

.btn-action-delete { border: 1px solid #ef4444; color: #ef4444; padding: 6px 14px; border-radius: 4px; font-size: 11px; font-weight: 700; background: transparent; cursor: pointer; transition: all 0.2s ease; display: inline-block; text-transform: uppercase; margin-left: 8px; }
.btn-action-delete:hover { background: #ef4444; color: white; }

/* Custom Deletion Modal Overlay styling matching timeline modal */
.custom-modal-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.25s ease; z-index: 9999; }
.custom-modal-overlay.active { opacity: 1; pointer-events: auto; }
.custom-modal-content { background: rgba(15, 15, 15, 0.95); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 32px; width: 90%; max-width: 420px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.6); transform: scale(0.9); transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1); }
.custom-modal-overlay.active .custom-modal-content { transform: scale(1); }
.modal-icon { width: 56px; height: 56px; border-radius: 50%; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
.modal-icon svg { width: 28px; height: 28px; color: #ef4444; }
.custom-modal-content h3 { color: white; font-size: 18px; font-weight: 800; margin-bottom: 10px; }
.custom-modal-content p { color: #9ca3af; font-size: 13px; line-height: 1.6; margin-bottom: 24px; }
.modal-actions { display: flex; gap: 12px; }
.modal-btn-cancel { flex: 1; background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #9ca3af; border-radius: 8px; padding: 10px 0; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s; }
.modal-btn-cancel:hover { background: rgba(255,255,255,0.05); color: white; }
.modal-btn-confirm { flex: 1; background: #ef4444; border: none; color: white; border-radius: 8px; padding: 10px 0; font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
.modal-btn-confirm:hover { background: #dc2626; }

/* ── PRINT STYLES ── */
@media print {
    body { background:white !important; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
    .sidebar, .topbar, .rep-layout { display:block !important; }
    .navbar, .sidebar, .topbar { display:none !important; }
    .rep-content { padding:0 !important; overflow:visible !important; }
    #report-paper { background:white !important; border:none !important; border-radius:0 !important; padding:24px !important; max-width:100% !important; box-shadow:none !important; margin: 0 !important; }
    .rp-logo-text h1, .sc-value, .det-name, .rp-badge-id, .meta-table td:last-child { color:#111 !important; }
    .sc-label, .det-cnt, .rp-badge-label, .rp-badge-date, .meta-table td:first-child, .rp-footer { color:#555 !important; }
    .score-card, .det-card { border:1px solid #ddd !important; background:#f9f9f9 !important; }
    .recom-box { background:#f0fdf4 !important; border:1px solid #bbf7d0 !important; }
    .recom-box p { color:#166534 !important; }
    .rp-header { border-bottom:1px solid #ddd !important; }
    .rp-footer { border-top:1px solid #ddd !important; }
    .conf-track { background:#e5e7eb !important; }
    @page { margin:20mm; }
}
</style>
@endsection

@section('content')
<div class="rep-layout">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main -->
    <main class="main-area">
        <div class="topbar">
            <div class="topbar-left">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;color:#FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h2>Full Report &amp; Export</h2>
            </div>
            <div class="topbar-actions">
                <button class="btn-back" onclick="history.back()">← Back</button>
                <button class="btn-pdf" onclick="exportPDF()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export as PDF
                </button>
            </div>
        </div>

        <div class="rep-content">

            <!-- Reports List Table Container -->
            <div id="reports-list-container" style="display:none; width:100%; max-width:1200px; margin:0 auto 32px;">
                <!-- Top Page Header Row -->
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:28px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:28px; height:28px; color:#FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                        <h1 style="color:white; font-size:26px; font-weight:800; margin:0; letter-spacing:-0.02em;">Analysis History</h1>
                    </div>
                    <a href="/upload" class="btn-new-analysis" style="background:#FFD500; color:black; border:none; padding:10px 20px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:8px; transition:background 0.2s; text-decoration:none;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px; height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                        New Analysis
                    </a>
                </div>

                <!-- Table Card Panel -->
                <div style="background:#0a0a0a; border:1px solid rgba(255,255,255,0.06); border-radius:16px; padding:32px; box-shadow:0 12px 32px rgba(0,0,0,0.4); backdrop-filter:blur(20px);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
                        <h3 style="color:white; font-size:18px; font-weight:800; margin:0;">All Road Analyses</h3>
                        <span id="records-count-badge" style="border: 1px solid #FFD500; color: #FFD500; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; background: rgba(255, 213, 0, 0.05);">0 total records</span>
                    </div>
                    
                    <div style="width:100%;">
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th>SCAN ID</th>
                                    <th>LOCATION</th>
                                    <th>PCI SCORE</th>
                                    <th>CONDITION</th>
                                    <th>SEVERITY</th>
                                    <th>DEFECTS</th>
                                    <th>DATE</th>
                                    <th style="text-align:right;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="reports-list-body">
                                <tr>
                                    <td colspan="5" style="text-align:center; color:#4b5563; padding:24px;">Loading analyses database logs...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- No data state -->
            <div id="no-data" style="display:none;text-align:center;padding:80px 24px;color:#6b7280;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:56px;height:56px;margin:0 auto 16px;color:#374151;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <h3 style="color:white;font-size:18px;font-weight:700;margin-bottom:8px;">No Report Data</h3>
                <p style="margin-bottom:20px;">Analyze a road image first to generate a report.</p>
                <a href="/" style="background:#FFD500;color:black;padding:10px 24px;border-radius:8px;font-weight:700;text-decoration:none;">Go Analyze Now</a>
            </div>

            <!-- Report Paper -->
            <div id="report-paper">
                <!-- Header -->
                <div class="rp-header">
                    <div class="rp-logo-wrap">
                        <img src="{{ asset('images/logo.png') }}" alt="RoadHealth AI">
                        <div class="rp-logo-text">
                            <h1>RoadHealth AI</h1>
                            <p>AI-Powered Pavement Intelligence</p>
                        </div>
                    </div>
                    <div class="rp-badge">
                        <div class="rp-badge-label">REPORT ID</div>
                        <div class="rp-badge-id" id="rp-scan-id">#RH-2025-0001</div>
                        <div class="rp-badge-date" id="rp-date">—</div>
                    </div>
                </div>

                <!-- Score Cards -->
                <div class="rp-score-section">
                    <div class="score-card">
                        <div class="sc-label">Road Condition</div>
                        <div class="sc-value" id="rp-condition" style="color:#FFD500;">POOR</div>
                        <div class="sc-sub">AI Classification</div>
                    </div>
                    <div class="score-card">
                        <div class="sc-label">PCI Score</div>
                        <div class="sc-value" id="rp-pci" style="color:#ef4444;">42<span style="font-size:16px;color:#6b7280;">/100</span></div>
                        <div class="sc-sub">Pavement Condition Index</div>
                    </div>
                    <div class="score-card">
                        <div class="sc-label">Severity</div>
                        <div class="sc-value" id="rp-severity" style="font-size:24px;color:#ef4444;">HIGH</div>
                        <div class="sc-sub" id="rp-issues">— defects detected</div>
                    </div>
                </div>

                <!-- Road Image -->
                <div class="rp-section-title">Uploaded Road Image</div>
                <img id="rp-image" class="rp-image" src="" alt="Road scan" style="display:none;">

                <!-- Detections -->
                <div class="rp-section-title">AI Detection Breakdown</div>
                <div class="det-grid" id="rp-det-grid">
                    <div style="grid-column:1/-1;text-align:center;color:#6b7280;padding:20px;">No detections recorded</div>
                </div>

                <!-- Recommendation -->
                <div class="rp-section-title">Recommended Action</div>
                <div class="recom-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p id="rp-recom">—</p>
                </div>

                <!-- Meta Table -->
                <div class="rp-section-title">Inspection Details</div>
                <table class="meta-table">
                    <tr><td>Location</td><td id="rp-location">Unknown</td></tr>
                    <tr><td>Analysis Mode</td><td id="rp-mode" style="color:#FFD500;">DEMO</td></tr>
                    <tr><td>Analysis Time</td><td id="rp-time">—</td></tr>
                    <tr><td>Generated By</td><td>RoadHealth AI Pavement Analysis Engine</td></tr>
                    <tr><td>Report Date</td><td id="rp-date2">—</td></tr>
                </table>

                <!-- Footer -->
                <div class="rp-footer">
                    <span>RoadHealth AI &copy; {{ date('Y') }} — Confidential Pavement Assessment Report</span>
                    <span>Generated on <span id="rp-now">—</span></span>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- Custom Premium Deletion Modal Overlay -->
<div id="delete-modal" class="custom-modal-overlay">
    <div class="custom-modal-content">
        <div class="modal-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h3>Delete Analysis Report?</h3>
        <p>This action is permanent and cannot be undone. The corresponding database record and analyzed image will be removed from disk.</p>
        <div class="modal-actions">
            <button class="modal-btn-cancel" onclick="closeDeleteModal()">Cancel</button>
            <button class="modal-btn-confirm" id="confirm-delete-btn">Delete Report</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const COLORS = {
    pothole:'#ef4444', alligator_crack:'#f59e0b', longitudinal_crack:'#f59e0b',
    transverse_crack:'#9ca3af', minor_crack:'#6b7280', surface_wear:'#3b82f6', patch:'#8b5cf6'
};

function pciColor(pci){ return pci>=80?'#10b981':pci>=55?'#f59e0b':'#ef4444'; }

function render(d) {
    const pci  = d.pci_score||0;
    const col  = pciColor(pci);
    const now  = new Date().toLocaleString('en-IN');

    let displayDate = now;
    const rawDate = d.analyzed_at || d.created_at;
    if (rawDate) {
        const parsedDate = new Date(rawDate);
        if (!isNaN(parsedDate.getTime())) {
            displayDate = parsedDate.toLocaleString('en-IN', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        } else {
            displayDate = rawDate;
        }
    }

    document.getElementById('rp-scan-id').textContent   = d.scan_id || '#RH-DEMO';
    document.getElementById('rp-date').textContent      = displayDate;
    document.getElementById('rp-date2').textContent     = displayDate;
    document.getElementById('rp-now').textContent       = now;
    document.getElementById('rp-condition').textContent = (d.condition||'Unknown').toUpperCase();
    document.getElementById('rp-condition').style.color = col;
    document.getElementById('rp-pci').innerHTML         = `${pci}<span style="font-size:16px;color:#6b7280;">/100</span>`;
    document.getElementById('rp-pci').style.color       = col;
    document.getElementById('rp-severity').textContent  = (d.severity||'Unknown').toUpperCase();
    document.getElementById('rp-severity').style.color  = pci<55?'#ef4444':pci<75?'#f59e0b':'#10b981';
    document.getElementById('rp-issues').textContent    = `${d.total_defects??0} defects detected`;
    document.getElementById('rp-recom').textContent     = d.recommended_action || 'No recommendation available.';
    document.getElementById('rp-location').textContent  = d.location || 'Unknown Location';
    document.getElementById('rp-mode').textContent      = (d.api_mode||d.mode||'DEMO').toUpperCase();
    document.getElementById('rp-time').textContent      = d.elapsed ? d.elapsed+' sec' : '—';

    // Image (Use CDN/storage path from DB to avoid giant Base64 lag!)
    const imgUrl = d.image_url || (d.image_path ? '/storage/' + d.image_path : null) || d.imageDataUrl;
    if (imgUrl) {
        const img = document.getElementById('rp-image');
        img.src = imgUrl; img.style.display = 'block';
    }

    // Detections
    const grid = document.getElementById('rp-det-grid');
    const dets = d.detections_decoded || (typeof d.detections === 'string' ? JSON.parse(d.detections) : d.detections) || [];
    if (dets.length === 0) {
        grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:#6b7280;padding:20px;">✅ No significant defects detected</div>';
    } else {
        grid.innerHTML = dets.map(det => {
            const c   = COLORS[det.label]||'#6b7280';
            const pct = Math.round((det.confidence||0)*100);
            return `<div class="det-card">
                <div class="det-dot" style="background:${c};"></div>
                <div class="det-info">
                    <div class="det-name">${(det.label||'').replace(/_/g,' ')}</div>
                    <div class="det-cnt">Count: ${det.count||1}</div>
                </div>
                <div style="text-align:right;">
                    <div class="det-conf" style="color:${c};">${pct}%</div>
                    <div class="conf-track"><div class="conf-fill" style="width:${pct}%;background:${c};"></div></div>
                </div>
            </div>`;
        }).join('');
    }
}

function exportPDF() {
    document.title = 'RoadHealth_AI_Report_' + (new Date().toISOString().slice(0,10));
    window.print();
}

const csrfToken = "{{ csrf_token() }}";
let activeDeleteFormId = null;

function openDeleteModal(id) {
    activeDeleteFormId = id;
    document.getElementById('delete-modal').classList.add('active');
}

function closeDeleteModal() {
    activeDeleteFormId = null;
    document.getElementById('delete-modal').classList.remove('active');
}

// Bind close event on click outside and delete confirmation click
window.addEventListener('DOMContentLoaded', () => {
    const deleteModal = document.getElementById('delete-modal');
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    }

    const confirmBtn = document.getElementById('confirm-delete-btn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (activeDeleteFormId) {
                const form = document.getElementById('delete-form-' + activeDeleteFormId);
                if (form) {
                    form.submit();
                }
            }
        });
    }
});

// Load data dynamically using specific database IDs to eliminate slow Base64 decoding
window.addEventListener('DOMContentLoaded', () => {
    // 1. Check URL parameters for explicit ID (?id=12)
    const urlParams = new URLSearchParams(window.location.search);
    let reportId = urlParams.get('id');

    // If there is no explicit ID parameter in the URL, we show the reports list container
    if (!reportId) {
        document.getElementById('report-paper').style.display = 'none';
        document.getElementById('no-data').style.display = 'none';
        document.getElementById('reports-list-container').style.display = 'block';
        
        // Hide default details topbar when viewing the list
        const topBar = document.querySelector('.topbar');
        if (topBar) topBar.style.display = 'none';
        
        // Hide PDF button when viewing the list
        const pdfBtn = document.querySelector('.btn-pdf');
        if (pdfBtn) pdfBtn.style.display = 'none';
        
        // Fetch all report logs
        fetch('/reports?json=1&all=1')
            .then(r => r.json())
            .then(data => {
                const tbody = document.getElementById('reports-list-body');
                
                // Update total dynamic records count badge
                const countBadge = document.getElementById('records-count-badge');
                if (countBadge) {
                    countBadge.textContent = `${data ? data.length : 0} total records`;
                }

                if (!data || data.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#6b7280; padding:32px;">No pavement reports recorded in the database yet.</td></tr>`;
                    return;
                }
                
                tbody.innerHTML = data.map(r => {
                    const pciColor = r.pci_score >= 75 ? '#10b981' : (r.pci_score >= 55 ? '#f59e0b' : '#ef4444');
                    
                    const dateStr = new Date(r.created_at).toLocaleDateString('en-GB', {
                        day: 'numeric', month: 'short', year: 'numeric'
                    });
                    
                    const conditionClass = r.condition ? r.condition.toLowerCase() : 'good';
                    const severityColor = r.severity && (r.severity.toLowerCase() === 'high' || r.severity.toLowerCase() === 'critical') ? '#ef4444' : '#f59e0b';
                    
                    return `
                        <tr>
                            <td>
                                <span class="reports-table-sid">${r.scan_id}</span>
                            </td>
                            <td>
                                <span class="reports-table-loc">${r.location || 'Unknown Location'}</span>
                            </td>
                            <td>
                                <div class="pci-bar-wrapper">
                                    <div class="pci-bar-track">
                                        <div class="pci-bar-fill" style="width: ${r.pci_score}%; background: ${pciColor};"></div>
                                    </div>
                                    <span class="pci-score-num" style="color: ${pciColor};">${r.pci_score}</span>
                                </div>
                            </td>
                            <td>
                                <span class="cond-outline-badge ${conditionClass}">${r.condition}</span>
                            </td>
                            <td>
                                <span style="color: ${severityColor}; font-weight: 700; font-size: 12px; text-transform: uppercase;">${r.severity || 'LOW'}</span>
                            </td>
                            <td>
                                <span style="color: white; font-weight: 700; font-size: 13px;">${r.total_defects ?? 0}</span>
                            </td>
                            <td>
                                <span style="color: #9ca3af; font-size: 12px; font-weight: 500;">${dateStr}</span>
                            </td>
                            <td style="text-align:right; white-space: nowrap;">
                                <a href="/dashboard/report-export?id=${r.id}" class="btn-action-view">View</a>
                                <button type="button" class="btn-action-delete" onclick="openDeleteModal('${r.id}')">Delete</button>
                                <form id="delete-form-${r.id}" action="/reports/${r.id}" method="POST" style="display:none;">
                                    <input type="hidden" name="_token" value="${csrfToken}">
                                    <input type="hidden" name="_method" value="DELETE">
                                </form>
                            </td>
                        </tr>
                    `;
                }).join('');
            })
            .catch(err => {
                const tbody = document.getElementById('reports-list-body');
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#ef4444; padding:24px;">Failed to load reports from database.</td></tr>`;
            });
            
        return;
    }

    // Otherwise, we load and render the specified report ID
    document.getElementById('report-paper').style.display = 'block';
    document.getElementById('reports-list-container').style.display = 'none';
    document.getElementById('no-data').style.display = 'none';
    
    // Show PDF button when viewing a specific report details
    const pdfBtn = document.querySelector('.btn-pdf');
    if (pdfBtn) pdfBtn.style.display = 'flex';

    fetch(`/reports/${reportId}?json=1`)
        .then(r => {
            if (!r.ok) throw new Error('Failed to load report');
            return r.json();
        })
        .then(d => {
            if (d && d.id) {
                render(d);
            } else {
                document.getElementById('no-data').style.display = 'block';
                document.getElementById('report-paper').style.display = 'none';
            }
        })
        .catch(() => {
            document.getElementById('no-data').style.display = 'block';
            document.getElementById('report-paper').style.display = 'none';
        });
});
</script>
@endsection
