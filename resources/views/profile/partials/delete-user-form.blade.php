<div class="alert alert-danger">
    <h5><i class="fas fa-exclamation-triangle"></i> Peringatan!</h5>
    <p class="mb-0">
        Setelah akun Anda dihapus, semua data dan resource akan dihapus secara permanen.
        Sebelum menghapus akun, silakan download data yang ingin Anda simpan.
    </p>
</div>

<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteAccountModal">
    <i class="fas fa-trash-alt"></i> Hapus Akun
</button>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus Akun
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-body">
                    <p class="font-weight-bold">Apakah Anda yakin ingin menghapus akun Anda?</p>
                    <p class="text-muted">
                        Setelah akun dihapus, semua data akan hilang secara permanen.
                        Masukkan password Anda untuk konfirmasi.
                    </p>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Masukkan password Anda"
                               required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
<script>
    $(document).ready(function() {
        $('#deleteAccountModal').modal('show');
    });
</script>
@endif
