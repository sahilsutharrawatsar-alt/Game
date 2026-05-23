@extends('layouts.admin', ['title' => 'Payments'])
@section('content')
<h1 class="text-3xl font-black">Payments</h1>
<div class="mt-6 overflow-x-auto rounded-2xl border border-white/10"><table class="w-full text-left text-sm"><thead class="bg-white/[.04] text-slate-400"><tr><th class="p-3">Provider ID</th><th>Booking</th><th>User</th><th>Venue</th><th>Amount</th><th>Status</th></tr></thead><tbody>@foreach($payments as $payment)<tr class="border-t border-white/10"><td class="p-3">{{ $payment->provider_payment_id ?? $payment->provider_order_id }}</td><td>{{ $payment->booking->booking_number }}</td><td>{{ $payment->booking->user->name }}</td><td>{{ $payment->booking->venue->name }}</td><td>₹{{ number_format($payment->amount, 2) }}</td><td>{{ $payment->status }}</td></tr>@endforeach</tbody></table></div><div class="mt-5">{{ $payments->links() }}</div>
@endsection
