<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="form-group">
        <label for="current_password">Password Saat Ini</label>
        <input type="password"
               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
               id="current_password"
               name="current_password"
               autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password Baru</label>
        <input type="password"
               class="form-control @error('password', 'updatePassword') is-invalid @enderror"
               id="password"
               name="password"
               autocomplete="new-password">
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="form-text text-muted">
            <i class="fas fa-info-circle"></i> Minimal 8 karakter
        </small>
    </div>

    <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password Baru</label>
        <input type="password"
               class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
               id="password_confirmation"
               name="password_confirmation"
               autocomplete="new-password">
        @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-key"></i> Update Password
        </button>

        @if (session('status') === 'password-updated')
            <span class="text-success ml-2">
                <i class="fas fa-check-circle"></i> Password berhasil diupdate!
            </span>
        @endif
    </div>
</form>
