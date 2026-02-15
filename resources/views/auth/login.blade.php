<x-guest-layout>
    <h2 class="auth-title">Selamat Datang Kembali</h2>
    <p class="auth-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                    autofocus
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
                    placeholder="Masukkan password Anda"
                    required
                >
            </div>
            @error('password')
                <div class="text-danger mt-2 small">
                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">
                    Ingat Saya
                </label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-gold small">
                    Lupa Password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk
        </button>

        <div class="divider">
            <span>Belum punya akun?</span>
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}" class="link-gold">
                <i class="fas fa-user-plus me-1"></i> Daftar Sekarang
            </a>
        </div>
    </form>
</x-guest-layout>
