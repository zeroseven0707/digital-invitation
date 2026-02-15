<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="form-group">
        <label for="name">Nama</label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="name"
               name="name"
               value="{{ old('name', $user->name) }}"
               required
               autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email"
               class="form-control @error('email') is-invalid @enderror"
               id="email"
               name="email"
               value="{{ old('email', $user->email) }}"
               required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert alert-warning mt-2">
                <p class="mb-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    Email Anda belum diverifikasi.
                </p>
                <button form="send-verification" class="btn btn-sm btn-warning">
                    <i class="fas fa-envelope"></i> Kirim Ulang Email Verifikasi
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-success">
                        <i class="fas fa-check-circle"></i>
                        Link verifikasi baru telah dikirim ke email Anda.
                    </p>
                @endif
            </div>
        @endif
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>

        @if (session('status') === 'profile-updated')
            <span class="text-success ml-2">
                <i class="fas fa-check-circle"></i> Tersimpan!
            </span>
        @endif
    </div>
</form>
