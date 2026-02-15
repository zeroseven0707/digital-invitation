@extends('layouts.admin')

@section('page-title', 'Tambah Template Baru')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Manajemen Template</a></li>
    <li class="breadcrumb-item active">Tambah Template</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle mr-2"></i>Informasi Template
            </h3>
        </div>
        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="name">Nama Template <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Contoh: Classic Elegant"
                           required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="3"
                              placeholder="Deskripsi singkat tentang template ini...">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="html_file">HTML File <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input @error('html_file') is-invalid @enderror"
                                       id="html_file"
                                       name="html_file"
                                       accept=".html"
                                       required>
                                <label class="custom-file-label" for="html_file">Choose file</label>
                            </div>
                            @error('html_file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="css_file">CSS File <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input @error('css_file') is-invalid @enderror"
                                       id="css_file"
                                       name="css_file"
                                       accept=".css"
                                       required>
                                <label class="custom-file-label" for="css_file">Choose file</label>
                            </div>
                            @error('css_file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="js_file">JavaScript File</label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input @error('js_file') is-invalid @enderror"
                                       id="js_file"
                                       name="js_file"
                                       accept=".js">
                                <label class="custom-file-label" for="js_file">Choose file (optional)</label>
                            </div>
                            @error('js_file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="thumbnail">Thumbnail Image</label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input @error('thumbnail') is-invalid @enderror"
                                       id="thumbnail"
                                       name="thumbnail"
                                       accept="image/*">
                                <label class="custom-file-label" for="thumbnail">Choose image (optional)</label>
                            </div>
                            @error('thumbnail')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Variabel Template:</strong> Gunakan placeholder berikut di HTML Anda:
                    <code>@{{ '{{bride_name}}' }}</code>,
                    <code>@{{ '{{groom_name}}' }}</code>,
                    <code>@{{ '{{akad_date}}' }}</code>, dll.
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Template
                </button>
                <a href="{{ route('admin.templates.index') }}" class="btn btn-default">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Update file input labels
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@endpush
