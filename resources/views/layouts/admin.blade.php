<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin - ArenaX' }}</title>
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-[#05070f] text-slate-100">
    <div class="min-h-screen md:flex">
        <aside class="border-b border-white/10 bg-black/40 p-4 md:min-h-screen md:w-64 md:border-b-0 md:border-r">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-black"><span class="grid size-9 place-items-center rounded-lg bg-gradient-to-br from-lime-300 via-cyan-300 to-violet-400 text-slate-950">AX</span>ArenaX Admin</a>
            <nav class="mt-8 grid gap-2 text-sm">
                @foreach([['admin.dashboard','Overview'],['admin.vendors.index','Vendors'],['admin.venues.index','Venues'],['admin.slots.index','Slots'],['admin.bookings.index','Bookings'],['admin.payments.index','Payments'],['admin.users.index','Users'],['admin.reviews.index','Reviews'],['admin.sports.index','Sports'],['admin.coupons.index','Coupons'],['admin.offers.index','Offers'],['admin.banners.index','Banners'],['admin.notifications.index','Notifications']] as [$route,$label])
                    <a href="{{ route($route) }}" class="rounded px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white">{{ $label }}</a>
                @endforeach
                <a href="{{ route('home') }}" class="rounded px-3 py-2 text-slate-300 hover:bg-white/10 hover:text-white">View site</a>
            </nav>
        </aside>
        <main class="flex-1 p-4 md:p-8">
            @if(session('status'))<div class="mb-5 rounded border border-lime-400/30 bg-lime-400/10 px-4 py-3 text-sm">{{ session('status') }}</div>@endif
            @yield('content')
        </main>
    </div>
</body>
</html>
