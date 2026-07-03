@extends('layouts.app')

@section('title', 'Verification Status')

@section('styles')
<style>
    body { background-color: #050505; color: #f3f4f6; min-height: 100vh; display: flex; flex-direction: column; }
    
    .verification-container {
        max-width: 800px;
        margin: 40px auto;
        width: 100%;
        padding: 0 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .verification-card {
        background: #0d0d0d;
        border: 1px solid #1a1a1a;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.8);
        position: relative;
        overflow: hidden;
    }

    .verification-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #FFD500, #ffea79);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 24px;
    }

    .status-badge.pending {
        background: rgba(255, 213, 0, 0.05);
        color: #FFD500;
        border: 1px solid rgba(255, 213, 0, 0.3);
    }

    .status-badge.rejected {
        background: rgba(239, 68, 68, 0.05);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .verification-title {
        font-size: 28px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 12px;
        letter-spacing: -0.01em;
    }

    .verification-desc {
        color: #9ca3af;
        font-size: 15px;
        line-height: 1.6;
        margin-bottom: 32px;
    }

    /* Timeline Styling */
    .timeline-container {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
        padding: 0 10px;
    }

    .timeline-line {
        position: absolute;
        top: 24px;
        left: 40px;
        right: 40px;
        height: 2px;
        background: #1a1a1a;
        z-index: 1;
    }

    .timeline-line-progress {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: #FFD500;
        transition: width 0.5s ease;
    }

    .timeline-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .step-node {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #0d0d0d;
        border: 2px solid #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: #4b5563;
        transition: all 0.3s;
        box-shadow: 0 0 0 6px #0d0d0d;
    }

    .timeline-step.completed .step-node {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .timeline-step.active .step-node {
        border-color: #FFD500;
        background: rgba(255, 213, 0, 0.1);
        color: #FFD500;
        box-shadow: 0 0 15px rgba(255,213,0,0.2), 0 0 0 6px #0d0d0d;
        animation: activePulse 2s infinite;
    }

    .timeline-step.failed .step-node {
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    @keyframes activePulse {
        0% { box-shadow: 0 0 0 0 rgba(255,213,0,0.4), 0 0 0 6px #0d0d0d; }
        70% { box-shadow: 0 0 0 10px rgba(255,213,0,0), 0 0 0 6px #0d0d0d; }
        100% { box-shadow: 0 0 0 0 rgba(255,213,0,0), 0 0 0 6px #0d0d0d; }
    }

    .step-label {
        margin-top: 12px;
        font-size: 13.5px;
        font-weight: 700;
        color: #6b7280;
        text-align: center;
    }

    .timeline-step.completed .step-label { color: #ffffff; }
    .timeline-step.active .step-label { color: #FFD500; }
    .timeline-step.failed .step-label { color: #ef4444; }

    /* Submitted details */
    .details-panel {
        background: rgba(255,255,255,0.01);
        border: 1px solid #1a1a1a;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 32px;
    }

    .details-header {
        font-size: 14px;
        font-weight: 800;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 16px;
        border-bottom: 1px solid #1a1a1a;
        padding-bottom: 8px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .detail-label {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .detail-value {
        font-size: 13.5px;
        font-weight: 600;
        color: #d1d5db;
    }

    .rejection-banner {
        background: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 32px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .rejection-icon {
        color: #ef4444;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .rejection-text {
        font-size: 13.5px;
        line-height: 1.5;
    }

    .rejection-title {
        font-weight: 800;
        color: #ef4444;
        margin-bottom: 4px;
    }

    .rejection-desc {
        color: #fca5a5;
    }

    /* Actions */
    .actions-panel {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        border-top: 1px solid #1a1a1a;
        padding-top: 24px;
    }

    .btn-logout {
        background: transparent;
        border: 1px solid #374151;
        color: #d1d5db;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 13.5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-logout:hover {
        background: rgba(255,255,255,0.03);
        border-color: #4b5563;
        color: #ffffff;
    }
</style>
@endsection

@section('content')
<div class="verification-container">
    <div class="verification-card">
        @if(auth()->user()->status === 'rejected')
            <!-- Rejection Badge -->
            <div class="status-badge rejected">
                <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                Registration Rejected
            </div>
            
            <h1 class="verification-title">Credentials Review Failed</h1>
            <p class="verification-desc">The municipal authority review team has declined your account activation request. Please review the explanation details below.</p>
            
            <!-- Rejection Banner -->
            <div class="rejection-banner">
                <svg class="rejection-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div class="rejection-text">
                    <div class="rejection-title">Reason for rejection:</div>
                    <div class="rejection-desc">{{ auth()->user()->approval_remarks ?? 'The submitted credentials or document uploads were invalid/illegible.' }}</div>
                </div>
            </div>
        @else
            <!-- Pending Badge -->
            <div class="status-badge pending">
                <svg fill="currentColor" viewBox="0 0 20 20" style="width: 14px; height: 14px;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                Verification Pending
            </div>
            
            <h1 class="verification-title">Verifying Identity & Jurisdiction</h1>
            <p class="verification-desc">Your department credentials have been submitted successfully. A municipal officer or system administrator is currently auditing your details against department rolls.</p>
        @endif

        <!-- Timeline -->
        <div class="timeline-container">
            <div class="timeline-line">
                <div class="timeline-line-progress" style="width: @if(auth()->user()->status === 'rejected') 50% @else 50% @endif;"></div>
            </div>
            
            <!-- Step 1 -->
            <div class="timeline-step completed">
                <div class="step-node">
                    <svg fill="currentColor" viewBox="0 0 20 20" style="width: 18px; height: 18px;"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div class="step-label">Submitted</div>
            </div>
            
            <!-- Step 2 -->
            @if(auth()->user()->status === 'rejected')
                <div class="timeline-step failed">
                    <div class="step-node">!</div>
                    <div class="step-label">Review Failed</div>
                </div>
            @else
                <div class="timeline-step active">
                    <div class="step-node">2</div>
                    <div class="step-label">In Review</div>
                </div>
            @endif
            
            <!-- Step 3 -->
            <div class="timeline-step">
                <div class="step-node">3</div>
                <div class="step-label">Active</div>
            </div>
        </div>

        <!-- Submitted Profile Data -->
        <div class="details-panel">
            <div class="details-header">Registration Profile Data</div>
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Full Name</span>
                    <span class="detail-value">{{ auth()->user()->name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Official Email</span>
                    <span class="detail-value">{{ auth()->user()->email }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jurisdiction / Authority</span>
                    <span class="detail-value">{{ auth()->user()->territory }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Assigned Role</span>
                    <span class="detail-value" style="text-transform: capitalize;">{{ auth()->user()->role }}</span>
                </div>
                @if(auth()->user()->employee_id)
                <div class="detail-item">
                    <span class="detail-label">Employee ID</span>
                    <span class="detail-value">{{ auth()->user()->employee_id }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Department</span>
                    <span class="detail-value">{{ auth()->user()->department }}</span>
                </div>
                @endif
                <div class="detail-item">
                    <span class="detail-label">Zone / Ward</span>
                    <span class="detail-value">{{ auth()->user()->zone }} / {{ auth()->user()->ward }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Area / Locality</span>
                    <span class="detail-value">{{ auth()->user()->area }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="actions-panel">
            <a href="{{ url('/logout') }}" class="btn-logout">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Log Out
            </a>
        </div>
    </div>
</div>
@endsection
