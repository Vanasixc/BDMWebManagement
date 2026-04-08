<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>403 — Akses Ditolak | WH Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script>if(localStorage.getItem('darkMode')==='1')document.documentElement.classList.add('dark');</script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            color: #0f172a;
        }
        html.dark body { background: #0f172a; color: #f1f5f9; }
        .card {
            text-align: center;
            padding: 3rem 2rem;
            max-width: 420px;
            width: 100%;
        }
        .icon-wrap {
            width: 80px; height: 80px;
            background: rgba(239,68,68,0.12);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon-wrap svg { width: 40px; height: 40px; color: #EF4444; stroke: currentColor; }
        h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; }
        p { font-size: 0.875rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem; }
        html.dark p { color: #94a3b8; }
        a {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.625rem 1.5rem;
            background: #3B82F6; color: #fff;
            border-radius: 0.75rem; font-weight: 700; font-size: 0.875rem;
            text-decoration: none;
            transition: background 0.2s;
        }
        a:hover { background: #2563EB; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap">
            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>
        <h1>403 — Akses Ditolak</h1>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.<br/>Halaman ini hanya dapat diakses oleh <strong>Super Admin</strong>.</p>
        <a href="{{ url('/dashboard') }}">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
