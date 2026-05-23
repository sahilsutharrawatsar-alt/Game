<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return view('admin.payments.index', [
            'payments' => Payment::with('booking.user', 'booking.venue')->latest()->paginate(25),
        ]);
    }
}
