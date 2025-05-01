@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Upload Knowledge Base Document</h4>
    </div>

    <div class="card-body">
   
      <form action="{{ route('kb.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

   
        <div class="mb-3">
          <label for="kb_title" class="form-label">Document Title</label>
          <input type="text" id="kb_title" name="kb_title" class="input-field" required placeholder="Enter the document title">
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

        <div class="mb-3">
          <label for="document" class="form-label">Upload Document</label>
          <input type="file" id="document" name="document" class="form-control" accept=".pdf,.docx,.txt" required>
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ route('admin.knowledge_base') }}" class="btn btn-secondary">Back</a>
          <button type="submit" class="btn btn-primary">Upload Document</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
