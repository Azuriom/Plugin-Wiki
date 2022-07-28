@csrf

<div class="row g-3">
    <div class="mb-3 col-md-6">
        <label class="form-label" for="titleInput">{{ trans('messages.fields.title') }}</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="titleInput" name="title" value="{{ old('title', $page->title ?? '') }}" required>

        @error('title')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3 col-md-6">
        <label class="form-label" for="categorySelect">{{ trans('messages.fields.category') }}</label>

        <select class="form-select" id="categorySelect" name="category_id">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $page->category_id ?? 0) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>

        @error('category_id')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="slugInput">{{ trans('messages.fields.slug') }}</label>
    <div class="input-group @error('slug') has-validation @enderror">
        <div class="input-group-text">{{ route('wiki.index') }}/{{ '{'.trans('wiki::admin.pages.category').'}' }}/</div>
        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slugInput" name="slug" value="{{ old('slug', $page->slug ?? '') }}" required>

        @error('slug')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="textArea">{{ trans('messages.fields.content') }}</label>
    <textarea class="form-control html-editor @error('content') is-invalid @enderror" id="textArea" name="content" rows="5">{{ old('content', $page->content ?? '') }}</textarea>

    @error('content')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
