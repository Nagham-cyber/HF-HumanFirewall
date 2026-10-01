<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HF - Human Firewall</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        :root { --primary:#4ADE80; --secondary:#60A5FA; --bg:#0A0A0F; --text:#F1F5F9; --muted:#94A3B8; }
        * { transition: all 0.3s ease; font-family: 'Inter', sans-serif; }
        body { background: #0A0A0F; color: #F1F5F9; min-height: 100vh; }
        h1,h2,h3,h4 { font-family: 'Space Grotesk', sans-serif; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(74,222,128,0.1); }
        .cyber-grid { background-image: linear-gradient(rgba(74,222,128,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(74,222,128,0.03) 1px, transparent 1px); background-size: 50px 50px; }
        .glow-green { box-shadow: 0 0 40px rgba(74,222,128,0.15); }
        .text-glow { text-shadow: 0 0 30px rgba(74,222,128,0.3); }
        .radial-border { position: relative; }
        .radial-border::before {
            content: ''; position: absolute; inset: -1px; border-radius: inherit; padding: 1px;
            background: radial-gradient(circle at 30% 30%, rgba(74,222,128,0.4), transparent 60%),
                        radial-gradient(circle at 70% 70%, rgba(96,165,250,0.3), transparent 60%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
        }
        .card { background: rgba(17,24,39,0.5); backdrop-filter: blur(30px); border: 1px solid rgba(74,222,128,0.15); border-radius: 1.5rem; transition: all 0.5s; position: relative; overflow: hidden; }
        .card:hover { border-color: rgba(74,222,128,0.4); transform: translateY(-4px); box-shadow: 0 20px 50px rgba(74,222,128,0.1); }
        @keyframes float { 0%,100%{transform:translate(0,0)} 33%{transform:translate(15px,-10px)} 66%{transform:translate(-8px,15px)} }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes scan { 0%{transform:translateY(-100%)} 100%{transform:translateY(100vh)} }
        .animate-scan { animation: scan 4s linear infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
        .animate-blink { animation: blink 1s infinite; }
        @keyframes marquee { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
        .animate-marquee { animation: marquee 30s linear infinite; }
        .animate-marquee:hover { animation-play-state: paused; }
        @keyframes blink-soft { 0%,100%{opacity:1; text-shadow:0 0 20px rgba(74,222,128,0.5)} 50%{opacity:0.3; text-shadow:0 0 40px rgba(74,222,128,0.8)} }
        .animate-blink-soft { animation: blink-soft 3s ease-in-out infinite; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0A0A0F; }
        ::-webkit-scrollbar-thumb { background: #4ADE80; border-radius: 99px; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="cyber-grid min-h-screen text-[#F1F5F9]">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-transparent via-[#4ADE80]/5 to-transparent animate-scan"></div>
    </div>

    <div class="fixed top-1/4 left-1/4 w-[500px] h-[500px] bg-[#4ADE80]/10 blur-[150px] rounded-full animate-float pointer-events-none z-0"></div>
    <div class="fixed bottom-1/4 right-1/4 w-[400px] h-[400px] bg-[#60A5FA]/10 blur-[150px] rounded-full animate-float pointer-events-none z-0" style="animation-delay:3s;"></div>

    <!-- HERO -->
    <section class="relative min-h-screen flex flex-col items-center justify-center px-6 py-16">
        <div class="w-24 h-24 rounded-full glass radial-border flex items-center justify-center glow-green mb-4 animate-float z-10">
            <img src="{{ asset('pic/logo1.png') }}" alt="HF" class="w-16 h-16 object-contain">
        </div>

        <span class="text-3xl font-black bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent text-glow z-10">HF</span>
        <span class="text-[10px] text-[#94A3B8] uppercase tracking-[0.3em] mt-1 z-10">Human Firewall</span>

        <div class="flex items-center gap-2 glass rounded-full px-4 py-1.5 mt-4 z-10">
            <span class="w-2 h-2 bg-[#4ADE80] rounded-full animate-pulse"></span>
            <span class="text-[#4ADE80] text-[10px] uppercase tracking-[0.2em]">Active</span>
        </div>

        <h1 class="text-3xl md:text-5xl font-black text-center mt-4 z-10 leading-tight">
            <span class="block text-[#F1F5F9] animate-blink-soft">DEFEND.</span>
            <span class="block bg-gradient-to-r from-[#4ADE80] via-[#60A5FA] to-[#4ADE80] bg-clip-text text-transparent text-glow animate-blink-soft" style="animation-delay: 0.5s;">DETECT.</span>
            <span class="block text-[#F1F5F9]/30 animate-blink-soft" style="animation-delay: 1s;">DOMINATE.</span>
        </h1>

        <div class="text-[#4ADE80]/60 font-mono text-[10px] mt-3 z-10">
            [ <span class="animate-blink">hf@security:~$ initializing</span> ]
        </div>

        <div class="flex flex-col sm:flex-row gap-3 mt-4 z-10">
            <div class="relative" id="startDropdown">
                <button onclick="toggleDropdown()" class="px-6 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green hover:scale-105 transition-all text-sm">
                    Start Training
                </button>
                <div id="dropdownMenu" class="hidden absolute mt-2 left-1/2 -translate-x-1/2 glass rounded-2xl p-2 w-44 z-50">
                    <a href="/login" class="flex items-center gap-2 px-4 py-3 rounded-xl hover:bg-[#4ADE80]/10 text-sm">
                        <span class="material-icons text-base text-[#4ADE80]">login</span> Login
                    </a>
                    <a href="/register" class="flex items-center gap-2 px-4 py-3 rounded-xl hover:bg-[#4ADE80]/10 text-sm">
                        <span class="material-icons text-base text-[#60A5FA]">person_add</span> Register
                    </a>
                </div>
            </div>
            <a href="#learn-more" class="px-6 py-3 glass rounded-full font-bold hover:text-[#4ADE80] text-center text-sm">Learn More</a>
        </div>
    </section>

    <!-- MARQUEE -->
    <section class="py-8 border-y border-[#4ADE80]/10 bg-[#050508] overflow-hidden mt-20">
        <div class="flex whitespace-nowrap animate-marquee">
            @for($i = 0; $i < 2; $i++)
            <div class="flex items-center gap-6 shrink-0 px-4">
                <span class="text-xl font-black bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Phishing Defense</span>
                <span class="material-icons text-[#4ADE80]">shield</span>
                <span class="text-xl font-black text-white/30">140 Scenarios</span>
                <span class="w-3 h-3 rounded-full bg-[#4ADE80]"></span>
                <span class="material-icons text-[#4ADE80]">emergency</span>
                <span class="text-xl font-black bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">AI Assistant</span>
                <span class="material-icons text-[#4ADE80]">smart_toy</span>
            </div>
            @endfor
        </div>
    </section>

    <!-- ==================== LIVE STATS ==================== -->
    <section class="py-16 px-6 max-w-6xl mx-auto">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black mb-2">Platform <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Statistics</span></h2>
            <p class="text-[#94A3B8] text-sm">Real-time numbers from our training platform</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Scenarios -->
            <div class="card p-6 text-center group" data-count="{{ $stats['scenarios'] }}">
                <span class="material-icons text-3xl text-[#4ADE80] mb-3">menu_book</span>
                <p class="text-3xl font-black text-[#4ADE80] counter">{{ $stats['scenarios'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Scenarios</p>
            </div>

            <!-- Cyber Tasks -->
            <div class="card p-6 text-center group">
                <span class="material-icons text-3xl text-orange-400 mb-3">assignment</span>
                <p class="text-3xl font-black text-orange-400 counter">{{ $stats['tasks'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Cyber Tasks</p>
            </div>

            <!-- Users -->
            <div class="card p-6 text-center group">
                <span class="material-icons text-3xl text-[#60A5FA] mb-3">people</span>
                <p class="text-3xl font-black text-[#60A5FA] counter">{{ $stats['users'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Users</p>
            </div>

            <!-- Challenges -->
            <div class="card p-6 text-center group">
                <span class="material-icons text-3xl text-purple-400 mb-3">bolt</span>
                <p class="text-3xl font-black text-purple-400 counter">{{ $stats['challenges'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Challenges</p>
            </div>

            <!-- Categories -->
            <div class="card p-6 text-center group">
                <span class="material-icons text-3xl text-[#4ADE80] mb-3">category</span>
                <p class="text-3xl font-black text-[#4ADE80] counter">{{ $stats['categories'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Categories</p>
            </div>

            <!-- Badges -->
            <div class="card p-6 text-center group">
                <span class="material-icons text-3xl text-yellow-400 mb-3">military_tech</span>
                <p class="text-3xl font-black text-yellow-400 counter">{{ $stats['badges'] }}</p>
                <p class="text-[10px] text-[#94A3B8] uppercase mt-1">Badges</p>
            </div>
        </div>

        <!-- Top 3 Champions -->
        @if($topChampions->count() >= 3)
        <div class="mt-16">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black mb-2">Top <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">Champions</span></h2>
                <p class="text-[#94A3B8] text-sm">Best performers on the platform</p>
            </div>

            <div class="grid grid-cols-3 gap-4 max-w-3xl mx-auto items-end">
                <!-- 2nd Place -->
                <div class="card p-6 text-center" style="margin-bottom: 30px;">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-gray-300 to-gray-500 rounded-full flex items-center justify-center text-[#0A0A0F] font-black text-2xl mb-3">
                        {{ strtoupper(substr($topChampions[1]->name, 0, 1)) }}
                    </div>
                    <span class="material-icons text-3xl text-gray-300 mb-2">military_tech</span>
                    <p class="text-2xl font-black text-gray-300">#2</p>
                    <p class="font-bold text-sm mt-2 truncate">{{ $topChampions[1]->name }}</p>
                    <p class="text-xs text-[#94A3B8] mt-1">{{ $topChampions[1]->xp_points }} XP</p>
                </div>

                <!-- 1st Place -->
                <div class="card p-6 text-center glow-green">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-yellow-400 to-amber-600 rounded-full flex items-center justify-center text-[#0A0A0F] font-black text-3xl mb-3">
                        {{ strtoupper(substr($topChampions[0]->name, 0, 1)) }}
                    </div>
                    <span class="material-icons text-4xl text-yellow-400 mb-2">emoji_events</span>
                    <p class="text-3xl font-black text-yellow-400">#1</p>
                    <p class="font-bold text-sm mt-2 truncate">{{ $topChampions[0]->name }}</p>
                    <p class="text-xs text-[#4ADE80] mt-1 font-bold">{{ $topChampions[0]->xp_points }} XP</p>
                </div>

                <!-- 3rd Place -->
                <div class="card p-6 text-center" style="margin-bottom: 30px;">
                    <div class="w-16 h-16 mx-auto bg-gradient-to-br from-amber-600 to-amber-800 rounded-full flex items-center justify-center text-[#0A0A0F] font-black text-2xl mb-3">
                        {{ strtoupper(substr($topChampions[2]->name, 0, 1)) }}
                    </div>
                    <span class="material-icons text-3xl text-amber-600 mb-2">military_tech</span>
                    <p class="text-2xl font-black text-amber-600">#3</p>
                    <p class="font-bold text-sm mt-2 truncate">{{ $topChampions[2]->name }}</p>
                    <p class="text-xs text-[#94A3B8] mt-1">{{ $topChampions[2]->xp_points }} XP</p>
                </div>
            </div>
        </div>
        @endif
    </section>

    <!-- TERMINAL -->
    <section class="py-10 px-6 max-w-3xl mx-auto">
        <div class="card p-8">
            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-[#4ADE80]/10">
                <div class="w-3 h-3 rounded-full bg-red-500/60"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-500/60"></div>
                <div class="w-3 h-3 rounded-full bg-[#4ADE80]/60"></div>
                <span class="text-[10px] text-[#F1F5F9]/30 uppercase ml-3">hf@security:~$</span>
            </div>
            <div class="font-mono text-xs space-y-2">
                <div class="text-[#4ADE80]/60">$ <span class="text-[#4ADE80]">initialize</span> --module training</div>
                <div class="text-[#F1F5F9]/40">[ OK ] Loading 140 security scenarios...</div>
                <div class="text-[#F1F5F9]/40">[ OK ] Loading 300 professional tasks...</div>
                <div class="text-[#F1F5F9]/40">[ OK ] AI assistant: <span class="text-[#4ADE80]">ONLINE</span></div>
                <div class="text-yellow-400/80">[ !! ] New threat detected: Phishing campaign</div>
                <div class="text-[#4ADE80]">[ OK ] Playbook deployed: TRAIN &amp; DEFEND</div>
                <div class="flex items-center gap-1 mt-2"><span class="text-[#4ADE80]/60">$</span><span class="text-[#4ADE80] animate-blink">|</span></div>
            </div>
        </div>
    </section>

    <!-- LEARN MORE -->
    <section id="learn-more" class="py-16 px-6 max-w-5xl mx-auto">

        <h2 class="text-2xl font-black text-center mb-10">About Human Firewall</h2>

        <!-- NEW DESCRIPTION -->
        <div class="card p-8 radial-border glow-green mb-14 max-w-2xl mx-auto">
            <p class="text-[#94A3B8] leading-relaxed text-sm text-center mb-4">
                <span class="text-[#4ADE80] font-bold">HF (Human Firewall)</span> is a next-generation cybersecurity training platform that transforms your workforce from the <span class="text-red-400">weakest link</span> into an <span class="text-[#4ADE80] font-bold">impenetrable line of defense</span>.
            </p>
            <p class="text-[#94A3B8] leading-relaxed text-sm text-center mb-4">
                Through <span class="text-[#60A5FA] font-bold">140+ real-world attack simulations</span>, <span class="text-[#60A5FA] font-bold">300 professional security tasks</span>, and an <span class="text-[#60A5FA] font-bold">AI-powered assistant</span>, we build a security-first culture in your organization.
            </p>
            <p class="text-[#F1F5F9] leading-relaxed text-xs text-center italic">
                Train. Detect. Defend. — Because cybersecurity starts with people.
            </p>
        </div>

        <!-- Feature Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-14">

            <!-- Dashboard -->
            <div class="card p-8 group">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-3xl text-[#4ADE80]">dashboard</span>
                    <h3 class="font-black text-lg">Dashboard</h3>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Contains <span class="text-[#4ADE80]">Top Champions</span> leaderboard,
                    <span class="text-[#60A5FA]">Daily Challenge</span> for +50 XP every day,
                    and access to <span class="text-[#4ADE80]">140+ Scenarios</span>.
                </p>
            </div>

            <!-- Scenarios -->
            <div class="card p-8 group">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-3xl text-[#4ADE80]">menu_book</span>
                    <h3 class="font-black text-lg">140+ Scenarios</h3>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Divided into <span class="text-[#4ADE80]">3 categories</span>: General Users, IT Professionals,
                    and Security Experts. Levels: <span class="text-[#60A5FA]">Easy, Medium, Hard, Expert</span>.
                </p>
            </div>

            <!-- AI Assistant -->
            <div class="card p-8 group">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-3xl text-[#60A5FA]">smart_toy</span>
                    <h3 class="font-black text-lg">CyberGuard AI</h3>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    24/7 AI-powered security assistant for any cybersecurity topic.
                </p>
            </div>

            <!-- Mistake Vault -->
            <div class="card p-8 group">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-3xl text-red-400">bug_report</span>
                    <h3 class="font-black text-lg">Mistake Vault</h3>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Tracks your errors with <span class="text-[#4ADE80]">detailed explanations</span> to ensure
                    you fully understand each scenario.
                </p>
            </div>

            <!-- Badges -->
            <div class="card p-8 group">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-3xl text-[#4ADE80]">military_tech</span>
                    <h3 class="font-black text-lg">Badges</h3>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Earn badges for <span class="text-[#4ADE80]">Easy, Medium, Hard, Expert</span> levels.
                    Each category has 4 badges.
                </p>
            </div>

            <!-- Progress -->
            <div class="card p-8 group">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-3xl text-[#60A5FA]">trending_up</span>
                    <h3 class="font-black text-lg">Progress</h3>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Monitor security awareness by category. Track XP, scenarios, and scores.
                </p>
            </div>

            <!-- Alerts -->
            <div class="card p-8 group">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-3xl text-[#4ADE80]">notifications</span>
                    <h3 class="font-black text-lg">Alerts</h3>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Latest cybersecurity news, threats, and updates.
                </p>
            </div>

            <!-- Certificates -->
            <div class="card p-8 group">
                <div class="flex items-center gap-3 mb-3">
                    <span class="material-icons text-3xl text-[#4ADE80]">workspace_premium</span>
                    <h3 class="font-black text-lg">My Certificates</h3>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed">
                    Earn verified certificates when you complete <span class="text-[#4ADE80]">all scenarios in a category</span>.
                    Download as PDF/JPG and share on LinkedIn.
                </p>
            </div>

            <!-- Cyber Tasks (Full Width) -->
            <div class="card p-8 group md:col-span-2">
                <div class="flex items-center gap-3 mb-3 justify-center">
                    <span class="material-icons text-3xl text-[#4ADE80]">assignment</span>
                    <h3 class="font-black text-lg">Cyber Tasks</h3>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-[#4ADE80]/10 text-[#4ADE80]">NEW</span>
                </div>
                <p class="text-xs text-[#94A3B8] leading-relaxed text-center mb-4">
                    <span class="text-[#4ADE80]">300 professional security tasks</span> across 6 specializations:
                    Network Defense, DFIR, Pentest, DevSecOps, GRC, and Automation. Each task includes detailed
                    <span class="text-[#60A5FA]">objectives, step-by-step instructions, tools, resources, and deliverables</span>.
                    Complete tasks to earn <span class="text-[#4ADE80]">+100 XP</span> each.
                </p>
                <div class="flex flex-wrap justify-center gap-2">
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-[#4ADE80]/10 text-[#4ADE80]">Network</span>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400">DFIR</span>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-purple-500/10 text-purple-400">Pentest</span>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-[#60A5FA]/10 text-[#60A5FA]">DevSecOps</span>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-yellow-500/10 text-yellow-400">GRC</span>
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-orange-500/10 text-orange-400">Automation</span>
                </div>
            </div>

        </div>

        <h2 class="text-2xl font-black text-center mb-6">Threat Statistics</h2>
        <div class="space-y-4 max-w-2xl mx-auto mb-14">
            <div class="flex justify-between text-xs uppercase tracking-widest text-[#94A3B8]"><span>Phishing</span><span class="text-[#4ADE80]">+87%</span></div>
            <div class="h-[2px] bg-[#4ADE80]/10 rounded-full"><div class="h-full w-[87%] bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] rounded-full"></div></div>
            <div class="flex justify-between text-xs uppercase tracking-widest text-[#94A3B8]"><span>Passwords</span><span class="text-[#4ADE80]">+64%</span></div>
            <div class="h-[2px] bg-[#4ADE80]/10 rounded-full"><div class="h-full w-[64%] bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] rounded-full"></div></div>
            <div class="flex justify-between text-xs uppercase tracking-widest text-[#94A3B8]"><span>Social Engineering</span><span class="text-[#4ADE80]">+73%</span></div>
            <div class="h-[2px] bg-[#4ADE80]/10 rounded-full"><div class="h-full w-[73%] bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] rounded-full"></div></div>
        </div>

        <div class="card p-12 text-center radial-border glow-green mb-14">
            <h2 class="text-3xl md:text-4xl font-black">
                FORTIFY YOUR<br />
                <span class="bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">HUMAN FIREWALL.</span>
            </h2>
            <a href="/register" class="inline-flex items-center gap-2 mt-6 px-8 py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-bold glow-green hover:scale-105 transition-all text-sm">
                <span class="material-icons">shield</span> Start Now <span class="material-icons">arrow_forward</span>
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-[#4ADE80]/20 py-10 px-6">
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-6">
            <div class="w-24 h-24 rounded-full radial-border glow-green overflow-hidden shrink-0">
                <img src="{{ asset('pic/my-photo.png') }}" alt="Nagham Almassri" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 text-center md:text-left">
                <p class="text-lg font-black bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] bg-clip-text text-transparent">The Founder</p>
                <h3 class="text-sm font-bold mt-1">Nagham Nabil Almassri</h3>
                <p class="text-xs text-[#94A3B8] mt-2">
                    Cybersecurity specialist with deep expertise in Digital Forensics, Incident Response, Malware Analysis,
                    SOC operations, and security hardening.
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-2">
                <a href="http://www.linkedin.com/in/nagham-almassri-4b34b6395" target="_blank" class="w-9 h-9 rounded-full glass flex items-center justify-center hover:bg-[#0A66C2] transition-all">
                    <svg class="w-4 h-4 fill-current text-[#0A66C2]" viewBox="0 0 24 24"><path d="M19 3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14m-.5 15.5v-5.3a3.26 3.26 0 00-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 011.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 001.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 00-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                </a>
                <a href="mailto:naghamalmassri943@gmail.com" class="w-9 h-9 rounded-full glass flex items-center justify-center hover:bg-red-500 transition-all">
                    <svg class="w-4 h-4 fill-current text-red-400" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                </a>
                <a href="https://github.com/Nagham-cyber" target="_blank" class="w-9 h-9 rounded-full glass flex items-center justify-center hover:bg-gray-700 transition-all">
                    <svg class="w-4 h-4 fill-current text-white" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.58 2 12.25c0 4.53 2.87 8.37 6.84 9.73.5.09.68-.22.68-.49 0-.24-.01-.87-.01-1.7-2.78.62-3.37-1.37-3.37-1.37-.45-1.18-1.11-1.5-1.11-1.5-.91-.64.07-.63.07-.63 1 .07 1.53 1.06 1.53 1.06.89 1.57 2.34 1.12 2.91.86.09-.66.35-1.11.63-1.37-2.22-.26-4.56-1.14-4.56-5.07 0-1.12.39-2.03 1.03-2.75-.1-.26-.45-1.3.1-2.7 0 0 .84-.28 2.75 1.05a9.36 9.36 0 015 0c1.91-1.33 2.75-1.05 2.75-1.05.55 1.4.2 2.44.1 2.7.64.72 1.03 1.63 1.03 2.75 0 3.94-2.34 4.8-4.57 5.06.36.32.68.94.68 1.9 0 1.37-.01 2.47-.01 2.81 0 .27.18.59.69.49A10.25 10.25 0 0022 12.25C22 6.58 17.52 2 12 2z"/></svg>
                </a>
                <a href="https://www.instagram.com/na.m.84" target="_blank" class="w-9 h-9 rounded-full glass flex items-center justify-center hover:bg-pink-600 transition-all">
                    <svg class="w-4 h-4 fill-current text-pink-400" viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 1.2.1 1.8.2 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1 .4 2.2.1 1.3.1 1.7.1 4.9s0 3.6-.1 4.9c-.1 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1 .4-2.2.4-1.3.1-1.7.1-4.9.1s-3.6 0-4.9-.1c-1.2-.1-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1-.4-2.2-.1-1.3-.1-1.7-.1-4.9s0-3.6.1-4.9c.1-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1-.4 2.2-.4 1.3-.1 1.7-.1 4.9-.1zm0 1.8c-3.1 0-3.5 0-4.8.1-1.1.1-1.5.2-1.9.3-.5.2-.8.4-1.1.7-.3.3-.5.6-.7 1.1-.1.4-.3.8-.3 1.9-.1 1.3-.1 1.7-.1 4.8s0 3.5.1 4.8c.1 1.1.2 1.5.3 1.9.2.5.4.8.7 1.1.3.3.6.5 1.1.7.4.1.8.3 1.9.3 1.3.1 1.7.1 4.8.1s3.5 0 4.8-.1c1.1-.1 1.5-.2 1.9-.3.5-.2.8-.4 1.1-.7.3-.3.5-.6.7-1.1.1-.4.3-.8.3-1.9.1-1.3.1-1.7.1-4.8s0-3.5-.1-4.8c-.1-1.1-.2-1.5-.3-1.9-.2-.5-.4-.8-.7-1.1-.3-.3-.6-.5-1.1-.7-.4-.1-.8-.3-1.9-.3-1.3-.1-1.7-.1-4.8-.1zm0 3.1a5 5 0 110 10 5 5 0 010-10zm0 8.2a3.2 3.2 0 100-6.4 3.2 3.2 0 000 6.4zm6.4-8.4a1.2 1.2 0 11-2.4 0 1.2 1.2 0 012.4 0z"/></svg>
                </a>
                <a href="https://t.me/nagham8881" target="_blank" class="w-9 h-9 rounded-full glass flex items-center justify-center hover:bg-sky-500 transition-all">
                    <svg class="w-4 h-4 fill-current text-sky-400" viewBox="0 0 24 24"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>
                </a>
            </div>
        </div>
        <div class="text-center mt-6 border-t border-[#4ADE80]/10 pt-4">
            <span class="text-xs text-[#94A3B8]/40">&copy; 2026 HF - Human Firewall. All rights reserved.</span>
        </div>
    </footer>

<script>
function toggleDropdown() {
    document.getElementById('dropdownMenu').classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('#startDropdown')) {
        document.getElementById('dropdownMenu').classList.add('hidden');
    }
});

const featureObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.card').forEach((el, index) => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = `all 0.7s ease ${index * 0.08}s`;
    featureObserver.observe(el);
});
// ==================== COUNTER ANIMATION ====================
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start);
            }
        }, 16);
    }

    // تشغيل العداد عند ظهوره
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                const target = parseInt(entry.target.textContent) || 0;
                entry.target.dataset.animated = 'true';
                animateCounter(entry.target, target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.counter').forEach(el => {
        counterObserver.observe(el);
    });
</script>
</body>
</html>