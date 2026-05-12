@extends('adminlte::page')

@section('title', 'Pengaturan Sistem')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-cog mr-2"></i>Pengaturan Sistem</h1>
    </div>
@stop

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')

    @php
        $groupLabels = [
            'midtrans' => ['label' => 'Midtrans Payment Gateway', 'icon' => 'fas fa-credit-card', 'color' => 'primary'],
            'payment'  => ['label' => 'Pengaturan Pembayaran',    'icon' => 'fas fa-money-bill',  'color' => 'success'],
            'app'      => ['label' => 'Pengaturan Aplikasi',      'icon' => 'fas fa-mobile-alt',  'color' => 'info'],
            'general'  => ['label' => 'Umum',                     'icon' => 'fas fa-sliders-h',   'color' => 'secondary'],
        ];
    @endphp

    @foreach($groups as $groupKey => $settings)
    @php $meta = $groupLabels[$groupKey] ?? ['label' => ucfirst($groupKey), 'icon' => 'fas fa-cog', 'color' => 'secondary']; @endphp
    <div class="card card-{{ $meta['color'] }} card-outline mb-4">
        <div class="card-header">
            <h3 class="card-title">
                <i class="{{ $meta['icon'] }} mr-2"></i>{{ $meta['label'] }}
            </h3>
        </div>
        <div class="card-body">
            @foreach($settings as $setting)
            <div class="form-group row">
                <label class="col-sm-3 col-form-label font-weight-bold">
                    {{ $setting->label }}
                    @if($setting->type === 'secret')
                        <span class="badge badge-warning ml-1">Secret</span>
                    @endif
                    @if($setting->is_public)
                        <span class="badge badge-info ml-1">Public</span>
                    @endif
                </label>
                <div class="col-sm-9">
                    @if($setting->type === 'boolean')
                        <div class="custom-control custom-switch mt-2">
                            <input
                                type="hidden"
                                name="{{ $setting->key }}"
                                value="false"
                            >
                            <input
                                type="checkbox"
                                class="custom-control-input"
                                id="setting_{{ $setting->key }}"
                                name="{{ $setting->key }}"
                                value="true"
                                {{ $setting->value === 'true' || $setting->value === '1' ? 'checked' : '' }}
                                onchange="this.previousElementSibling.value = this.checked ? 'true' : 'false'"
                            >
                            <label class="custom-control-label" for="setting_{{ $setting->key }}">
                                {{ $setting->value === 'true' || $setting->value === '1' ? 'Aktif' : 'Nonaktif' }}
                            </label>
                        </div>
                    @elseif($setting->type === 'secret')
                        <div class="input-group">
                            <input
                                type="password"
                                class="form-control"
                                name="{{ $setting->key }}"
                                value="{{ $setting->value ? substr($setting->value, 0, 4) . str_repeat('*', max(0, strlen($setting->value) - 8)) . substr($setting->value, -4) : '' }}"
                                placeholder="Kosongkan jika tidak ingin mengubah"
                                autocomplete="new-password"
                            >
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary toggle-secret" data-target="{{ $setting->key }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Kosongkan field ini jika tidak ingin mengubah nilai yang tersimpan.
                        </small>
                    @elseif($setting->type === 'integer')
                        <input
                            type="number"
                            class="form-control"
                            name="{{ $setting->key }}"
                            value="{{ $setting->value }}"
                        >
                    @else
                        <input
                            type="text"
                            class="form-control"
                            name="{{ $setting->key }}"
                            value="{{ $setting->value }}"
                        >
                    @endif

                    @if($setting->description)
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle mr-1"></i>{{ $setting->description }}
                    </small>
                    @endif
                </div>
            </div>
            @if(!$loop->last)<hr>@endif
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-save mr-2"></i>Simpan Semua Pengaturan
        </button>
    </div>
</form>
@stop

@section('js')
<script>
// Toggle secret field visibility
document.querySelectorAll('.toggle-secret').forEach(btn => {
    btn.addEventListener('click', function() {
        const targetName = this.dataset.target;
        const input = document.querySelector(`input[name="${targetName}"]`);
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            this.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            input.type = 'password';
            this.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
});

// Update boolean label on change
document.querySelectorAll('.custom-control-input').forEach(cb => {
    cb.addEventListener('change', function() {
        const label = this.nextElementSibling;
        label.textContent = this.checked ? 'Aktif' : 'Nonaktif';
    });
});
</script>
@stop
