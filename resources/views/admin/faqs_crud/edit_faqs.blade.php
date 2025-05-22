@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <h3>Edit FAQ</h3>
    <form action="{{ route('faqs.update', $faq->faqID) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Question</label>
            <input type="text" name="question" class="form-control" value="{{ $faq->question }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Answer</label>
            <textarea name="answer" class="form-control" rows="4" required>{{ $faq->answer }}</textarea>
        </div>

        <div class="mb-3">
    <label for="category" class="form-label">Category</label>
    <select name="category" id="category" class="form-select" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category->categoryID }}" 
                {{ $faq->categoryID == $category->categoryID ? 'selected' : '' }}>
                {{ $category->category_name }}
            </option>
        @endforeach
    </select>
</div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('faqs') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
