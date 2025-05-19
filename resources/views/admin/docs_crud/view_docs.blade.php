@extends('layouts.app')

@section('content')

<div class="container mt-4">
<a href="{{ route('admin.knowledge_base') }}" class="btn btn-secondary mb-3">Back</a>
    
  <div class="card">
    
    <div class="card-header">
      <h4>{{ $doc->kb_title }}</h4>
    </div>
    <div class="card-body">

      <p><strong>Source:</strong> {{ $doc->source }}</p>
      <p><strong>Category:</strong> {{ $doc->category->category_name ?? 'N/A' }}</p>
      <hr>
      <pre>{{ $doc->content }}</pre>
    </div>
  </div>
</div>
@endsection
