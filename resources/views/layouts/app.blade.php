<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HF - Human Firewall')</title>
 <html lang="{{ app()->getLocale() }}" dir="ltr">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Icons (NOT Symbols) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        :root {
            --primary: #4ADE80;
            --secondary: #60A5FA;
            --bg: #0A0A0F;
            --text: #F1F5F9;
            --muted: #94A3B8;
        }

        /* RTL Support */
html[dir="rtl"] .material-icons {
    transform: scaleX(-1);
}
html[dir="rtl"] .material-icons.keep-direction {
    transform: none;
}
        * {
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #0A0A0F;
            color: #F1F5F9;
            min-height: 100vh;
            margin: 0;
        }

        h1, h2, h3, h4 {
            font-family: 'Space Grotesk', sans-serif;
        }

        /* Cyber Grid Background */
        .cyber-grid {
            background-image: 
                linear-gradient(rgba(74,222,128,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(74,222,128,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* Glass Effect */
        .glass {
            background: rgba(17,24,39,0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(74,222,128,0.1);
        }

        /* Card */
        .card {
            background: rgba(17,24,39,0.5);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(74,222,128,0.15);
            border-radius: 1.5rem;
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(74,222,128,0.05), inset 0 0 30px rgba(74,222,128,0.02);
        }

        .card:hover {
            border-color: rgba(74,222,128,0.4);
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(74,222,128,0.1);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 0;
            background: #4ADE80;
            transition: height 0.7s ease;
        }

        .card:hover::before {
            height: 100%;
        }

        /* Glow Effects */
        .glow-green {
            box-shadow: 0 0 40px rgba(74,222,128,0.15);
        }

        .glow-blue {
            box-shadow: 0 0 40px rgba(96,165,250,0.15);
        }

        .text-glow {
            text-shadow: 0 0 30px rgba(74,222,128,0.3);
        }

        /* Radial Border */
        .radial-border {
            position: relative;
        }

        .radial-border::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: inherit;
            padding: 1px;
            background: radial-gradient(circle at 30% 30%, rgba(74,222,128,0.4), transparent 60%),
                        radial-gradient(circle at 70% 70%, rgba(96,165,250,0.3), transparent 60%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* Nav Link */
        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(to right, #4ADE80, #60A5FA);
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .nav-link:hover::after {
            width: 60%;
        }

        /* Animations */
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(20px, -15px); }
            66% { transform: translate(-10px, 20px); }
        }

        .animate-float {
            animation: float 8s ease-in-out infinite;
        }

        @keyframes scan {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(100vh); }
        }

        .animate-scan {
            animation: scan 4s linear infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        .animate-blink {
            animation: blink 1s infinite;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0A0A0F;
        }

        ::-webkit-scrollbar-thumb {
            background: #4ADE80;
            border-radius: 99px;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Material Icons Fix */
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            -moz-osx-font-smoothing: grayscale;
            font-feature-settings: 'liga';
        }
    </style>
</head>
<body class="cyber-grid min-h-screen text-[#F1F5F9]">

    <!-- Scan Line -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-transparent via-[#4ADE80]/5 to-transparent animate-scan"></div>
    </div>

    <!-- Glowing Orbs -->
    <div class="fixed top-1/4 left-1/4 w-[500px] h-[500px] bg-[#4ADE80]/10 blur-[150px] rounded-full animate-float pointer-events-none z-0"></div>
    <div class="fixed bottom-1/4 right-1/4 w-[400px] h-[400px] bg-[#60A5FA]/10 blur-[150px] rounded-full animate-float pointer-events-none z-0" style="animation-delay:3s;"></div>

    <!-- Header -->
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[calc(100%-2rem)] max-w-6xl z-50 glass rounded-full px-6 py-3">
        <nav class="flex justify-between items-center gap-4">

            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 shrink-0 group">
                <img src="{{ asset('pic/logo1.png') }}" alt="HF" class="w-12 h-12 object-contain group-hover:scale-110 transition-all">
                <div class="flex flex-col">
                    <span class="text-2xl font-black bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent leading-none">HF</span>
                    <span class="text-[8px] text-[#94A3B8] uppercase tracking-[0.2em]">Human Firewall</span>
                </div>
            </a>

            <!-- Nav Links -->
            <div class="flex items-center space-x-1 overflow-x-auto scrollbar-hide">
                <a href="{{ route('dashboard') }}" class="nav-link text-[#F1F5F9]/70 hover:text-[#4ADE80] px-3 py-2 text-sm font-medium whitespace-nowrap flex items-center gap-1">
                    <span class="material-icons text-base">dashboard</span> Dashboard
                </a>
                <a href="{{ route('scenarios.index') }}" class="nav-link text-[#F1F5F9]/70 hover:text-[#4ADE80] px-3 py-2 text-sm font-medium whitespace-nowrap flex items-center gap-1">
                    <span class="material-icons text-base">menu_book</span> Scenarios
                </a>
                <a href="{{ route('ai.assistant') }}" class="nav-link text-[#F1F5F9]/70 hover:text-[#4ADE80] px-3 py-2 text-sm font-medium whitespace-nowrap flex items-center gap-1">
                    <span class="material-icons text-base">smart_toy</span> AI
                </a>
                <a href="{{ route('certificates.index') }}" class="nav-link text-[#F1F5F9]/70 hover:text-[#4ADE80] px-3 py-2 text-sm font-medium whitespace-nowrap flex items-center gap-1">
                    <span class="material-icons text-base">workspace_premium</span> Certificates
                </a>
                                              <a href="{{ route('messages.index') }}" class="nav-link text-[#F1F5F9]/70 hover:text-[#4ADE80] px-3 py-2 text-sm font-medium whitespace-nowrap flex items-center gap-1 relative">
                    <span class="material-icons text-base">mail</span> Messages
                    @auth
                    @php
                        $userUnread = DB::table('messages')
                            ->where('receiver_id', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    @if($userUnread > 0)
                    <span class="absolute -top-1 -right-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-red-500 text-white">{{ $userUnread }}</span>
                    @endif
                    @endauth
                </a>
                 <a href="{{ route('tasks.index') }}" class="nav-link text-[#F1F5F9]/70 hover:text-[#4ADE80] px-3 py-2 text-sm font-medium whitespace-nowrap flex items-center gap-1">
    <span class="material-icons text-base">assignment</span> Tasks
</a>             
            </div>

         

            <!-- User Actions -->
            <div class="flex items-center space-x-2 shrink-0">
                @auth
                <a href="{{ route('profile.index') }}" class="w-9 h-9 bg-gradient-to-br from-[#4ADE80] to-[#60A5FA] rounded-full flex items-center justify-center text-[#0A0A0F] font-bold glow-green hover:scale-110 transition-all">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg hover:bg-red-500/10 text-[#F1F5F9]/40 hover:text-red-500 transition-all">
                        <span class="material-icons">logout</span>
                    </button>
                </form>
                @else
                <a href="/login" class="px-4 py-2 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green">Login</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="pt-24 min-h-screen relative z-10">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>