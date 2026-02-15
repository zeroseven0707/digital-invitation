<x-guest-layout>
    <h2 class="auth-title">Buat Akun Baru</h2>
    <p class="auth-subtitle">Daftar untuk membuat undangan digital Anda</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="fas fa-user"></i> Nama Lengkap
            </label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Masukkan nama lengkap Anda"
                    required
                    autofocus
                >
            </div>
            @error('name')
                <div class="text-danger mt-2 small">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope"></i> Email
            </label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    required
                >
            </div>
            @error('email')
                <div class="text-danger mt-2 small">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock"></i> Password
            </label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
                <input
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                >
            </div>
            @error('password')
                <div class="text-danger mt-2 small">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock"></i> Konfirmasi Password
            </label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
                <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    name="password_confirmation"
                    placeholder="Ulangi password Anda"
                    required
                >
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
        </button>

        <div class="divider">
            <span>Sudah punya akun?</span>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="link-gold">
                <i class="fas fa-sign-in-alt me-1"></i> Masuk di Sini
            </a>
        </div>
    </form>
</x-guest-layout>
