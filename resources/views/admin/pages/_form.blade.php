@include('admin.elements.editor')

@csrf

@php
    $translations = $page->translations ?? [];
    $locales = array_keys($translations['title'] ?? []);
@endphp

@push('footer-scripts')
    <script>
        var test = parseInt({{count($locales)}});
        numberOfTranslatedElements = parseInt({{count($locales)}});

        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.translation-remove').forEach(function (el) {
            addCommandListenerToTranslations(el);
            });

            document.getElementById('addCommandButton').addEventListener('click', function () {
            
            let form = `
            
                <div class="form-group">
                    <label for="translationInput-`+numberOfTranslatedElements+`">Translation</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="translationInput-`+numberOfTranslatedElements+`" name="translations[`+numberOfTranslatedElements+`][locale]" value="" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-danger translation-remove" type="button"><i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="titleInput-`+numberOfTranslatedElements+`">{{ trans('messages.fields.title') }}</label>
                    <input type="text" class="form-control" id="titleInput-`+numberOfTranslatedElements+`" name="translations[`+numberOfTranslatedElements+`][title]" value="" required>
                </div>
            

            <div class="form-group">
                <label for="textArea-`+numberOfTranslatedElements+`">{{ trans('messages.fields.content') }}</label>
                <textarea class="form-control" id="textArea-`+numberOfTranslatedElements+`" name="translations[`+numberOfTranslatedElements+`][content]" rows="5"></textarea>
            </div>
            `;

            addNodeToTranslationsDom(form);
            });
        });
    </script>
@endpush

<div id="translations">
@forelse ($locales as $locale)
<div>
        <div class="form-group">
            <label for="translationInput-{{$loop->index}}">Translation</label>
            <div class="input-group">
                <input type="text" class="form-control" id="translationInput-{{$loop->index}}" name="translations[{{$loop->index}}][locale]" value="{{ old('translations.'.$loop->index.'.locale', $locale ?? '') }}" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-danger translation-remove" type="button"><i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    
        <div class="form-group">
            <label for="titleInput-{{$loop->index}}">{{ trans('messages.fields.title') }}</label>
            <input type="text" class="form-control @error('title-'.$loop->index) is-invalid @enderror" id="titleInput-{{$loop->index}}" name="translations[{{$loop->index}}][title]" value="{{ old('translations.'.$loop->index.'.title', $translations['title'][$locale] ?? '') }}" required>
    
            @error('title-'.$loop->index)
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

    <div class="form-group">
        <label for="textArea-{{$loop->index}}">{{ trans('messages.fields.content') }}</label>
        <textarea class="form-control html-editor @error('content-'.$loop->index) is-invalid @enderror" id="textArea-{{$loop->index}}" name="translations[{{$loop->index}}][content]" rows="5">{{ old('translations.'.$loop->index.'.content', $translations['content'][$locale] ?? '') }}</textarea>

        @error('content-'.$loop->index)
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>
    
@empty
    <div>
            <div class="form-group">
                <label for="translationInput-default">Translation</label>
                <input type="text" class="form-control" id="translationInput-default" name="translations[default][locale]" value="{{ old('translations.default.locale', app()->getLocale()) }}" required>
            </div>
            
            <div class="form-group">
                <label for="titleInput-default">{{ trans('messages.fields.title') }}</label>
                <input type="text" class="form-control @error('title-default') is-invalid @enderror" id="titleInput-default" name="translations[default][title]" value="{{ old('translations.default.title', '') }}" required>
            
                @error('title-default')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

        <div class="form-group">
            <label for="textArea-default">{{ trans('messages.fields.content') }}</label>
            <textarea class="form-control html-editor @error('content-default') is-invalid @enderror" id="textArea-default" name="translations[default][content]" rows="5">{{ old('translations.default.content', '') }}</textarea>
        
            @error('content-default')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>
@endforelse
</div>

<button type="button" id="addCommandButton" class="btn btn-sm btn-success my-2">
    <i class="fas fa-plus"></i> {{ trans('messages.actions.add') }}
</button>

<div class="form-group">
    <label for="categorySelect">{{ trans('messages.fields.category') }}</label>

    <select class="custom-select" id="categorySelect" name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @if(old('category_id', $page->category_id ?? 0) === $category->id) selected @endif>{{ $category->name }}</option>
        @endforeach
    </select>

    @error('category_id')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
