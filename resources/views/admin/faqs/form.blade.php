@extends('layout.admin')
@section('content')
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-4">{{ $faq->exists ? 'Edit question' : 'Add question' }}</h2>
        @if ($errors->any())<div class="alert alert-danger mt-3">@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>@endif
        <form method="post" action="{{ $faq->exists ? route('faqs.update', $faq) : route('faqs.store') }}" class="card shadow-sm mt-3" id="faq-form">
            @csrf
            @if($faq->exists) @method('PUT') @endif
            <div class="card-body">
                <label class="form-label">Question</label>
                <input name="question" class="form-control mb-3" value="{{ old('question', $faq->question) }}" required maxlength="255">
                <label class="form-label">Answer</label>
                <textarea id="answer" name="answer" class="form-control mb-3" rows="6">{{ old('answer', $faq->answer) }}</textarea>
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Display order</label>
                        <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $faq->sort_order) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" @selected(old('status', $faq->status) == 1)>Active</option>
                            <option value="0" @selected(old('status', $faq->status) == 0)>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer"><button class="btn btn-primary" type="submit">Save question</button></div>
        </form>
    </div>
</main>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editor = document.querySelector('#answer');
    if (editor && window.ClassicEditor) ClassicEditor.create(editor).catch(function () {});
    document.getElementById('faq-form')?.addEventListener('submit', function () {
        const button = this.querySelector('[type=submit]');
        if (button) button.disabled = true;
    });
});
</script>
@endsection
