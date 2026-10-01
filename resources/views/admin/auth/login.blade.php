<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Access - HF</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body { background: #0A0A0F; font-family: 'Inter', sans-serif; color: #F1F5F9; }
        h1,h2,h3 { font-family: 'Space Grotesk', sans-serif; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(74,222,128,0.15); }
        .cyber-grid { background-image: linear-gradient(rgba(74,222,128,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(74,222,128,0.03) 1px, transparent 1px); background-size: 50px 50px; }
        .glow-green { box-shadow: 0 0 40px rgba(74,222,128,0.15); }
        .radial-border { position: relative; }
        .radial-border::before {
            content: ''; position: absolute; inset: -1px; border-radius: inherit; padding: 1px;
            background: radial-gradient(circle at 30% 30%, rgba(74,222,128,0.4), transparent 60%),
                        radial-gradient(circle at 70% 70%, rgba(96,165,250,0.3), transparent 60%);
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
        }
    </style>
</head>
<body class="cyber-grid min-h-screen flex items-center justify-center p-4">

    <div class="fixed top-1/4 left-1/4 w-[500px] h-[500px] bg-[#4ADE80]/10 blur-[150px] rounded-full pointer-events-none"></div>
    <div class="fixed bottom-1/4 right-1/4 w-[400px] h-[400px] bg-[#60A5FA]/10 blur-[150px] rounded-full pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="glass rounded-3xl p-10 radial-border glow-green">
            <div class="text-center mb-8">
                <div class="w-20 h-20 mx-auto rounded-full glass radial-border flex items-center justify-center glow-green mb-4">
                    <span class="material-icons text-4xl text-[#4ADE80]">admin_panel_settings</span>
                </div>
                <h1 class="text-2xl font-black mb-2">Admin <span class="text-[#4ADE80]">Access</span></h1>
                <p class="text-[#94A3B8] text-xs">Restricted Area — Authorized Personnel Only</p>
            </div>

            @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 text-red-400 text-xs flex items-start gap-2">
                <span class="material-icons text-sm">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6 text-red-400 text-xs flex items-start gap-2">
                <span class="material-icons text-sm">error</span>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Email</label>
                    <div class="relative">
                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8] text-lg">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="admin@hf.com"
                            class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl pl-10 pr-4 py-3 text-[#F1F5F9] focus:border-[#4ADE80]/50 focus:outline-none text-sm">
                    </div>
                </div>

                <div>
                    <label class="text-xs text-[#94A3B8] uppercase mb-2 block font-bold">Password</label>
                    <div class="relative">
                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8] text-lg">lock</span>
                        <input type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full bg-[#0A0A0F] border border-[#4ADE80]/20 rounded-xl pl-10 pr-4 py-3 text-[#F1F5F9] focus:border-[#4ADE80]/50 focus:outline-none text-sm">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-[#94A3B8] cursor-pointer">
                        <input type="checkbox" name="remember" class="accent-[#4ADE80]">
                        Remember me
                    </label>
                    <span class="text-[#94A3B8]">🔒 Encrypted session</span>
                </div>

                <button type="submit" 
                    class="w-full py-3 bg-gradient-to-r from-[#4ADE80] to-[#60A5FA] text-[#0A0A0F] rounded-full font-black hover:scale-105 transition-all glow-green">
                    <span class="material-icons align-middle mr-1 text-lg">login</span>
                    Access Dashboard
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-[#4ADE80]/10 text-center">
                <p class="text-[10px] text-[#94A3B8]/60">
                    <span class="material-icons text-xs align-middle">shield</span>
                    Protected by rate limiting & audit logging
                </p>
            </div>
        </div>
    </div>
</body>
</html>