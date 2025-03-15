@extends('layouts.app')
@section('content')
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Rental PS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#services">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </li>
                    @endguest

                    @auth
                        <a class="nav-link" href="#" id="logoutButton">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1>Selamat Datang di Rental PS</h1>
            <p>Sewa PlayStation dengan mudah dan nyaman</p>

            @auth
                <a href="{{ route('booking.create') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-shopping-cart"></i> Buat Booking Sekarang
                </a>
            @else
                <div class="alert alert-warning text-dark mt-4 p-4 rounded shadow-sm" style="max-width: 500px; margin: auto;">
                    <i class="fas fa-exclamation-circle fa-lg text-danger"></i>
                    <strong>Silakan login untuk melakukan booking</strong>
                    <p class="mt-2">Anda harus masuk terlebih dahulu sebelum bisa menyewa PlayStation.</p>
                    <a href="#" class="btn btn-dark btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="fas fa-sign-in-alt"></i> Login Sekarang
                    </a>
                </div>
            @endauth
        </div>
    </header>


    <section id="services" class="py-5">
        <div class="container text-center">
            <h2 class="mb-4">Layanan Kami</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm">
                        <img src="{{ asset('images/PS5.jpg') }}" class="mb-3" alt="PS5">
                        <h5>Rental PS5</h5>
                        <p>Nikmati pengalaman gaming terbaru dengan PS5.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm">
                        <img src="{{ asset('images/PS4.jpg') }}" class="mb-3" alt="PS5">
                        <h5>Rental PS4</h5>
                        <p>Mainkan game favorit Anda dengan PS4 terbaik.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm">
                        <img src="{{ asset('images/aksesoris.jpg') }}" height="168" class="mb-3" alt="aksesoris">
                        <h5>Aksesoris Gaming</h5>
                        <p>Sewa aksesoris tambahan untuk pengalaman gaming lebih seru.</p>
                    </div>
                </div>
            </div>
        </div>
        
    </section>

    <section id="contact" class="py-5">
        <div class="container text-center">
            <h2 class="mb-4">Hubungi Kami</h2>
            <p>Silakan hubungi kami melalui informasi di bawah ini:</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <ul class="list-group text-start">
                        <li class="list-group-item"><i class="fas fa-envelope"></i> Email: contact@yourwebsite.com</li>
                        <li class="list-group-item"><i class="fas fa-phone"></i> Telepon: +62 812-3456-7890</li>
                        <li class="list-group-item"><i class="fas fa-map-marker-alt"></i> Alamat: Jl. Merak No. 123, Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Rental PS - Semua Hak Dilindungi</p>
    </footer> --}}


    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="loginEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="loginPassword" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Register -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Register</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="registerName" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="registerName" name="name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="registerEmail" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registerPhone" class="form-label">Nomor Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" id="registerPhone" name="phone">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registerAddress" class="form-label">Alamat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea class="form-control" id="registerAddress" name="address"></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registerCity" class="form-label">Kota</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-city"></i></span>
                                <input type="text" class="form-control" id="registerCity" name="city">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registerZip" class="form-label">Kode Pos</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope-open-text"></i></span>
                                <input type="text" class="form-control" id="registerZip" name="zip_code">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="registerPassword" name="password"
                                    required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
        $(document).ready(function() {
        // Login Form
        $('#loginForm').submit(function(event) {
            event.preventDefault();
            $('#loadingModal').modal('show');

            let formData = new FormData(this);

            fetch('/login', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                $('#loadingModal').modal('hide');

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil!',
                        text: 'Selamat datang, ' + data.user.name,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.href = '/');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal!',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                $('#loadingModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Silakan coba lagi.'
                });
            });

            return false;
        });

        // Logout Button
        $('#logoutButton').click(function() {
            Swal.fire({
                title: 'Logout',
                text: 'Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/';
                        } else {
                            Swal.fire('Error', 'Gagal logout, coba lagi!', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Terjadi kesalahan saat logout', 'error');
                    });
                }
            });
        });

        // Register Form
        $('#registerForm').submit(function(event) {
            event.preventDefault();
            $('#loadingModal').modal('show');

            let formData = new FormData(this);

            fetch('/register', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                $('#loadingModal').modal('hide');

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrasi Berhasil!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registrasi Gagal!',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                }
            })
            .catch(error => {
                $('#loadingModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: 'Silakan coba lagi.'
                });
            });

            return false; 
        });
    });
    </script>
@endsection
