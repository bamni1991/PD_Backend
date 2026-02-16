@extends('ui.layout')

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Add New Entry -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 text-white"><i class="ti ti-pencil"></i> Write Diary</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('personal-diary.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Today's Highlight"
                                    required>
                            </div>
                            <div class="mb-3 d-none">
                                <label class="form-label">Date</label>
                                <input type="date" name="entry_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea name="content" class="form-control" rows="10" placeholder="Dear Diary..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Save Entry</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Diary Entries -->
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Your Memories</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('personal-diary.export-pdf', request()->query()) }}"
                            class="btn btn-outline-danger" target="_blank" title="Export as PDF">
                            <i class="ti ti-file-description"></i> PDF
                        </a>
                        <form action="{{ route('personal-diary.index') }}" method="GET" class="d-flex" role="search"
                            style="max-width: 300px;">
                            <input type="search" name="search" class="form-control me-2" placeholder="Search..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-primary" type="submit"><i class="ti ti-search"></i></button>
                        </form>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="row">
                    @foreach ($diaries as $diary)
                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title text-primary">{{ $diary->title ?? 'Untitled' }}</h5>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($diary->entry_date)->format('d M, Y') }}</small>
                                    </div>
                                    <p class="card-text mt-2">{{ Str::limit($diary->content, 200) }}</p>

                                    <button class="btn btn-sm btn-light-info" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $diary->id }}" aria-expanded="false">
                                        Read More
                                    </button>

                                    <div class="collapse mt-3" id="collapse{{ $diary->id }}">
                                        <div class="card card-body bg-light">
                                            {{ $diary->content }}
                                        </div>
                                        <div class="mt-2 text-end">
                                            <form action="{{ route('personal-diary.destroy', $diary->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($diaries->isEmpty())
                    <div class="alert alert-info text-center">No diary entries yet. Start writing today!</div>
                @else
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Showing {{ $diaries->firstItem() }} to {{ $diaries->lastItem() }} of
                                {{ $diaries->total() }} entries</span>

                            <div class="btn-group">
                                {{-- Previous Page Link --}}
                                @if ($diaries->onFirstPage())
                                    <button class="btn btn-outline-secondary" disabled>Previous</button>
                                @else
                                    <a href="{{ $diaries->previousPageUrl() }}&search={{ request('search') }}"
                                        class="btn btn-outline-primary">Previous</a>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($diaries->hasMorePages())
                                    <a href="{{ $diaries->nextPageUrl() }}&search={{ request('search') }}"
                                        class="btn btn-outline-primary">Next</a>
                                @else
                                    <button class="btn btn-outline-secondary" disabled>Next</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
