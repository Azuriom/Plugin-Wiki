@csrf

@php
    $translations = $category->translations ?? [];
    $locales = array_keys($translations['name'] ?? []);
@endphp

@push('footer-scripts')
    <script>
        numberOfTranslatedElements = parseInt({{count($locales)}});

        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.command-remove').forEach(function (el) {
                addCommandListenerToTranslations(el);
            });

            document.getElementById('addCommandButton').addEventListener('click', function () {
                let form = `
            <div>
                <div class="input-group">
                    <span class="input-group-text">Locale and translation</span>
                    <input type="text" name="translations[`+numberOfTranslatedElements+`][locale]" aria-label="en" class="form-control">
                    <input type="text" name="translations[`+numberOfTranslatedElements+`][name]" aria-label="Home" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger command-remove" type="button"><i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
                `;
                addNodeToTranslationsDom(form);
            });
        });
    </script>
@endpush

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="translations">{{ trans('messages.fields.name') }}</label>

        <div id="translations">
            @forelse ($locales as $locale)
            <div>
                <div>
                <div class="input-group">
                    <span class="input-group-text">Locale and translation</span>
                    <input type="text" value="{{ old('translations.'.$loop->index.'.locale', $locale ?? '') }}" name="translations[{{$loop->index}}][locale]" aria-label="en" class="form-control">
                    <input type="text" class="form-control" name="translations[{{$loop->index}}][name]" value="{{ old('translations.'.$loop->index.'.name', $translations['name'][$locale] ?? '') }}" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger command-remove" type="button"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
            </div>
            @empty
            <div class="input-group">
                <span class="input-group-text">Locale and translation</span>
                <input type="text" value="{{ old('translations.default.locale', app()->getLocale()) }}" name="translations[default][locale]" aria-label="en" class="form-control" required>
                <input type="text" class="form-control" name="translations[default][name]" value="{{ old('translations.default.name', '') }}" required>
            </div>
            @endforelse
        </div>
        <button type="button" id="addCommandButton" class="btn btn-sm btn-success my-2">
            <i class="fas fa-plus"></i> {{ trans('messages.actions.add') }}
        </button>
    </div>

    <div class="form-group col-md-6">
        <label for="iconInput">{{ trans('messages.fields.icon') }}</label>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="{{ $category->icon ?? 'fas fa-book' }} fa-fw"></i></span>
            </div>

            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="iconInput" name="icon" value="{{ old('icon', $category->icon ?? '') }}" placeholder="fas fa-book" aria-labelledby="iconLabel">

            @error('icon')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <small id="iconLabel" class="form-text">@lang('wiki::admin.categories.icons')</small>
    </div>
</div>

<div class="form-group">
    <label for="slugInput">{{ trans('messages.fields.slug') }}</label>
    <div class="input-group">
        <div class="input-group-prepend">
            <div class="input-group-text">{{ url('/wiki') }}/</div>
        </div>
        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slugInput" name="slug" value="{{ old('slug', $category->slug ?? '') }}" required>

        @error('slug')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>
