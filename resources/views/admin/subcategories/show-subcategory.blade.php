@extends('layouts.app')

@section('title', 'Show Subcategory')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Subcategories</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('subcategory.index') }}">Subcategories</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Show</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-12">
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">Subcategory Details</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>

                            @include('components.session-messages')

                            <div class="card-body">
                                <div class="row g-4">
                                    {{-- Main Details Card --}}
                                    <div class="col-lg-7">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body p-4">
                                                {{-- Header with Name and Badges --}}
                                                <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                                                    <div class="flex-grow-1">
                                                        <h4 class="fw-bold mb-2" id="showName">
                                                            {{ $subcategory->name ?? '—' }}
                                                        </h4>
                                                        <p class="text-muted mb-0" id="showDesc" style="white-space: pre-wrap; line-height: 1.6;">
                                                            {{ $subcategory->description ?? 'No description provided' }}
                                                        </p>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="mb-2">
                                                            <span class="badge bg-primary fs-6" id="showOrder">
                                                                <i class="bi bi-sort-numeric-up me-1"></i>{{ $subcategory->display_order ?? 1 }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <span class="badge bg-success fs-6" id="showStatus">
                                                                <i class="bi bi-check-circle me-1"></i>{{ $subcategory->status ?? 'active' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr class="my-4" />

                                                {{-- Details Grid --}}
                                                <div class="row g-3">
                                                    <div class="col-sm-6">
                                                        <div class="p-3 bg-light rounded">
                                                            <div class="small text-muted mb-2">
                                                                <i class="bi bi-sort-numeric-up me-1"></i>Display Order
                                                            </div>
                                                            <div class="fw-bold fs-5" id="showOrderText">
                                                                {{ $subcategory->display_order ?? 1 }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="p-3 bg-light rounded">
                                                            <div class="small text-muted mb-2">
                                                                <i class="bi bi-toggle-on me-1"></i>Status
                                                            </div>
                                                            <div class="fw-bold fs-5 text-success" id="showStatusText">
                                                                {{ ucfirst($subcategory->status ?? 'active') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr class="my-4" />

                                                {{-- Category Info --}}
                                                <div class="p-3 bg-light rounded">
                                                    <div class="small text-muted mb-2">
                                                        <i class="bi bi-tag me-1"></i>Category
                                                    </div>
                                                    <div class="fw-bold fs-5" id="showCategory">
                                                        {{ $subcategory->menuCategory->name ?? '—' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Actions Card --}}
                                    <div class="col-lg-5">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header bg-light border-0 py-3">
                                                <h5 class="card-title mb-0">
                                                    <i class="bi bi-lightning-fill text-warning me-2"></i>Actions
                                                </h5>
                                            </div>
                                            <div class="card-body p-4">
                                                <div class="d-grid gap-3">
                                                    <a class="btn btn-primary btn-lg" href="{{ route('subcategory.edit', $subcategory->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit Subcategory
                                                    </a>
                                                    <form action="{{ route('subcategory.destroy', $subcategory->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-lg w-100"
                                                            onclick="return confirm('Are you sure you want to delete this subcategory?')">
                                                            <i class="bi bi-trash me-2"></i> Delete Subcategory
                                                        </button>
                                                    </form>
                                                </div>

                                                <hr class="my-4" />

                                                <div class="d-grid">
                                                    <a href="{{ route('subcategory.index') }}" class="btn btn-outline-secondary">
                                                        <i class="bi bi-arrow-left me-2"></i> Back to Subcategories
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const showStatus = document.getElementById('showStatus');
            const showStatusText = document.getElementById('showStatusText');

            function setStatus(newStatus) {
                if (!showStatus) return;
                const s = String(newStatus || 'active');
                showStatus.textContent = s;
                if (s === 'active') {
                    showStatus.classList.remove('bg-secondary');
                    showStatus.classList.add('bg-success');
                } else {
                    showStatus.classList.remove('bg-success');
                    showStatus.classList.add('bg-secondary');
                }

                if (showStatusText) showStatusText.textContent = s;
            }

            // Ensure correct badge style on load
            if (showStatus) {
                setStatus(showStatus.textContent);
            }
        })();
    </script>
@endsection
