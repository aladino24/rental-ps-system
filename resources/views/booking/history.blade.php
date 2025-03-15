@extends('layouts.app')
@section('content')
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Rental PS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('booking.create') }}">Buat Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('booking.history') }}">Histori Booking</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container mt-4">
            <h2 class="mb-4">History Booking</h2>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Daftar Booking Anda</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Layanan</th>
                                <th>Jumlah Sesi</th>
                                <th>Biaya Tambahan</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Nota</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $index => $booking)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</td>
                                    <td>{{ $booking->service }}</td>
                                    <td>{{ $booking->session_duration }}</td>
                                    <td>Rp {{ number_format($booking->surcharge, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($booking->status == 'pending')
                                            <span class="badge bg-warning">{{ ucfirst($booking->status) }}</span>
                                        @elseif ($booking->status == 'confirmed')
                                            <span class="badge bg-success">{{ ucfirst($booking->status) }}</span>
                                        @elseif ($booking->status == 'canceled')
                                            <span class="badge bg-danger">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($booking->payment_status == 'paid')
                                            <a href="{{ route('booking.nota', $booking->id) }}" target="_blank">
                                                <i class="fa fa-file-pdf text-danger"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($booking->payment_status == 'pending')
                                            <button class="btn btn-success btn-sm btn-bayar" data-id="{{ $booking->id }}">
                                                Bayar
                                            </button>
                                            <button class="btn btn-danger btn-sm btn-cancel" data-id="{{ $booking->id }}">
                                                Batalkan
                                            </button>
                                        @elseif ($booking->payment_status == 'paid')
                                            <span class="badge bg-primary">Telah terbayar</span>
                                        @elseif ($booking->payment_status == 'failed')
                                            <span class="badge bg-danger">Pembayaran gagal</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada booking.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <img src="{{ asset('images/loading.gif') }}" alt="Loading..." width="80">
                    <p class="mt-2">Mohon tunggu...</p>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('.btn-cancel').click(function() {
                  $('#loadingModal').modal('show');
                let bookingId = $(this).data('id');

                Swal.fire({
                    title: "Yakin ingin membatalkan booking?",
                    text: "Aksi ini tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, batalkan!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#loadingModal').modal('show');
                        $.ajax({
                            url: "/booking/cancel/" + bookingId,
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                $('#loadingModal').modal('hide');
                                Swal.fire("Dibatalkan!", response.message, "success")
                                    .then(() => location.reload());
                            },
                            error: function() {
                                $('#loadingModal').modal('hide');
                                Swal.fire("Error!", "Gagal membatalkan booking.",
                                    "error");
                            }
                        });
                    }else{
                        $('#loadingModal').modal('hide');
                    }
                });
            });

            $('.btn-bayar').click(function() {
                // loading

                let bookingId = $(this).data('id');

                $.ajax({
                    url: "/booking/" + bookingId + "/checkout",
                    type: "GET",
                    success: function(response) {
                        if (response.data.snapToken) {
                            snap.pay(response.data.snapToken, {
                                onSuccess: function(result) {
                                    Swal.fire("Sukses!", "Pembayaran berhasil!",
                                            "success")
                                        .then(() => {
                                            $.post("/payment/callback", {
                                                _token: "{{ csrf_token() }}",
                                                transaction: result
                                            }, function() {
                                                location.reload();
                                            });
                                        });
                                },
                                onPending: function(result) {
                                    Swal.fire("Menunggu!", "Menunggu pembayaran...",
                                        "info");
                                },
                                onError: function(result) {
                                    Swal.fire("Error!", "Pembayaran gagal!",
                                        "error");
                                }
                            });
                        } else {
                            Swal.fire("Error!", "Gagal mendapatkan token pembayaran.", "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error!", "Gagal memproses pembayaran.", "error");
                    }
                });
            });
        });
    </script>
@endsection
