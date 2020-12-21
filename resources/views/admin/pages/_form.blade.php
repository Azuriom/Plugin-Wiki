@csrf

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="titleInput">{{ trans('messages.fields.title') }}</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" id="titleInput" name="title" value="{{ old('title', $page->title ?? '') }}" required>

        @error('title')
        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group col-md-6">
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
</div>

<div class="form-group">
    <label for="textArea">{{ trans('messages.fields.content') }}</label>
    <textarea class="form-control html-editor @error('content') is-invalid @enderror" id="textArea" name="content" rows="5">{{ old('content', $page->content ?? '') }}</textarea>

    @error('content')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>
