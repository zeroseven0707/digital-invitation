@props(['label', 'name', 'type' => 'text', 'value' => '', 'required' => false])

<div class="form-group">
    <label for="{{ $name }}">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    <input type="{{ $type }}"
           class="form-control @error($name) is-invalid @enderror"
           id="{{ $name }}"
           name="{{ $name }}"
           value="{{ old($name, $value) }}"
           {{ $required ? 'required' : '' }}
           {{ $attributes }}>
    @error($name)
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>
