@props(['label', 'name', 'value' => '', 'required' => false, 'rows' => 3])

<div class="form-group">
    <label for="{{ $name }}">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
    <textarea class="form-control @error($name) is-invalid @enderror"
              id="{{ $name }}"
              name="{{ $name }}"
              rows="{{ $rows }}"
              {{ $required ? 'required' : '' }}
              {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @error($name)
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>
