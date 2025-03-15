<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout($id)
    {
        $booking = Booking::findOrFail($id);
    
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    
        $transaction_details = [
            // order id ambil tanggal sekarang 'BOOK-' . $booking->id . '-' . date('Ymd'),
            'order_id' => 'PSN-' . $booking->id . '-' . \Carbon\Carbon::parse($booking->date)->format('Ymd') . '-' . '0' . Auth::id(),
            'gross_amount' => $booking->total_price,
        ];
    
        $customer_details = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone,
        ];
    
        $payload = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
        ];
    
        try {
            $snapToken = Snap::getSnapToken($payload);
            return response()->json([
                'success' => true,
                'message' => 'Snap token generated successfully',
                'data' => [
                    'snapToken' => $snapToken,
                    'orderId' => $transaction_details['order_id'],
                    'amount' => $transaction_details['gross_amount']
                ]
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    public function callback(Request $request)
    {
        // $serverKey = config('midtrans.server_key');
    
        $orderId = $request->transaction['order_id'];
        $statusCode = $request->transaction['status_code'];
        $grossAmount = number_format($request->transaction['gross_amount'], 2, '.', '');
        // $signatureKey = $request->signature_key;
   
        // $hashed = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
    

        // if ($hashed !== $signatureKey) {
        //     return response()->json(['message' => 'Invalid Signature'], 403);
        // }
    
 
        $bookingId = (int) str_replace('PSN-', '', explode('-', $orderId)[1]); 
        $booking = Booking::find($bookingId);
    
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }
    
        if (in_array($request->transaction['transaction_status'], ['capture', 'settlement'])) {
            $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);
            Payment::create([
                'booking_id' => $booking->id,
                'transaction_id' => $request->transaction['transaction_id'],
                'payment_method' => $request->transaction['payment_type'],
                'amount' => $grossAmount,
                'status' => $statusCode == 200 ? 'success' : 'failed',
            ]);
        } elseif (in_array($request->transaction['transaction_status'], ['expire', 'cancel'])) {
            $booking->update(['payment_status' => 'failed']);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Payment status updated',
        ]);
    }
    
}
