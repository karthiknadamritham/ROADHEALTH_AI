<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">My Inspections</span>
            <span class="stat-value">{{ $totalAnalyses }}</span>
            <span class="stat-trend trend-up">&uarr; System Active</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Average PCI Score</span>
            <span class="stat-value">{{ $avgPciScore }} <span style="font-size: 14px; color: #6b7280;">/100</span></span>
            @if($avgPciScore >= 75)
                <span class="stat-trend trend-up">Overall Good</span>
            @elseif($avgPciScore >= 55)
                <span class="stat-trend trend-up">Overall Fair</span>
            @else
                <span class="stat-trend trend-down">Overall Poor</span>
            @endif
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Critical Spots</span>
            <span class="stat-value" style="{{ $highRiskCount > 0 ? 'color: #ef4444;' : '' }}">{{ $highRiskCount }}</span>
            <span class="stat-trend trend-up">&uarr; Requiring Action</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-info">
            <span class="stat-title">Estimated Scans Coverage</span>
            <span class="stat-value">{{ $totalAnalyses * 0.5 }} <span style="font-size: 14px; color: #6b7280;">km</span></span>
            <span class="stat-trend trend-up">Inspected road length</span>
        </div>
        <div class="stat-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
        </div>
    </div>
</div>

<!-- Main Grid layout with AI Chat & Chart -->
<div class="main-grid">
    <!-- Left: Line Chart Panel -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Inspection Surface Quality Split</div>
            <div class="panel-action" style="cursor: pointer;" onclick="window.location='/reports'">Detailed History &rarr;</div>
        </div>
        
        <div class="line-chart-box">
            <div class="chart-y-axis">
                <span>Good</span>
                <span>Fair</span>
                <span>Poor</span>
                <span>0</span>
            </div>
            <div class="chart-bars-wrap" style="display: flex; gap: 20px; align-items: flex-end; height: 100%; justify-content: space-around;">
                <div class="chart-bar-container" style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; gap: 8px;">
                    <div style="background: #10b981; width: 30px; height: {{ $goodPercent }}%; border-radius: 4px 4px 0 0; min-height: 5px;"></div>
                    <span style="font-size: 11px; color: #9ca3af; font-weight: 600;">{{ $goodPercent }}%</span>
                </div>
                <div class="chart-bar-container" style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; gap: 8px;">
                    <div style="background: #f59e0b; width: 30px; height: {{ $fairPercent }}%; border-radius: 4px 4px 0 0; min-height: 5px;"></div>
                    <span style="font-size: 11px; color: #9ca3af; font-weight: 600;">{{ $fairPercent }}%</span>
                </div>
                <div class="chart-bar-container" style="display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; gap: 8px;">
                    <div style="background: #ef4444; width: 30px; height: {{ $poorPercent }}%; border-radius: 4px 4px 0 0; min-height: 5px;"></div>
                    <span style="font-size: 11px; color: #9ca3af; font-weight: 600;">{{ $poorPercent }}%</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right: AI Assistant Chat Panel -->
    <div class="panel ai-assistant" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; gap: 16px;">
        <div style="background: rgba(255, 213, 0, 0.1); padding: 20px; border-radius: 50%;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 48px; height: 48px; color: #FFD500;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </div>
        <div>
            <h3 style="color: white; margin-bottom: 8px; font-size: 20px;">Dedicated AI Assistant</h3>
            <p style="color: #9ca3af; font-size: 14px; line-height: 1.5; padding: 0 20px;">
                The AI Chat interface has been upgraded and moved to a dedicated full-screen view for a more focused experience.
            </p>
        </div>
        <a href="/ai-chat" class="btn" style="text-decoration: none; padding: 12px 24px; border-radius: 8px; color: black; background: #FFD500; font-weight: 700; display: inline-block; margin-top: 10px;">Open AI Assistant</a>
    </div>
</div>

<!-- Lower Grid: QR Code Banner & Nearest Authorities -->
<div class="main-grid">
    <!-- QR Code & App Banner -->
    <div class="qr-banner">
        <div class="qr-left">
            <div class="qr-code-box">
                <svg viewBox="0 0 100 100" style="width: 100%; height: 100%;">
                    <rect x="0" y="0" width="20" height="20" fill="black"/>
                    <rect x="5" y="5" width="10" height="10" fill="white"/>
                    <rect x="80" y="0" width="20" height="20" fill="black"/>
                    <rect x="85" y="5" width="10" height="10" fill="white"/>
                    <rect x="0" y="80" width="20" height="20" fill="black"/>
                    <rect x="5" y="85" width="10" height="10" fill="white"/>
                    <rect x="30" y="30" width="40" height="40" fill="black"/>
                    <rect x="40" y="40" width="20" height="20" fill="white"/>
                    <rect x="10" y="40" width="10" height="30" fill="black"/>
                    <rect x="80" y="40" width="10" height="30" fill="black"/>
                </svg>
            </div>
            <div class="qr-text">
                <h4>Inspect via Mobile App</h4>
                <p>Scan this QR code to download our field surveyor app. Directly upload photos from your smartphone during inspection tours.</p>
            </div>
        </div>
        <button class="btn-qr">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Download App
        </button>
    </div>

    <!-- Nearest Authorities List -->
    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">Nearest Authorities</div>
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px; color: #6b7280;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
        </div>
        
        <div class="authority-list">
            <div class="auth-item">
                <div class="auth-left">
                    <div class="auth-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <div class="auth-name">Municipal Corporation Delhi</div>
                        <div class="auth-dist">1.2 km away</div>
                    </div>
                </div>
                <span class="auth-status">ACTIVE</span>
            </div>
            
            <div class="auth-item">
                <div class="auth-left">
                    <div class="auth-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <div class="auth-name">NHAI Regional HQ</div>
                        <div class="auth-dist">4.5 km away</div>
                    </div>
                </div>
                <span class="auth-status">ACTIVE</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Inspections Table -->
<div class="panel">
    <div class="panel-header">
        <div class="panel-title">Recent Inspections</div>
        <div class="panel-action" style="cursor:pointer;" onclick="window.location='/reports'">View All &rarr;</div>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Scan Details</th>
                    <th>Location</th>
                    <th>PCI Score</th>
                    <th>Condition</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="inspections-tbody">
                @forelse($recentAnalyses as $analysis)
                <tr>
                    <td>
                        <div class="td-scan">
                            @if($analysis->image_path)
                                <img src="{{ asset('storage/' . $analysis->image_path) }}" alt="Scan" class="td-img">
                            @else
                                <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 4px;"></div>
                            @endif
                            <div>
                                <div style="color: white; font-weight: 600;">{{ $analysis->title ?? $analysis->scan_id }}</div>
                                <div style="font-size: 11px; color: #6b7280;">Scan: {{ $analysis->scan_id }} &bull; {{ $analysis->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-weight: 600; color: white;">{{ Str::limit($analysis->location, 30) }}</span></td>
                    <td><span style="color: white; font-weight: 700;">{{ $analysis->pci_score }}</span>/100</td>
                    <td>
                        @if($analysis->pci_score >= 75)
                            <span class="badge-status badge-good">GOOD</span>
                        @elseif($analysis->pci_score >= 55)
                            <span class="badge-status badge-fair">FAIR</span>
                        @else
                            <span class="badge-status badge-poor">POOR</span>
                        @endif
                    </td>
                    <td>
                        <button class="action-btn view-report-btn" 
                            onclick="window.location='/reports/{{ $analysis->id }}'">
                            View Report
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #6b7280; padding: 32px;">No analyses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
