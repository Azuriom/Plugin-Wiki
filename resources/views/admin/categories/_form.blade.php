@csrf

<div class="row g-3">
    <div class="mb-3 col-md-6">
        <label class="form-label" for="nameInput">{{ trans('messages.fields.name') }}</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameInput" name="name" value="{{ old('name', $category->name ?? '') }}" required>

        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="iconInput">{{ trans('messages.fields.icon') }}</label>

        <div class="input-group @error('icon') has-validation @enderror">
            <span class="input-group-text"><i class="{{ $category->icon ?? 'fas fa-book' }} fa-fw"></i></span>

            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="iconInput" name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="fas fa-book" aria-labelledby="iconLabel">

            @error('icon')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <small id="iconLabel" class="form-text">@lang('messages.fontawesome')</small>
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="slugInput">{{ trans('messages.fields.slug') }}</label>
    <div class="input-group @error('slug') has-validation @enderror">
        <div class="input-group-text">{{ url('/wiki') }}/</div>
        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slugInput" name="slug" value="{{ old('slug', $category->slug ?? '') }}" required>

        @error('slug')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="mb-3 form-check form-switch">
    <input type="checkbox" class="form-check-input" id="enableSwitch" name="is_enabled" @checked($category->is_enabled ?? true)>
    <label class="form-check-label" for="enableSwitch">{{ trans('wiki::admin.categories.enable') }}</label>
</div>
