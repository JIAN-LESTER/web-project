@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <a href="{{ route('admin.knowledge_base') }}" class="btn btn-outline-secondary mb-5">
        <i class="fas fa-arrow-left"></i> Back to Knowledge Base
    </a>

    <div class="card shadow-sm border-0">
        <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #0F4C3A;">
            <h4 class="mb-0">{{ $doc->kb_title }}</h4>
            <span class="badge bg-light text-dark">{{ $doc->category->category_name ?? 'Uncategorized' }}</span>
        </div>

        <div class="card-body">
            <div class="mb-3">
                <p class="mb-1 text-muted fw-semibold">Source:</p>
                <p class="form-control-plaintext">{{ $doc->source }}</p>
            </div>

            <hr>

            <div>
                <p class="mb-2 text-muted fw-semibold">Document Content:</p>
                <div class="border rounded p-3 bg-light" style="max-height: 500px; overflow-y: auto; white-space: pre-wrap;">
                    {{ $doc->content }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
