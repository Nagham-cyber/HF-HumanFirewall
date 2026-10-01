<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - HF</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        :root { --primary:#4ADE80; --secondary:#60A5FA; --bg:#0A0A0F; }
        * { transition: all 0.3s ease; font-family: 'Inter', sans-serif; }
        body { background: #0A0A0F; color: #F1F5F9; min-height: 100vh; margin: 0; }
        h1,h2,h3,h4 { font-family: 'Space Grotesk', sans-serif; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(74,222,128,0.1); }
        .cyber-grid { background-image: linear-gradient(rgba(74,222,128,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(74,222,128,0.03) 1px, transparent 1px); background-size: 50px 50px; }
        .glow-green { box-shadow: 0 0 40px rgba(74,222,128,0.15); }
        .card { background: rgba(17,24,39,0.5); backdrop-filter: blur(30px); border: 1px solid rgba(74,222,128,0.15); border-radius: 1rem; transition: all 0.4s; }
        .card:hover { border-color: rgba(74,222,128,0.4); transform: translateY(-2px); box-shadow: 0 20px 50px rgba(74,222,128,0.1); }

        /* Sidebar */
        .sidebar { position: fixed; left: 0; top: 0; bottom: 0; width: 260px; z-index: 40; background: rgba(10,10,15,0.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(74,222,128,0.15); overflow-y: auto; }
        .sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 500; color: #94A3B8; transition: all 0.2s; }
        .sidebar-link:hover { background: rgba(74,222,128,0.1); color: #4ADE80; }
        .sidebar-link.active { background: linear-gradient(90deg, rgba(74,222,128,0.15), rgba(96,165,250,0.15)); color: #4ADE80; border-left: 3px solid #4ADE80; }

        /* Main */
        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar { position: sticky; top: 0; z-index: 30; background: rgba(10,10,15,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(74,222,128,0.15); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0A0A0F; }
        ::-webkit-scrollbar-thumb { background: #4ADE80; border-radius: 99px; }

        .material-icons { font-family: 'Material Icons'; font-weight: normal; font-style: normal; display: inline-block; line-height: 1; text-transform: none; letter-spacing: normal; word-wrap: normal; white-space: nowrap; direction: ltr; -webkit-font-smoothing: antialiased; font-feature-settings: 'liga'; }
    </style>
</head>
<body class="cyber-grid">

    @php
        $currentRoute = request()->route()->getName() ?? '';
    @endphp

    <!-- Sidebar -->
    <aside class="sidebar">

        <!-- Logo -->
        <div class="p-6 border-b border-[#4ADE80]/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full glass flex items-center justify-center glow-green">
                    <span class="material-icons text-[#4ADE80]">admin_panel_settings</span>
                </div>
                <div>
                    <h1 class="text-lg font-black bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">HF Admin</h1>
                    <p class="text-[10px] text-[#94A3B8] uppercase tracking-widest">Control Panel</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-1">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}">
                <span class="material-icons text-lg">dashboard</span> Dashboard
            </a>

            <!-- ==================== CONTENT ==================== -->
            <p class="px-4 pt-4 pb-2 text-[10px] uppercase tracking-widest text-[#94A3B8]/60 font-bold">Content</p>

            <!-- Scenarios -->
            <a href="{{ route('admin.scenarios.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.scenarios') ? 'active' : '' }}">
                <span class="material-icons text-lg">menu_book</span> Scenarios
                <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#4ADE80]/10 text-[#4ADE80]">{{ DB::table('scenarios')->count() }}</span>
            </a>

            <!-- Daily Challenges -->
            <a href="{{ route('admin.challenges.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.challenges') ? 'active' : '' }}">
                <span class="material-icons text-lg">bolt</span> Daily Challenges
                <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400">{{ DB::table('challenges')->count() }}</span>
            </a>

            <!-- Cyber Tasks -->
            <a href="{{ route('admin.tasks.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.tasks') && !str_contains($currentRoute, 'submission') ? 'active' : '' }}">
                <span class="material-icons text-lg">assignment</span> Cyber Tasks
                <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#4ADE80]/10 text-[#4ADE80]">{{ DB::table('cyber_tasks')->count() }}</span>
            </a>

            <!-- Team Submissions -->
            <a href="{{ route('admin.tasks.submissions') }}" class="sidebar-link {{ str_contains($currentRoute, 'submission') ? 'active' : '' }}">
                <span class="material-icons text-lg">fact_check</span> Team Submissions
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.categories') ? 'active' : '' }}">
                <span class="material-icons text-lg">category</span> Categories
                <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#60A5FA]/10 text-[#60A5FA]">{{ DB::table('categories')->count() }}</span>
            </a>

            <!-- ==================== USERS ==================== -->
            <p class="px-4 pt-4 pb-2 text-[10px] uppercase tracking-widest text-[#94A3B8]/60 font-bold">Users</p>

            <!-- Manage Users -->
            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.users') ? 'active' : '' }}">
                <span class="material-icons text-lg">people</span> Manage Users
            </a>

            <!-- Messages -->
            <a href="{{ route('admin.messages.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.messages') ? 'active' : '' }}">
                <span class="material-icons text-lg">chat</span> Messages
                @php
                    $unreadMessages = DB::table('messages')->where('sender_type', 'user')->where('is_read', false)->count();
                @endphp
                @if($unreadMessages > 0)
                <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/20 text-red-400">{{ $unreadMessages }}</span>
                @endif
            </a>

            <!-- ==================== SYSTEM ==================== -->
            <p class="px-4 pt-4 pb-2 text-[10px] uppercase tracking-widest text-[#94A3B8]/60 font-bold">System</p>

            <!-- Audit Logs -->
            <a href="{{ route('admin.audit-logs.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.audit-logs') ? 'active' : '' }}">
                <span class="material-icons text-lg">list_alt</span> Audit Logs
            </a>
                                  <!-- Security Dashboard -->
            <a href="{{ route('admin.security.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.security') ? 'active' : '' }}">
                <span class="material-icons text-lg">security</span> Security
            </a>

            <!-- Backups -->
            <a href="{{ route('admin.backups.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.backups') ? 'active' : '' }}">
                <span class="material-icons text-lg">backup</span> Backups
            </a>

            <!-- Reports -->
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.reports') ? 'active' : '' }}">
                <span class="material-icons text-lg">assessment</span> Reports
            </a>
                                    <!-- Analytics -->
            <a href="{{ route('admin.analytics') }}" class="sidebar-link {{ $currentRoute === 'admin.analytics' ? 'active' : '' }}">
                <span class="material-icons text-lg">insights</span> Analytics
            </a>
            <!-- Settings -->
            <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ str_starts_with($currentRoute, 'admin.settings') ? 'active' : '' }}">
                <span class="material-icons text-lg">settings</span> Settings
            </a>

        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-[#4ADE80]/10">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-red-400 hover:bg-red-500/10 hover:text-red-400">
                    <span class="material-icons text-lg">logout</span> Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Topbar -->
        <header class="topbar px-6 py-4">
            <div class="flex items-center justify-between">

                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-black">@yield('title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-4">

                    <a href="/" target="_blank" class="text-xs text-[#94A3B8] hover:text-[#4ADE80] flex items-center gap-1">
                        <span class="material-icons text-sm">open_in_new</span> View Site
                    </a>

                    <div class="flex items-center gap-3 pl-4 border-l border-[#4ADE80]/10">
                        <div class="text-right">
                            <p class="text-xs font-bold">{{ auth('admin')->user()->name }}</p>
                            <p class="text-[10px] text-[#94A3B8]">{{ auth('admin')->user()->email }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] flex items-center justify-center text-[#0A0A0F] font-bold glow-green">
                            {{ strtoupper(substr(auth('admin')->user()->name, 0, 1)) }}
                        </div>
                    </div>

                </div>

            </div>
        </header>

        <!-- Page Content -->
        <main class="p-6">

            @if(session('success'))
            <div class="bg-[#4ADE80]/10 border border-[#4ADE80]/30 rounded-xl p-4 mb-6 text-[#4ADE80] text-sm flex items-center gap-2">
                <span class="material-icons">check_circle</span>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 text-red-400 text-sm flex items-center gap-2">
                <span class="material-icons">error</span>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')

        </main>

    </div>

    @yield('scripts')

</body>
</html>