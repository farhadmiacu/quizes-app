<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'Admin') — Quiz Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #0a0c12;
            --surface: #12151f;
            --surface2: #1a1e2e;
            --border: #252a3d;
            --accent: #00d4ff;
            --green: #00e5a0;
            --red: #ff4d6d;
            --text: #dde4f0;
            --muted: #5a6380;
        }

        body {
            font-family: 'Inter', 'Hind Siliguri', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo span {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: 0.5px;
        }

        .sidebar-logo small {
            display: block;
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 2px;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }

        .nav-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            padding: 8px 8px 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s ease;
            margin-bottom: 2px;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--surface2);
            color: var(--text);
        }

        .nav-link.active {
            color: var(--accent);
        }

        .nav-link svg { flex-shrink: 0; }

        .nav-divider {
            height: 1px;
            background: var(--border);
            margin: 12px 0;
        }

        /* ── Main layout ── */
        .main-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* ── Topbar ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
        }

        .topbar-actions { display: flex; gap: 10px; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--accent);
            color: #000;
        }
        .btn-primary:hover { background: #00b8e0; }

        .btn-sm {
            padding: 5px 11px;
            font-size: 0.75rem;
            border-radius: 6px;
        }

        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { color: var(--text); border-color: var(--muted); background: var(--surface2); }

        .btn-danger {
            background: rgba(255,77,109,0.12);
            color: var(--red);
            border: 1px solid rgba(255,77,109,0.2);
        }
        .btn-danger:hover { background: rgba(255,77,109,0.22); }

        .btn-success {
            background: rgba(0,229,160,0.12);
            color: var(--green);
            border: 1px solid rgba(0,229,160,0.2);
        }
        .btn-success:hover { background: rgba(0,229,160,0.22); }

        .btn-warning {
            background: rgba(255,193,7,0.12);
            color: #ffc107;
            border: 1px solid rgba(255,193,7,0.2);
        }
        .btn-warning:hover { background: rgba(255,193,7,0.22); }

        /* ── Content area ── */
        .content {
            padding: 28px;
            flex: 1;
        }

        /* ── Flash messages ── */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(0,229,160,0.1);
            border: 1px solid rgba(0,229,160,0.25);
            color: var(--green);
        }

        .alert-error {
            background: rgba(255,77,109,0.1);
            border: 1px solid rgba(255,77,109,0.25);
            color: var(--red);
        }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text);
        }

        .card-body { padding: 20px; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 11px 16px;
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        td {
            padding: 13px 16px;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--surface2); }

        /* ── Form elements ── */
        .form-group { margin-bottom: 20px; }

        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 14px;
            color: var(--text);
            font-size: 0.9rem;
            font-family: inherit;
            transition: border-color 0.15s;
            outline: none;
        }

        input[type="text"]:focus,
        textarea:focus,
        select:focus {
            border-color: var(--accent);
        }

        textarea { resize: vertical; min-height: 100px; }

        select option { background: var(--surface2); }

        .error-msg {
            color: var(--red);
            font-size: 0.78rem;
            margin-top: 5px;
        }

        /* ── Badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-published {
            background: rgba(0,229,160,0.12);
            color: var(--green);
        }

        .badge-draft {
            background: rgba(90,99,128,0.2);
            color: var(--muted);
        }

        /* ── Pill options preview ── */
        .pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.72rem;
            margin: 2px;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }

        .pill-correct {
            background: rgba(0,229,160,0.15);
            color: var(--green);
            border: 1px solid rgba(0,229,160,0.3);
        }

        .pill-wrong {
            background: rgba(90,99,128,0.15);
            color: var(--muted);
            border: 1px solid var(--border);
        }

        /* ── Stat cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }

        .stat-card .stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-value.accent { color: var(--accent); }
        .stat-value.green { color: var(--green); }
        .stat-value.muted { color: var(--muted); }

        /* ── Actions row ── */
        .actions { display: flex; gap: 6px; align-items: center; flex-wrap: nowrap; }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo">
        <span>⚡ Quiz Admin</span>
        <small>Management Panel</small>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Questions</div>
        <a href="{{ route('admin.questions.index') }}"
           class="nav-link {{ request()->routeIs('admin.questions.index') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
            All Questions
        </a>
        <a href="{{ route('admin.questions.create') }}"
           class="nav-link {{ request()->routeIs('admin.questions.create') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Create New
        </a>
        <div class="nav-divider"></div>
        <div class="nav-label">Public</div>
        <a href="{{ route('quiz.index') }}" class="nav-link" target="_blank">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
            View Quiz
        </a>
    </nav>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <div class="topbar-actions">
            <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Question
            </a>
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
