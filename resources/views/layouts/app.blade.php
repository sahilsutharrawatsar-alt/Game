<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'ArenaX' }}</title>
    <meta name="description" content="{{ $description ?? 'Book premium sports venues in Mohali, Chandigarh, Zirakpur, and Panchkula.' }}">
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="min-h-screen bg-[#05070f] text-slate-100 antialiased selection:bg-lime-300 selection:text-slate-950">
    <div class="fixed inset-0 -z-10 bg-[radial-gradient(circle_at_18%_0%,rgba(34,211,238,.20),transparent_26%),radial-gradient(circle_at_80%_12%,rgba(168,85,247,.18),transparent_30%),radial-gradient(circle_at_50%_100%,rgba(163,230,53,.14),transparent_32%),linear-gradient(135deg,#05070f,#0a1220_42%,#030712)]"></div>
    <header class="sticky top-0 z-40 border-b border-white/10 bg-[#05070f]/78 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="grid size-10 place-items-center rounded-lg bg-gradient-to-br from-lime-300 via-cyan-300 to-violet-400 font-black text-slate-950 shadow-lg shadow-cyan-500/20">AX</span>
                <span class="text-lg font-black tracking-wide">ArenaX</span>
            </a>
            <div class="hidden items-center gap-6 text-sm text-slate-300 md:flex">
                <a href="{{ route('venues.index') }}" class="hover:text-lime-300">Venues</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-lime-300">Dashboard</a>
                    @if(auth()->user()->isAdmin())<a href="{{ route('admin.dashboard') }}" class="hover:text-lime-300">Admin</a>@endif
                    @if(auth()->user()->role === 'vendor')<a href="{{ route('vendor.dashboard') }}" class="hover:text-lime-300">Vendor</a>@endif
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="hover:text-lime-300">Logout</button></form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-lime-300">Login</a>
                    <a href="{{ route('vendor.login') }}" class="hover:text-lime-300">Vendor</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-lime-300">Admin</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-gradient-to-r from-lime-300 to-cyan-300 px-4 py-2 font-bold text-slate-950 shadow-lg shadow-cyan-500/20">Create account</a>
                @endauth
            </div>
        </nav>
    </header>
    @if(session('status'))
        <div class="mx-auto mt-4 max-w-7xl px-4"><div class="rounded border border-lime-400/30 bg-lime-400/10 px-4 py-3 text-sm text-lime-100">{{ session('status') }}</div></div>
    @endif
    <main class="pb-20 md:pb-0">{{ $slot ?? '' }}@yield('content')</main>
    <nav class="fixed bottom-0 left-0 right-0 z-50 grid grid-cols-4 border-t border-white/10 bg-[#05070f]/95 px-2 py-2 text-center text-xs backdrop-blur-xl md:hidden">
        <a href="{{ route('home') }}" class="rounded-xl px-2 py-2 text-slate-300">Home</a>
        <a href="{{ route('venues.index') }}" class="rounded-xl px-2 py-2 text-slate-300">Venues</a>
        <a href="{{ route('dashboard') }}" class="rounded-xl px-2 py-2 text-slate-300">Bookings</a>
        @auth
            @if(auth()->user()->role === 'vendor')
                <a href="{{ route('vendor.dashboard') }}" class="rounded-xl px-2 py-2 text-slate-300">Vendor</a>
            @else
                <a href="{{ route('profile') }}" class="rounded-xl px-2 py-2 text-slate-300">Profile</a>
            @endif
        @else
            <a href="{{ route('login') }}" class="rounded-xl px-2 py-2 text-slate-300">Login</a>
        @endauth
    </nav>
    <footer class="mt-16 border-t border-white/10 bg-black/30">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 md:grid-cols-4">
            <div><div class="text-xl font-black">ArenaX</div><p class="mt-3 text-sm text-slate-400">Premium turf, court, pool, and indoor sports bookings for the Chandigarh tri-city.</p></div>
            <div><h3 class="font-bold">Cities</h3><p class="mt-3 text-sm text-slate-400">Mohali<br>Chandigarh<br>Zirakpur<br>Panchkula</p></div>
            <div><h3 class="font-bold">Sports</h3><p class="mt-3 text-sm text-slate-400">Box Cricket, Football Turf, Tennis, Badminton, Basketball, Table Tennis, Volleyball, Swimming, Pickleball</p></div>
            <div><h3 class="font-bold">Support</h3><p class="mt-3 text-sm text-slate-400">Email confirmations, live slot availability, promo codes, and secure Razorpay-ready checkout.</p></div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
