<x-guest-layout>
    <h2 class="auth-title">Reset Password</h2>
    <p class="auth-subtitle">Masukkan password baru untuk akun Anda</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                    value="{{ old('email', $request->email) }}"
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
                <i class="fas fa-lock"></i> Password Baru
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
                    placeholder="Ulangi password baru"
                    required
                >
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-key me-2"></i> Reset Password
        </button>
    </form>
</x-guest-layout>
