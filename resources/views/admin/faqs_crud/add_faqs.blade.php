@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <h3>Add New FAQ</h3>
    <form action="{{ route('faqs.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Question</label>
            <input type="text" name="question" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Answer</label>
            <textarea name="answer" class="form-control" rows="4" ></textarea>
        </div>

        <div class="mb-3">
          <label for="category" class="form-label">Category</label>
          <select name="category" id="category" class="form-select" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
              <option value="{{ $category->categoryID }}">{{ $category->category_name }}</option>
            @endforeach
          </select>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('faqs') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
