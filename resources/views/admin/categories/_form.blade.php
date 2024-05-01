@csrf

<div class="row gx-3">
    <div class="mb-3 col-md-6">
        <label class="form-label" for="nameInput">{{ trans('messages.fields.name') }}</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameInput" name="name" value="{{ old('name', $category->name ?? '') }}" required>

        @error('name')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="parentSelect">{{ trans('wiki::admin.categories.parent') }}</label>

        <select class="form-select" id="parentSelect" name="parent_id">
            <option value="">{{ trans('messages.none') }}</option>
            @foreach($categories as $sub)
                <option value="{{ $sub->id }}" @selected((int) old('parent_id', $category->parent_id ?? 0) === $sub->id)>
                    {{ $sub->name }}
                </option>
            @endforeach
        </select>

        @error('category_id')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="iconInput">{{ trans('messages.fields.icon') }}</label>

    <div class="input-group @error('icon') has-validation @enderror">
        <span class="input-group-text"><i class="{{ $category->icon ?? 'bi bi-book' }} fa-fw"></i></span>

        <input type="text" class="form-control @error('icon') is-invalid @enderror" id="iconInput" name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="bi bi-book" aria-labelledby="iconLabel">

        @error('icon')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <small id="iconLabel" class="form-text">@lang('messages.icons')</small>
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
    <input type="checkbox" class="form-check-input" id="privateSwitch" name="is_private" data-bs-toggle="collapse" data-bs-target="#rolesGroup" @checked(isset($category) && $category->roles !== null)>
    <label class="form-check-label" for="privateSwitch">{{ trans('wiki::admin.categories.private') }}</label>
</div>

<div id="rolesGroup" class="{{ (isset($category) && $category->roles !== null) ? 'show' : 'collapse' }}">
    <div class="card card-body mb-2 pb-0">
        @foreach($roles as $role)
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="role{{ $role->id }}" name="roles[]" value="{{ $role->id }}" @checked(isset($category) && $category->roles !== null && $category->hasRole($role))>
                <label class="form-check-label" for="role{{ $role->id }}">
                    <span class="badge" style="{{ $role->getBadgeStyle() }}">{{ $role->name }}</span>
                </label>
            </div>
        @endforeach
    </div>
</div>

<div class="mb-3 form-check form-switch">
    <input type="checkbox" class="form-check-input" id="enableSwitch" name="is_enabled" @checked($category->is_enabled ?? true)>
    <label class="form-check-label" for="enableSwitch">{{ trans('wiki::admin.categories.enable') }}</label>
</div>
