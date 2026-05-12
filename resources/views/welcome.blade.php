<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HelpDesk') }} — IT Support Management System</title>
    <meta name="description"
        content="HelpDesk is a web-based IT support ticketing system with role-based access, priority tracking, reply threads and PDF reports.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ── Reset ──────────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', system-ui, 'Segoe UI', sans-serif;
            background-color: #0a1628;
            background-image: radial-gradient(circle, rgba(77, 157, 224, 0.07) 1px, transparent 1px);
            background-size: 30px 30px;
            color: #fff;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* ── Glow blobs ─────────────────────────────────────── */
        .blob {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .blob-tr {
            width: 500px;
            height: 500px;
            top: -120px;
            right: -120px;
            background: radial-gradient(circle, rgba(42, 99, 199, 0.18) 0%, transparent 70%);
        }

        .blob-bl {
            width: 350px;
            height: 350px;
            bottom: -80px;
            left: -80px;
            background: radial-gradient(circle, rgba(42, 99, 199, 0.14) 0%, transparent 70%);
        }

        /* ── Layout utility ─────────────────────────────────── */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            position: relative;
            z-index: 1;
        }

        /* ══════════════════════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════════════════════ */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(10, 22, 40, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            height: 62px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Brand */
        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-brand-icon {
            width: 34px;
            height: 34px;
            background: #2a63c7;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-brand-icon svg {
            width: 18px;
            height: 18px;
            color: #fff;
        }

        .nav-brand-name {
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: -0.01em;
        }

        /* Nav links */
        .nav-links {
            display: flex;
            gap: 32px;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255, 255, 255, 0.55);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #fff;
        }

        /* Nav actions */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            padding: 0 16px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-ghost:hover {
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
        }

        .btn-blue {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 36px;
            padding: 0 18px;
            background: #2a63c7;
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-blue:hover {
            background: #3572d4;
        }

        /* ══════════════════════════════════════════════════════
           HERO
        ══════════════════════════════════════════════════════ */
        .hero {
            padding: 96px 0 80px;
            position: relative;
            z-index: 1;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        /* Hero left */
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(42, 99, 199, 0.15);
            border: 1px solid rgba(42, 99, 199, 0.3);
            border-radius: 100px;
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 500;
            color: #7ab3f0;
            margin-bottom: 22px;
        }

        .hero-badge-dot {
            width: 6px;
            height: 6px;
            background: #4d9de0;
            border-radius: 50%;
        }

        .hero-h1 {
            font-size: clamp(34px, 4vw, 52px);
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -0.03em;
            color: #fff;
            margin-bottom: 20px;
        }

        .hero-h1 .accent {
            color: #4d9de0;
        }

        .hero-desc {
            color: rgba(255, 255, 255, 0.5);
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 440px;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-blue-lg {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 46px;
            padding: 0 24px;
            background: #2a63c7;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-blue-lg:hover {
            background: #3572d4;
        }

        .btn-blue-lg:active {
            transform: scale(0.98);
        }

        .btn-ghost-lg {
            display: inline-flex;
            align-items: center;
            height: 46px;
            padding: 0 24px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.65);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-ghost-lg:hover {
            border-color: rgba(255, 255, 255, 0.35);
            color: #fff;
        }

        /* Hero right — ticket preview card */
        .ticket-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.45);
        }

        .ticket-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .ticket-card-title {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(74, 222, 128, 0.12);
            border: 1px solid rgba(74, 222, 128, 0.25);
            border-radius: 100px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 500;
            color: #4ade80;
        }

        .live-dot {
            width: 5px;
            height: 5px;
            background: #4ade80;
            border-radius: 50%;
            animation: pulse-live 1.6s ease-in-out infinite;
        }

        @keyframes pulse-live {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.35;
            }
        }

        .ticket-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .ticket-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .ticket-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .ticket-info {
            flex: 1;
            min-width: 0;
        }

        .ticket-subject {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ticket-meta {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.35);
            margin-top: 2px;
        }

        .ticket-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .ticket-time {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.3);
        }

        .status-badge {
            font-size: 10px;
            font-weight: 500;
            border-radius: 6px;
            padding: 2px 8px;
        }

        .status-open {
            background: rgba(59, 130, 246, 0.18);
            color: #60a5fa;
        }

        .status-progress {
            background: rgba(234, 179, 8, 0.15);
            color: #facc15;
        }

        .status-pending {
            background: rgba(249, 115, 22, 0.15);
            color: #fb923c;
        }

        .status-resolved {
            background: rgba(74, 222, 128, 0.12);
            color: #4ade80;
        }

        /* ══════════════════════════════════════════════════════
           STATS ROW
        ══════════════════════════════════════════════════════ */
        .stats-row {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            padding: 32px 0;
            position: relative;
            z-index: 1;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 8px 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.07);
        }

        .stat-item:last-child {
            border-right: none;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            color: #4d9de0;
            letter-spacing: -0.03em;
        }

        .stat-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 4px;
            text-align: center;
        }

        /* ══════════════════════════════════════════════════════
           FEATURES
        ══════════════════════════════════════════════════════ */
        .section {
            padding: 88px 0;
            position: relative;
            z-index: 1;
        }

        .section-eyebrow {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #4d9de0;
            margin-bottom: 12px;
        }

        .section-heading {
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #fff;
            margin-bottom: 48px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 22px;
            transition: border-color 0.25s, background 0.25s, transform 0.2s;
        }

        .feature-card:hover {
            border-color: rgba(77, 157, 224, 0.25);
            background: rgba(255, 255, 255, 0.07);
            transform: translateY(-2px);
        }

        .feature-icon-box {
            width: 38px;
            height: 38px;
            background: rgba(42, 99, 199, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .feature-icon-box svg {
            width: 18px;
            height: 18px;
            color: #4d9de0;
        }

        .feature-title {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 7px;
        }

        .feature-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.4);
            line-height: 1.65;
        }

        /* ══════════════════════════════════════════════════════
           CALL TO ACTION
        ══════════════════════════════════════════════════════ */
        .cta-section {
            padding: 0 0 88px;
            position: relative;
            z-index: 1;
        }

        .cta-card {
            background: rgba(42, 99, 199, 0.1);
            border: 1px solid rgba(42, 99, 199, 0.25);
            border-radius: 18px;
            padding: 48px 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
        }

        .cta-heading {
            font-size: clamp(20px, 2.5vw, 28px);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: #fff;
            margin-bottom: 10px;
        }

        .cta-sub {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.45);
            line-height: 1.6;
        }

        /* ══════════════════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════════════════ */
        .footer {
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding: 24px;
            text-align: center;
            color: rgba(255, 255, 255, 0.2);
            font-size: 13px;
            position: relative;
            z-index: 1;
        }

        /* ══════════════════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════════════════ */
        @media (max-width: 900px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stat-item:nth-child(2) {
                border-right: none;
            }

            .stat-item:nth-child(3),
            .stat-item:nth-child(4) {
                border-top: 1px solid rgba(255, 255, 255, 0.07);
            }

            .cta-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 36px 28px;
            }

            .nav-links {
                display: none;
            }
        }

        @media (max-width: 540px) {
            .features-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .hero {
                padding: 64px 0 48px;
            }
        }
    </style>
</head>

<body>

    <!-- Glow blobs -->
    <div class="blob blob-tr" aria-hidden="true"></div>
    <div class="blob blob-bl" aria-hidden="true"></div>

    <!-- ══ NAVBAR ══════════════════════════════════════════════ -->
    <nav class="navbar">
        <div class="navbar-inner">

            <!-- Brand -->
            <a href="/" class="nav-brand" aria-label="HelpDesk home">
                <div class="nav-brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 18v-6a9 9 0 0 1 18 0v6" />
                        <path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z" />
                        <path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z" />
                    </svg>
                </div>
                <span class="nav-brand-name">HelpDesk</span>
            </a>

            <!-- Nav links -->
            <ul class="nav-links" role="list">
                <li><a href="#features">Features</a></li>
                <li><a href="#how-it-works">How it works</a></li>
                <li><a href="#roles">Roles</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>

            <!-- Actions -->
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn-ghost">Sign in</a>
                <a href="{{ route('login') }}" class="btn-blue">Get started</a>
            </div>

        </div>
    </nav>

    <!-- ══ HERO ════════════════════════════════════════════════ -->
    <section class="hero" id="hero" aria-labelledby="hero-heading">
        <div class="container">
            <div class="hero-grid">

                <!-- Left: copy -->
                <div class="hero-left">
                    <div class="hero-badge" aria-label="Product category">
                        <span class="hero-badge-dot" aria-hidden="true"></span>
                        IT Support Management System
                    </div>

                    <h1 class="hero-h1" id="hero-heading">
                        Resolve issues faster<br>
                        with <span class="accent">HelpDesk</span>
                    </h1>

                    <p class="hero-desc">
                        A web-based ticketing platform built for support teams of every size.
                        Track, prioritize, and close requests with complete visibility —
                        from first submission to final resolution.
                    </p>

                    <div class="hero-actions">
                        <a href="{{ route('login') }}" class="btn-blue-lg" id="hero-signin-btn">
                            Sign in to portal
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="#features" class="btn-ghost-lg" id="hero-learn-btn">Learn more</a>
                    </div>
                </div>

                <!-- Right: ticket preview card -->
                <div class="hero-right">
                    <div class="ticket-card" aria-label="Sample ticket list preview">
                        <div class="ticket-card-header">
                            <span class="ticket-card-title">Recent tickets</span>
                            <span class="live-badge">
                                <span class="live-dot" aria-hidden="true"></span>
                                Live
                            </span>
                        </div>

                        <!-- Ticket rows -->
                        <div class="ticket-row">
                            <span class="ticket-dot" style="background:#60a5fa;" aria-hidden="true"></span>
                            <div class="ticket-info">
                                <div class="ticket-subject">VPN access not working after update</div>
                                <div class="ticket-meta">Maria Santos</div>
                            </div>
                            <div class="ticket-right">
                                <span class="ticket-time">2m ago</span>
                                <span class="status-badge status-open">Open</span>
                            </div>
                        </div>

                        <div class="ticket-row">
                            <span class="ticket-dot" style="background:#facc15;" aria-hidden="true"></span>
                            <div class="ticket-info">
                                <div class="ticket-subject">Printer offline — Floor 3 unit B</div>
                                <div class="ticket-meta">Juan dela Cruz</div>
                            </div>
                            <div class="ticket-right">
                                <span class="ticket-time">18m ago</span>
                                <span class="status-badge status-progress">In Progress</span>
                            </div>
                        </div>

                        <div class="ticket-row">
                            <span class="ticket-dot" style="background:#fb923c;" aria-hidden="true"></span>
                            <div class="ticket-info">
                                <div class="ticket-subject">Email client crashing on startup</div>
                                <div class="ticket-meta">Anna Reyes</div>
                            </div>
                            <div class="ticket-right">
                                <span class="ticket-time">1h ago</span>
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                        </div>

                        <div class="ticket-row">
                            <span class="ticket-dot" style="background:#4ade80;" aria-hidden="true"></span>
                            <div class="ticket-info">
                                <div class="ticket-subject">Reset password for new hire account</div>
                                <div class="ticket-meta">Carlos Bautista</div>
                            </div>
                            <div class="ticket-right">
                                <span class="ticket-time">3h ago</span>
                                <span class="status-badge status-resolved">Resolved</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ══ STATS ROW ═══════════════════════════════════════════ -->
    <div class="stats-row" aria-label="System statistics">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-value">4+</span>
                    <span class="stat-label">User roles supported</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">5+</span>
                    <span class="stat-label">Ticket status levels</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">100%</span>
                    <span class="stat-label">Web-based, no install</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">1×</span>
                    <span class="stat-label">Centralized platform</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ══ FEATURES ════════════════════════════════════════════ -->
    <section class="section" id="features" aria-labelledby="features-heading">
        <div class="container">
            <p class="section-eyebrow">Features</p>
            <h2 class="section-heading" id="features-heading">Everything your team needs</h2>

            <div class="features-grid">

                <!-- 1 -->
                <div class="feature-card">
                    <div class="feature-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                            <rect x="9" y="3" width="6" height="4" rx="1" />
                            <path d="M9 12h6M9 16h4" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Ticket Management</h3>
                    <p class="feature-desc">Create, assign, track, and close support tickets with full reply threads.
                        Keep every request organized in one place.</p>
                </div>

                <!-- 2 -->
                <div class="feature-card">
                    <div class="feature-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                            <path d="M17 11l1.5 1.5L21 10" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Role-Based Access</h3>
                    <p class="feature-desc">Four distinct roles — Admin, Supervisor, Support Agent, and Requester — each
                        with scoped permissions and dashboards.</p>
                </div>

                <!-- 3 -->
                <div class="feature-card">
                    <div class="feature-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 3v18h18" />
                            <path d="M7 16l4-4 4 4 4-6" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Reports &amp; Analytics</h3>
                    <p class="feature-desc">Export PDF reports, filter by date range, and review agent performance
                        metrics to improve team efficiency.</p>
                </div>

                <!-- 4 -->
                <div class="feature-card">
                    <div class="feature-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2l3 6.5L22 9l-5 5 1.18 7L12 18l-6.18 3L7 14 2 9l7-.5z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Priority &amp; Status Tracking</h3>
                    <p class="feature-desc">Tag tickets as Low, Medium, High, or Critical. Five status levels — Open, In
                        Progress, Pending, Resolved, Closed.</p>
                </div>

                <!-- 5 -->
                <div class="feature-card">
                    <div class="feature-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Reply &amp; Update Logs</h3>
                    <p class="feature-desc">Threaded replies keep conversations in context. Internal notes let agents
                        communicate without exposing details to requesters.</p>
                </div>

                <!-- 6 -->
                <div class="feature-card">
                    <div class="feature-icon-box" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <h3 class="feature-title">Secure Authentication</h3>
                    <p class="feature-desc">Role middleware enforces access boundaries at every route. Passwords are
                        hashed using Laravel's bcrypt driver.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- ══ CALL TO ACTION ═══════════════════════════════════════ -->
    <section class="cta-section" id="contact" aria-labelledby="cta-heading">
        <div class="container">
            <div class="cta-card">
                <div class="cta-text">
                    <h2 class="cta-heading" id="cta-heading">Ready to manage your support team?</h2>
                    <p class="cta-sub">Sign in to the portal and start resolving tickets immediately.<br>No installation
                        needed — it's all in your browser.</p>
                </div>
                <a href="{{ route('login') }}" class="btn-blue-lg" id="cta-signin-btn">
                    Sign in to portal
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ══ FOOTER ══════════════════════════════════════════════ -->
    <footer class="footer" id="how-it-works" role="contentinfo">
        <div class="container">

        </div>
    </footer>

</body></html>