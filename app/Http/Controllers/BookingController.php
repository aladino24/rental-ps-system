<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function index()
    {
        return view('home');
    }

    // public function history()
    // {
    //     return view('history');
    // }

    public function create()
    {
        return view('booking.create');
    }

    public function gethistory()
    {
        $bookings = Booking::where('user_id', Auth::id())->orderBy('date', 'desc')->get();
        return view('booking.history', compact('bookings'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'service' => 'required|string',
            'session_duration' => 'required|integer|min:1'
        ]);

        try {
            $base_price = $validated['service'] == 'PS4' ? 30000 : 40000;
            $weekend_surcharge = (Carbon::parse($validated['date'])->isWeekend()) ? 50000 : 0;
            $total_price = ($base_price * $validated['session_duration']) + $weekend_surcharge;

            $booking = Booking::create([
                'user_id' => Auth::user()->id,
                'date' => $validated['date'],
                'service' => $validated['service'],
                'session_duration' => $validated['session_duration'],
                'base_price' => $base_price,
                'surcharge' => $weekend_surcharge,
                'total_price' => $total_price,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat booking. Silakan coba lagi.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil!',
                'redirect' => route('booking.history')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function checkout($id)
    {
        $booking = Booking::findOrFail($id);
        return view('booking.checkout', compact('booking'));
    }

    public function cancel($id)
    {
        try {
            $booking = Booking::findOrFail($id);

            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak dapat dibatalkan.'
                ], 400);
            }

            $booking->update([
                'status' => 'canceled',
                'payment_status' => 'failed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function generateNota($id)
    {
        $booking = Booking::with('user')->findOrFail($id);

        $pdf = Pdf::loadView('booking.nota', compact('booking'));

        return $pdf->download('nota_' . $booking->id . '.pdf');
    }
}
