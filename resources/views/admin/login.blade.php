<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { background: #0A0A0F; font-family: 'Space Grotesk', sans-serif; color: #F1F5F9; }
        .glass { background: rgba(17,24,39,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(239,68,68,0.2); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="glass rounded-3xl p-10 max-w-md w-full">
        <div class="text-center mb-8">
            <span class="material-icons text-5xl text-red-400 mb-4">admin_panel_settings</span>
            <h1 class="text-2xl font-black text-red-400">Admin Access</h1>
            <p class="text-[#94A3B8] text-sm mt-2">Restricted - Authorized only</p>
        </div>
        
        @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-6 text-red-400 text-sm">
            {{ $errors->first() }}
        </div>
        @endif
        
        <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
            @csrf
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]">mail</span>
                <input type="email" name="email" required placeholder="Admin Email" 
                    class="w-full bg-[#0A0A0F] border border-red-500/20 rounded-xl pl-10 pr-4 py-3 focus:border-red-500/50 focus:outline-none">
            </div>
            <div class="relative">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-[#94A3B8]">lock</span>
                <input type="password" name="password" required placeholder="Password" 
                    class="w-full bg-[#0A0A0F] border border-red-500/20 rounded-xl pl-10 pr-4 py-3 focus:border-red-500/50 focus:outline-none">
            </div>
            <button type="submit" 
                class="w-full py-3 bg-gradient-to-r from-red-500 to-red-700 text-white rounded-full font-bold hover:scale-105 transition-all">
                Access Dashboard
            </button>
        </form>
    </div>
</body>
</html>