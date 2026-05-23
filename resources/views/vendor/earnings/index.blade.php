@extends('layouts.vendor', ['title' => 'Earnings'])
@section('content')
<h1 class="text-3xl font-black">Earnings</h1>
<div class="mt-6 rounded-3xl border border-white/10 bg-white/[.05] p-6"><p class="text-sm text-slate-400">Total net earnings</p><b class="mt-2 block text-4xl text-lime-200">₹{{ number_format($total, 2) }}</b></div>
<div class="mt-6 overflow-x-auto rounded-3xl border border-white/10"><table class="w-full text-left text-sm"><thead class="bg-white/[.04] text-slate-400"><tr><th class="p-3">Booking</th><th>Ground</th><th>Gross</th><th>Fee</th><th>Net</th><th>Status</th></tr></thead><tbody>@foreach($earnings as $earning)<tr class="border-t border-white/10"><td class="p-3">{{ $earning->booking->booking_number }}</td><td>{{ $earning->booking->venue->name }}</td><td>₹{{ number_format($earning->gross_amount,2) }}</td><td>₹{{ number_format($earning->platform_fee,2) }}</td><td>₹{{ number_format($earning->net_amount,2) }}</td><td>{{ $earning->status }}</td></tr>@endforeach</tbody></table></div>
@endsection
