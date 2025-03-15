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
                        <a class="nav-link active" href="{{ route('booking.create') }}">Buat Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('booking.history') }}">Histori Booking</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container mt-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Buat Booking</h3>
                </div>
                <div class="card-body">
                    <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama:</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Atas nama penyewa" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">No Telepon:</label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        placeholder="08xxxxxxxxxx" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat:</label>
                            <textarea id="address" name="address" class="form-control" rows="2" placeholder="Alamat penyewa" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Pilih Tanggal:</label>
                                    <input type="text" id="date" name="date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service" class="form-label">Pilih Layanan:</label>
                                    <select name="service" id="service" class="form-select">
                                        <option value="PS4">PS4 - Rp 30.000</option>
                                        <option value="PS5">PS5 - Rp 40.000</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="session_duration" class="form-label">Durasi Sesi (jam):</label>
                            <input type="number" name="session_duration" class="form-control" required min="1"
                                max="10">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Lanjutkan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Rental PS - Semua Hak Dilindungi</p>
    </footer> --}}


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

    

    <script>
        flatpickr("#date", {
            enableTime: false,
            dateFormat: "Y-m-d"
        });



        $(document).ready(function() {
            $('#bookingForm').on('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "/booking/store",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('#loadingModal').modal('show');
                    },
                    success: function(response) {
                        $('#loadingModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = response.redirect;
                        });
                    },
                    error: function(xhr) {
                        $('#loadingModal').modal('hide');
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = "Terjadi kesalahan, silakan coba lagi.";

                        if (errors) {
                            errorMessage = Object.values(errors).map(err => err.join("\n"))
                                .join("\n");
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endsection
