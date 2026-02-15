<x-guest-layout>
    <h2 class="auth-title">Lupa Password?</h2>
    <p class="auth-subtitle">Kami akan mengirimkan link reset password ke email Anda</p>

    @if (session('status'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
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

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane me-2"></i> Kirim Link Reset Password
        </button>

        <div class="divider">
            <span>Atau</span>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="link-gold">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>
