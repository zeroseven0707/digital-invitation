<x-guest-layout>
    <h2 class="auth-title">Konfirmasi Password</h2>
    <p class="auth-subtitle">Ini adalah area aman. Silakan konfirmasi password Anda untuk melanjutkan.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

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

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check me-2"></i> Konfirmasi
        </button>
    </form>
</x-guest-layout>
