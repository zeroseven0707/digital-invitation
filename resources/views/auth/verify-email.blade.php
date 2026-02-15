<x-guest-layout>
    <h2 class="auth-title">Verifikasi Email</h2>
    <p class="auth-subtitle">Terima kasih telah mendaftar! Silakan verifikasi alamat email Anda dengan mengklik link yang kami kirimkan.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            <span>Link verifikasi baru telah dikirim ke alamat email Anda.</span>
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane me-2"></i> Kirim Ulang Email Verifikasi
        </button>
    </form>

    <div class="divider">
        <span>Atau</span>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100">
            <i class="fas fa-sign-out-alt me-2"></i> Keluar
        </button>
    </form>
</x-guest-layout>
