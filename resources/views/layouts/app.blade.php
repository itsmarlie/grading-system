<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'EduGrade')</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans&family=DM+Serif+Display&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
  :root {
    --green-950: #052e16; --green-900: #14532d; --green-800: #166534;
    --green-700: #15803d; --green-600: #16a34a; --green-500: #22c55e;
    --green-400: #4ade80; --green-300: #86efac; --green-200: #bbf7d0;
    --green-100: #dcfce7; --green-50: #f0fdf4;
    --white: #ffffff; --gray-50: #f9fafb; --gray-100: #f3f4f6;
    --gray-200: #e5e7eb; --gray-300: #d1d5db; --gray-400: #9ca3af;
    --gray-500: #6b7280; --gray-600: #4b5563; --gray-700: #374151;
    --sidebar-w: 260px; --header-h: 68px; --radius: 14px; --radius-sm: 8px;
    --shadow: 0 2px 16px rgba(5,46,22,.07); --shadow-md: 0 6px 28px rgba(5,46,22,.11);
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { font-size: 15px; scroll-behavior: smooth; }
  body { font-family: 'DM Sans', sans-serif; background: var(--green-50); color: var(--gray-700); min-height: 100vh; display: flex; flex-direction: row; overflow-x: hidden; }

  /* ── SIDEBAR ── */
  .sidebar { width: var(--sidebar-w); min-height: 100vh; background: var(--green-900); display: flex; flex-direction: column; position: fixed; left: 0; top: 0; bottom: 0; z-index: 100; transition: transform .25s ease; }
  .sidebar-logo { padding: 28px 24px 20px; border-bottom: 1px solid rgba(255,255,255,.08); position: relative; }
  .logo-mark { display: flex; align-items: center; gap: 11px; }
  .logo-icon { width: 38px; height: 38px; background: var(--green-500); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
  .logo-text { font-family: 'DM Serif Display', serif; color: var(--white); font-size: 1.25rem; line-height: 1.1; }
  .logo-text span { color: var(--green-400); }
  .logo-sub { color: var(--green-300); font-size: .7rem; letter-spacing: .06em; text-transform: uppercase; margin-top: 3px; }
  .sidebar-close { display: none; position: absolute; top: 14px; right: 14px; background: rgba(255,255,255,.1); border: none; color: white; border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 1rem; align-items: center; justify-content: center; }
  .sidebar-user { padding: 18px 20px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(255,255,255,.08); }
  .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--green-600); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: .9rem; flex-shrink: 0; border: 2px solid var(--green-400); }
  .user-info .name { color: var(--white); font-weight: 500; font-size: .88rem; }
  .user-info .role { display: inline-block; background: var(--green-700); color: var(--green-200); font-size: .66rem; letter-spacing: .06em; text-transform: uppercase; padding: 2px 8px; border-radius: 20px; margin-top: 3px; }
  .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }
  .nav-section-label { color: var(--green-500); font-size: .67rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; padding: 10px 12px 6px; margin-top: 8px; }
  .nav-item { display: flex; align-items: center; gap: 11px; padding: 10px 12px; border-radius: var(--radius-sm); color: var(--green-200); font-size: .875rem; font-weight: 400; cursor: pointer; transition: all .18s; margin-bottom: 2px; position: relative; text-decoration: none; }
  .nav-item:hover { background: rgba(255,255,255,.07); color: var(--white); }
  .nav-item.active { background: var(--green-700); color: var(--white); font-weight: 500; }
  .nav-item.active::before { content: ''; position: absolute; left: 0; top: 6px; bottom: 6px; width: 3px; background: var(--green-400); border-radius: 3px; }
  .nav-icon { font-size: 1.1rem; width: 22px; text-align: center; }
  .nav-badge { margin-left: auto; background: var(--green-500); color: white; font-size: .65rem; font-weight: 700; padding: 2px 7px; border-radius: 20px; }
  .sidebar-footer { padding: 16px 12px; border-top: 1px solid rgba(255,255,255,.08); }
  .logout-btn { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--radius-sm); color: var(--green-300); font-size: .875rem; cursor: pointer; transition: all .18s; border: none; background: none; width: 100%; }
  .logout-btn:hover { background: rgba(255,255,255,.06); color: var(--white); }

  /* ── SIDEBAR OVERLAY ── */
  .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(5,46,22,.5); z-index: 99; }
  .sidebar-overlay.open { display: block; }

  /* ── MAIN ── */
  .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; min-width: 0; }

  /* ── SEMESTER BANNER ── */
  .semester-banner { font-size: .78rem; letter-spacing: .03em; background: var(--green-800); color: var(--green-200); text-align: center; padding: 5px 0; width: 100%; z-index: 51; flex-shrink: 0; }

  /* ── HEADER ── */
  .header { height: var(--header-h); background: var(--white); border-bottom: 1px solid var(--green-100); display: flex; align-items: center; justify-content: space-between; padding: 0 28px; position: sticky; top: 0; z-index: 50; gap: 14px; box-shadow: 0 1px 8px rgba(5,46,22,.05); flex-shrink: 0; }
  .header-page-title { font-family: 'DM Serif Display', serif; font-size: 1.3rem; color: var(--green-900); white-space: nowrap; }
  .hamburger { display: none; background: none; border: none; font-size: 1.4rem; cursor: pointer; color: var(--green-800); padding: 4px 6px; flex-shrink: 0; line-height: 1; }
  .header-search { display: flex; align-items: center; gap: 8px; background: var(--green-50); border: 1.5px solid var(--green-100); border-radius: 30px; padding: 7px 16px; transition: border-color .18s; width: 250px; }
  .header-search:focus-within { border-color: var(--green-400); }
  .header-search input { border: none; background: transparent; outline: none; font-family: inherit; font-size: .85rem; color: var(--gray-700); width: 100%; }
  .header-search input::placeholder { color: var(--gray-400); }
  .header-icon-btn { width: 38px; height: 38px; border-radius: 50%; background: var(--green-50); border: 1.5px solid var(--green-100); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 1rem; position: relative; transition: background .18s; flex-shrink: 0; }
  .header-icon-btn:hover { background: var(--green-100); }
  .notif-dot { position: absolute; top: 6px; right: 7px; width: 8px; height: 8px; border-radius: 50%; background: var(--green-500); border: 2px solid var(--white); }
  .semester-badge { background: var(--green-100); color: var(--green-800); font-size: .75rem; font-weight: 600; padding: 5px 14px; border-radius: 20px; white-space: nowrap; flex-shrink: 0; }
  .content { flex: 1; padding: 28px; }

  /* ── SECTION ANIMATION ── */
  .section { animation: fadeUp .35s ease both; }
  @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
  .page-header { margin-bottom: 24px; }
  .page-header h2 { font-family: 'DM Serif Display', serif; font-size: 1.6rem; color: var(--green-900); }
  .page-header p { color: var(--gray-500); font-size: .875rem; margin-top: 4px; }

  /* ── STATS ── */
  .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap: 16px; margin-bottom: 24px; }
  .stat-card { background: var(--white); border-radius: var(--radius); padding: 20px 22px; box-shadow: var(--shadow); border: 1px solid var(--green-100); display: flex; align-items: flex-start; gap: 14px; transition: box-shadow .2s, transform .2s; cursor: default; }
  .stat-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
  .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
  .stat-icon.green { background: var(--green-100); } .stat-icon.teal { background: #ccfbf1; } .stat-icon.lime { background: #ecfccb; } .stat-icon.emerald { background: #d1fae5; }
  .stat-label { font-size: .75rem; color: var(--gray-400); font-weight: 500; text-transform: uppercase; letter-spacing: .05em; }
  .stat-value { font-size: 1.65rem; font-weight: 700; color: var(--green-900); line-height: 1.1; margin: 4px 0 2px; }
  .stat-change { font-size: .75rem; color: var(--green-600); }

  /* ── CARD ── */
  .card { background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid var(--green-100); overflow: hidden; }
  .card-header { padding: 18px 22px 0; display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
  .card-title { font-family: 'DM Serif Display', serif; font-size: 1.05rem; color: var(--green-900); }
  .card-body { padding: 0 22px 22px; }
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
  .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px; }

  /* ── TABLE ── */
  .table-wrap { overflow-x: auto; }
  table { width: 100%; border-collapse: collapse; font-size: .855rem; }
  thead th { background: var(--green-50); color: var(--green-800); font-weight: 600; font-size: .75rem; letter-spacing: .05em; text-transform: uppercase; padding: 11px 16px; text-align: left; border-bottom: 1px solid var(--green-100); }
  tbody tr { border-bottom: 1px solid var(--gray-100); transition: background .15s; }
  tbody tr:last-child { border-bottom: none; }
  tbody tr:hover { background: var(--green-50); }
  tbody td { padding: 12px 16px; color: var(--gray-600); vertical-align: middle; }
  .td-name { color: var(--green-900); font-weight: 500; }

  /* ── BADGES ── */
  .badge { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: .72rem; font-weight: 600; }
  .badge-green { background: var(--green-100); color: var(--green-700); }
  .badge-gray { background: var(--gray-100); color: var(--gray-600); }
  .badge-amber { background: #fef3c7; color: #92400e; }
  .badge-red { background: #fee2e2; color: #991b1b; }
  .badge-blue { background: #dbeafe; color: #1e40af; }
  .badge-purple { background: #ede9fe; color: #6d28d9; }

  /* ── BUTTONS ── */
  .btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 18px; border-radius: var(--radius-sm); font-family: inherit; font-size: .855rem; font-weight: 500; cursor: pointer; border: none; transition: all .18s; text-decoration: none; }
  .btn-primary { background: var(--green-600); color: var(--white); }
  .btn-primary:hover { background: var(--green-700); }
  .btn-outline { background: transparent; color: var(--green-700); border: 1.5px solid var(--green-300); }
  .btn-outline:hover { background: var(--green-50); }
  .btn-sm { padding: 5px 12px; font-size: .78rem; }
  .btn-danger { background: #fee2e2; color: #991b1b; border: none; }
  .btn-danger:hover { background: #fecaca; }

  /* ── FORMS ── */
  .form-row { display: flex; gap: 14px; margin-bottom: 14px; flex-wrap: wrap; }
  .form-group { flex: 1; min-width: 160px; }
  .form-label { display: block; font-size: .78rem; font-weight: 600; color: var(--gray-600); margin-bottom: 5px; }
  .form-input, .form-select, .form-textarea { width: 100%; padding: 9px 13px; border: 1.5px solid var(--gray-200); border-radius: var(--radius-sm); font-family: inherit; font-size: .855rem; color: var(--gray-700); background: var(--white); outline: none; transition: border-color .18s; }
  .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--green-400); }
  .form-textarea { resize: vertical; min-height: 80px; }

  /* ── ALERTS ── */
  .alert { padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: .875rem; }
  .alert-success { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-200); }
  .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

  /* ── MODAL ── */
  .modal-overlay { position: fixed; inset: 0; background: rgba(5,46,22,.5); z-index: 1000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
  .modal-overlay.open { display: flex; animation: fadeIn .2s ease; }
  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
  .modal { background: var(--white); border-radius: 18px; width: 540px; max-width: 95vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 30px 80px rgba(0,0,0,.25); animation: slideUp .3s cubic-bezier(.34,1.56,.64,1); }
  @keyframes slideUp { from { opacity: 0; transform: translateY(30px) scale(.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
  .modal-header { padding: 22px 24px 0; display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px; }
  .modal-title { font-family: 'DM Serif Display', serif; font-size: 1.2rem; color: var(--green-900); }
  .modal-close { width: 32px; height: 32px; border: none; background: var(--gray-100); border-radius: 50%; cursor: pointer; font-size: 1rem; display: flex; align-items: center; justify-content: center; transition: background .18s; }
  .modal-close:hover { background: var(--gray-200); }
  .modal-body { padding: 0 24px 24px; }
  .modal-footer { padding: 0 24px 24px; display: flex; gap: 10px; justify-content: flex-end; }

  /* ── TABS ── */
  .tabs { display: flex; gap: 4px; background: var(--green-50); border-radius: 10px; padding: 4px; margin-bottom: 20px; width: fit-content; }
  .tab { padding: 7px 18px; border-radius: 8px; font-size: .85rem; cursor: pointer; transition: all .18s; color: var(--gray-500); font-weight: 500; border: none; background: none; }
  .tab.active { background: var(--white); color: var(--green-700); box-shadow: 0 1px 4px rgba(0,0,0,.08); }
  .tab-content { display: none; }
  .tab-content.active { display: block; }

  /* ── SCHEDULE ── */
  .schedule-list { display: flex; flex-direction: column; gap: 8px; }
  .schedule-item { display: flex; align-items: center; gap: 14px; padding: 10px 14px; border-radius: var(--radius-sm); border: 1px solid var(--green-100); background: var(--white); }
  .sched-time { font-size: .75rem; font-weight: 600; color: var(--green-700); min-width: 75px; }
  .sched-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--green-400); flex-shrink: 0; }

  /* ── ANNOUNCE ── */
  .announce-list { display: flex; flex-direction: column; gap: 12px; }
  .announce-item { border-left: 4px solid var(--green-400); padding: 12px 16px; background: var(--green-50); border-radius: 0 var(--radius-sm) var(--radius-sm) 0; }
  .announce-item h4 { color: var(--green-900); font-size: .9rem; margin-bottom: 3px; }
  .announce-item p { color: var(--gray-500); font-size: .8rem; }
  .announce-meta { font-size: .72rem; color: var(--gray-400); margin-top: 5px; }

  /* ── UPCOMING ── */
  .upcoming-list { display: flex; flex-direction: column; gap: 8px; }
  .upcoming-item { display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: var(--radius-sm); border: 1px solid var(--green-100); background: var(--white); }
  .upcoming-type { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .9rem; flex-shrink: 0; }
  .upcoming-info { flex: 1; }
  .upcoming-info h5 { font-size: .83rem; color: var(--green-900); font-weight: 600; margin-bottom: 2px; }
  .upcoming-info p { font-size: .72rem; color: var(--gray-400); }
  .upcoming-due { font-size: .72rem; font-weight: 600; padding: 3px 8px; border-radius: 20px; }
  .due-soon { background: #fef3c7; color: #92400e; }
  .due-today { background: #fee2e2; color: #991b1b; }
  .due-ok { background: var(--green-100); color: var(--green-700); }

  /* ── PROGRESS ── */
  .progress-list { display: flex; flex-direction: column; gap: 14px; }
  .progress-info { display: flex; justify-content: space-between; margin-bottom: 5px; }
  .progress-name { font-size: .855rem; color: var(--gray-700); }
  .progress-val { font-size: .855rem; font-weight: 600; color: var(--green-700); }
  .progress-bg { height: 8px; background: var(--green-100); border-radius: 99px; }
  .progress-fill { height: 100%; border-radius: 99px; }

  /* ── GRADE LETTER ── */
  .grade-letter { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 8px; font-weight: 700; font-size: .875rem; }
  .gl-A { background: var(--green-100); color: var(--green-700); }
  .gl-B { background: #dbeafe; color: #1d4ed8; }
  .gl-C { background: #fef3c7; color: #92400e; }
  .gl-D { background: #fee2e2; color: #991b1b; }

  /* ── GRADE INPUT ── */
  .grade-input { width: 65px; padding: 4px 6px; border: 1.5px solid var(--gray-200); border-radius: 6px; font-size: .8rem; text-align: center; font-family: inherit; outline: none; transition: border-color .18s; }
  .grade-input:focus { border-color: var(--green-400); }

  /* ── GRADE BAR ── */
  .grade-bar-wrap { display: flex; align-items: center; gap: 10px; }
  .grade-bar-bg { flex: 1; height: 6px; background: var(--green-100); border-radius: 99px; }
  .grade-bar-fill { height: 100%; border-radius: 99px; background: var(--green-500); }
  .grade-pct { font-size: .78rem; color: var(--gray-500); min-width: 34px; text-align: right; }

  /* ── SECURITY ── */
  .security-item { display: flex; align-items: center; gap: 14px; padding: 14px 0; border-bottom: 1px solid var(--green-50); }
  .security-item:last-child { border-bottom: none; }
  .sec-icon { font-size: 1.4rem; }
  .sec-info { flex: 1; }
  .sec-info h4 { font-size: .88rem; color: var(--green-900); font-weight: 600; margin-bottom: 2px; }
  .sec-info p { font-size: .78rem; color: var(--gray-400); }
  .sec-toggle { width: 42px; height: 24px; background: var(--green-500); border-radius: 99px; position: relative; cursor: pointer; transition: background .2s; }
  .sec-toggle::after { content:''; position:absolute; left:3px; top:3px; width:18px; height:18px; background:white; border-radius:50%; transition: left .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
  .sec-toggle.off { background: var(--gray-300); }
  .sec-toggle.off::after { left:21px; }

  /* ── PAGINATION ── */
  .pagination { display: flex; align-items: center; justify-content: space-between; margin-top: 16px; font-size: .8rem; color: var(--gray-400); }

  /* ── SECTION BLOCK ── */
  .section-block { background: #fafbfd; border-radius: var(--radius); padding: 16px; border: 1px solid var(--green-100); }

  /* ── PROFILE AVATAR ── */
  .profile-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--green-600); color: white; display: flex; align-items: center; justify-content: center; font-size: .82rem; font-weight: 700; border: 2px solid var(--green-200); cursor: pointer; flex-shrink: 0; }

  /* ── SEARCH DROPDOWN ── */
  .search-wrapper { position: relative; flex: 1; max-width: 300px; }
  .search-dropdown { position: absolute; top: calc(100% + 6px); left: 0; right: 0; background: white; border: 1px solid rgba(0,0,0,.1); border-radius: .5rem; box-shadow: 0 8px 24px rgba(0,0,0,.1); z-index: 500; display: none; max-height: 320px; overflow-y: auto; }
  .search-dropdown.open { display: block; }
  .search-dropdown .sd-section { font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--gray-400); padding: 8px 14px 4px; }
  .search-dropdown .sd-item { display: flex; align-items: center; gap: 10px; padding: 8px 14px; cursor: pointer; font-size: .84rem; color: var(--gray-700); text-decoration: none; transition: background .15s; }
  .search-dropdown .sd-item:hover { background: #f8f9fa; }
  .search-dropdown .sd-icon { width: 28px; height: 28px; border-radius: 8px; background: var(--green-100); display: flex; align-items: center; justify-content: center; font-size: .8rem; flex-shrink: 0; }

  /* ── NOTIFICATION DROPDOWN ── */
  .notif-dropdown { position: absolute; top: calc(100% + 8px); right: 0; width: 320px; background: white; border: 1px solid var(--green-100); border-radius: .75rem; box-shadow: 0 8px 30px rgba(5,46,22,.12); z-index: 500; display: none; }
  .notif-dropdown.open { display: block; }
  .notif-dropdown .nd-header { padding: 14px 16px 10px; font-weight: 600; font-size: .85rem; color: var(--green-900); border-bottom: 1px solid var(--green-50); display: flex; justify-content: space-between; align-items: center; }
  .notif-dropdown .nd-item { padding: 10px 16px; border-bottom: 1px solid var(--green-50); cursor: pointer; transition: background .15s; }
  .notif-dropdown .nd-item:hover { background: #f0f4ff; }
  .notif-dropdown .nd-item:last-child { border-bottom: none; }
  .notif-dropdown .nd-title { font-size: .83rem; color: var(--gray-700); font-weight: 500; }
  .notif-dropdown .nd-date { font-size: .72rem; color: var(--gray-400); margin-top: 2px; }
  .notif-dropdown .nd-empty { padding: 20px; text-align: center; color: var(--gray-400); font-size: .83rem; }

  /* ── PROFILE DROPDOWN ── */
  .profile-dropdown { position: absolute; top: calc(100% + 8px); right: 0; width: 220px; background: white; border: 1px solid var(--green-100); border-radius: .75rem; box-shadow: 0 8px 30px rgba(5,46,22,.12); z-index: 500; display: none; }
  .profile-dropdown.open { display: block; }
  .profile-dropdown .pd-header { padding: 14px 16px 10px; border-bottom: 1px solid var(--green-50); }
  .profile-dropdown .pd-name { font-size: .875rem; font-weight: 600; color: var(--green-900); }
  .profile-dropdown .pd-role { font-size: .7rem; text-transform: uppercase; letter-spacing: .06em; color: var(--green-600); margin-top: 2px; }
  .profile-dropdown .pd-item { display: flex; align-items: center; gap: 9px; padding: 9px 16px; font-size: .83rem; color: var(--gray-600); cursor: pointer; transition: background .15s; text-decoration: none; width: 100%; border: none; background: none; text-align: left; font-family: inherit; }
  .profile-dropdown .pd-item:hover { background: var(--green-50); color: var(--green-800); }
  .profile-dropdown .pd-divider { height: 1px; background: var(--green-50); margin: 4px 0; }

  /* ── SCROLLBAR ── */
  ::-webkit-scrollbar { width: 6px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: var(--green-200); border-radius: 3px; }

  /* ══ RESPONSIVE ══ */
  @media (max-width: 900px) {
    .sidebar { transform: translateX(-100%); }
    .sidebar.open { transform: translateX(0); }
    .sidebar-close { display: flex; }
    .sidebar-overlay.open { display: block; }
    .main { margin-left: 0; }
    .hamburger { display: flex; }
    .header { padding: 0 14px; gap: 8px; }
    .header-page-title { font-size: 1rem; }
    .semester-badge { display: none; }
    .content { padding: 16px; }
    .stats-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); }
    .grid-2, .grid-3 { grid-template-columns: 1fr; }
    .search-wrapper { max-width: 180px; }
  }
  @media (max-width: 560px) {
    .header-search { display: none; }
    .search-wrapper { display: none; }
    .stat-value { font-size: 1.3rem; }
    .page-header h2 { font-size: 1.2rem; }
    .content { padding: 12px; }
    .card-body { padding: 0 14px 14px; }
    .card-header { padding: 14px 14px 0; }
  }
  </style>
</head>
<body>

{{-- Sidebar overlay — sibling of sidebar, NOT inside main or body flex --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ══ SIDEBAR ══ --}}
<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-mark">
      <div class="logo-icon">🎓</div>
      <div>
        <div class="logo-text">Edu<span>Grade</span></div>
        <div class="logo-sub">Management System</div>
      </div>
    </div>
    <button class="sidebar-close" id="sidebarClose">✕</button>
  </div>

  <div class="sidebar-user">
    <div class="user-avatar">
      {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? '', 0, 1)) }}
    </div>
    <div class="user-info">
      <div class="name">{{ Auth::user()->display_name ?? Auth::user()->name }}</div>
      <div class="role">{{ Auth::user()->role }}</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Overview</div>
    <a href="{{ route('dashboard') }}"
       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <span class="nav-icon">🏠</span> Dashboard
    </a>
    <a href="{{ route('announcements.index') }}"
       class="nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
      <span class="nav-icon">📢</span> Announcements
    </a>

    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'teacher')
    <div class="nav-section-label">Academic</div>
    <a href="{{ route('students.index') }}"
       class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}">
      <span class="nav-icon">👥</span> Student Management
    </a>
    @if(Auth::user()->role === 'admin')
    <a href="{{ route('admin.sections.index') }}"
      class="nav-item {{ request()->routeIs('admin.sections.*') ? 'active' : '' }}">
      <span class="nav-icon">🗂️</span> Section Management
    </a>
    @endif
    <a href="{{ route('courses.index') }}"
       class="nav-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
      <span class="nav-icon">📖</span> Course Management
    </a>
    <a href="{{ route('syllabi.index') }}"
       class="nav-item {{ request()->routeIs('syllabi.*') ? 'active' : '' }}">
      <span class="nav-icon">📚</span> Syllabus
    </a>

    @auth
        @if(auth()->user()->role == 'teacher')
            <a href="{{ route('assignments.index') }}"
              class="nav-item {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
                <span class="nav-icon">📋</span>
                <span class="nav-label">Assignments</span>
            </a>
        @endif
    @endauth

    <a href="{{ route('gradebook.index') }}"
       class="nav-item {{ request()->routeIs('gradebook.*') ? 'active' : '' }}">
      <span class="nav-icon">📊</span> Gradebook
    </a>

    <div class="nav-section-label">Insights</div>
    <a href="{{ route('reports.index') }}"
       class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
      <span class="nav-icon">📈</span> Reports & Analytics
    </a>
    <a href="{{ route('attendance.index') }}"
       class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
      <span class="nav-icon">📅</span> Attendance
    </a>
    @endif

    @if(Auth::user()->role === 'student')
    <div class="nav-section-label">My Academic</div>
    <a href="{{ route('my.grades') }}"
       class="nav-item {{ request()->routeIs('my.grades') ? 'active' : '' }}">
      <span class="nav-icon">📊</span> My Grades
    </a>
    <a href="{{ route('my.attendance') }}"
       class="nav-item {{ request()->routeIs('my.attendance') ? 'active' : '' }}">
      <span class="nav-icon">📅</span> My Attendance
    </a>
    <a href="{{ route('my.syllabus') }}"
       class="nav-item {{ request()->routeIs('my.syllabus*') ? 'active' : '' }}">
      <span class="nav-icon">📚</span> My Syllabus
    </a>
    @endif

    @if(Auth::user()->role === 'admin')
    <div class="nav-section-label">System</div>
    <a href="{{ route('create-user') }}"
       class="nav-item {{ request()->routeIs('create-user') || request()->routeIs('store-user') ? 'active' : '' }}">
      <span class="nav-icon">👤</span> Create Account
    </a>
    <a href="{{ route('admin.settings.index') }}"
       class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
      <span class="nav-icon">⚙️</span> Settings
    </a>
    @endif
  </nav>

  <div class="sidebar-footer">
    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
      @csrf
      <button type="button" class="logout-btn"
              onclick="document.getElementById('logoutForm').submit()">
        <span>🚪</span> Sign Out
      </button>
    </form>
  </div>
</aside>

{{-- ══ MAIN ══ --}}
<div class="main">

  {{-- Semester banner — inside .main so it flows with content --}}
  <div class="semester-banner">
    {{ \App\Models\Setting::get('active_semester', '1st Semester') }}
    &bull; A.Y. {{ \App\Models\Setting::get('active_school_year', '2025-2026') }}
  </div>

  <header class="header">
    <button class="hamburger" id="hamburger" aria-label="Open menu">☰</button>
    <span class="header-page-title">@yield('title', 'Dashboard')</span>

    {{-- Search --}}
    <div class="search-wrapper">
      <div class="header-search">
        <span>🔍</span>
        <input type="text" id="globalSearch"
               placeholder="Search students, courses…" autocomplete="off">
      </div>
      <div class="search-dropdown" id="searchDropdown"></div>
    </div>

    {{-- Semester badge --}}
    <div class="semester-badge">
      {{ \App\Models\Setting::get('active_semester', '1st Semester') }}
      &bull; {{ \App\Models\Setting::get('active_school_year', '2025-2026') }}
    </div>

    {{-- Notification bell --}}
    <div style="position:relative;">
      <div class="header-icon-btn" id="notifBtn">
        🔔<div class="notif-dot" id="notifDot" style="display:none;"></div>
      </div>
      <div class="notif-dropdown" id="notifDropdown">
        <div class="nd-header">
          <span>Announcements</span>
          <span id="notifCount" style="font-size:.72rem;color:var(--green-600);"></span>
        </div>
        <div id="notifList"><div class="nd-empty">Loading…</div></div>
      </div>
    </div>

    {{-- Profile --}}
    <div style="position:relative;">
      <div class="profile-avatar" id="profileBtn">
        {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name ?? '', 0, 1)) }}
      </div>
      <div class="profile-dropdown" id="profileDropdown">
        <div class="pd-header">
          <div class="pd-name">{{ Auth::user()->display_name ?? Auth::user()->name }}</div>
          <div class="pd-role">{{ Auth::user()->role }}</div>
        </div>
        <a href="{{ route('profile.edit') }}" class="pd-item">✏️ Edit Profile</a>
        <div class="pd-divider"></div>
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
          @csrf
          <button type="button" class="pd-item"
                  onclick="document.getElementById('logoutForm').submit()">
            🚪 Sign Out
          </button>
        </form>
      </div>
    </div>
  </header>

  <div class="content">
    @yield('content')
  </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
});

function showToast(msg) {
    const t = document.createElement('div');
    t.style.cssText = 'position:fixed;bottom:28px;right:28px;background:var(--green-700);color:white;padding:12px 20px;border-radius:10px;font-size:.875rem;z-index:9000;box-shadow:0 6px 20px rgba(5,46,22,.3)';
    t.textContent = '✅ ' + msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}
function toggleSec(el) { el.classList.toggle('off'); }

// ── Sidebar ──
const sidebar      = document.querySelector('.sidebar');
const overlay      = document.getElementById('sidebarOverlay');
const hamburger    = document.getElementById('hamburger');
const sidebarClose = document.getElementById('sidebarClose');

function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('open'); }
function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }

hamburger?.addEventListener('click', openSidebar);
sidebarClose?.addEventListener('click', closeSidebar);
overlay?.addEventListener('click', closeSidebar);

document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => { if (window.innerWidth <= 900) closeSidebar(); });
});

// ── Notifications ──
const notifBtn      = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notifDropdown');
let notifLoaded = false;

notifBtn?.addEventListener('click', e => {
    e.stopPropagation();
    notifDropdown.classList.toggle('open');
    profileDropdown?.classList.remove('open');
    if (!notifLoaded) loadNotifications();
});

function loadNotifications() {
    notifLoaded = true;
    fetch('/api/notifications')
        .then(r => r.json())
        .then(data => {
            const list  = document.getElementById('notifList');
            const dot   = document.getElementById('notifDot');
            const count = document.getElementById('notifCount');
            if (!data.length) {
                list.innerHTML = '<div class="nd-empty">No announcements yet</div>';
                return;
            }
            dot.style.display = 'block';
            count.textContent = data.length + ' new';
            list.innerHTML = data.map(n => `
                <div class="nd-item" onclick="window.location='${n.url}'">
                    <div class="nd-title">${n.title}</div>
                    <div class="nd-date">${n.date}</div>
                </div>`).join('');
        })
        .catch(() => {
            document.getElementById('notifList').innerHTML = '<div class="nd-empty">Could not load</div>';
        });
}

// ── Profile dropdown ──
const profileBtn      = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');

profileBtn?.addEventListener('click', e => {
    e.stopPropagation();
    profileDropdown.classList.toggle('open');
    notifDropdown?.classList.remove('open');
});

document.addEventListener('click', () => {
    notifDropdown?.classList.remove('open');
    profileDropdown?.classList.remove('open');
    searchDropdown?.classList.remove('open');
});

// ── Search ──
const searchInput    = document.getElementById('globalSearch');
const searchDropdown = document.getElementById('searchDropdown');
let searchTimeout;

searchInput?.addEventListener('input', function () {
    clearTimeout(searchTimeout);
    const q = this.value.trim();
    if (q.length < 2) { searchDropdown.classList.remove('open'); return; }
    searchTimeout = setTimeout(() => runSearch(q), 280);
});
searchInput?.addEventListener('click', e => e.stopPropagation());

function runSearch(q) {
    fetch(`/api/search?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            let html = '';
            if (data.students?.length) {
                html += `<div class="sd-section">👥 Students</div>`;
                html += data.students.map(s => `
                    <a href="${s.url}" class="sd-item">
                        <div class="sd-icon">👤</div>
                        <div><div style="font-weight:500;">${s.name}</div>
                        <div style="font-size:.72rem;color:var(--gray-400);">${s.sub}</div></div>
                    </a>`).join('');
            }
            if (data.courses?.length) {
                html += `<div class="sd-section">📖 Courses</div>`;
                html += data.courses.map(c => `
                    <a href="${c.url}" class="sd-item">
                        <div class="sd-icon" style="background:#dbeafe;">📚</div>
                        <div><div style="font-weight:500;">${c.name}</div>
                        <div style="font-size:.72rem;color:var(--gray-400);">${c.sub}</div></div>
                    </a>`).join('');
            }
            if (data.announcements?.length) {
                html += `<div class="sd-section">📢 Announcements</div>`;
                html += data.announcements.map(a => `
                    <a href="${a.url}" class="sd-item">
                        <div class="sd-icon" style="background:#fef3c7;">📣</div>
                        <div><div style="font-weight:500;">${a.name}</div>
                        <div style="font-size:.72rem;color:var(--gray-400);">${a.sub}</div></div>
                    </a>`).join('');
            }
            if (!html) html = `<div class="nd-empty">No results for "${q}"</div>`;
            searchDropdown.innerHTML = html;
            searchDropdown.classList.add('open');
        });
}
</script>
@stack('scripts')
</body>
</html>
