@extends('layouts.app')

@section('title', 'Show Item')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Menu Items</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('item.index') }}">Items</a></li>
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
                                <h3 class="card-title">Item Details</h3>
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

                                                {{-- Item Image --}}
                                                @if ($item->image)
                                                    <div class="mb-4 text-center">
                                                        <img src="{{ asset('storage/' . $item->image) }}"
                                                            alt="{{ $item->name }}" class="img-fluid rounded"
                                                            style="max-height: 260px; width: 100%; object-fit: cover; border-radius: 10px !important;">
                                                    </div>
                                                @else
                                                    <div class="mb-4 text-center bg-light rounded d-flex align-items-center justify-content-center"
                                                        style="height: 180px;">
                                                        <div class="text-muted">
                                                            <i class="bi bi-image" style="font-size: 2.5rem;"></i>
                                                            <p class="mb-0 mt-2 small">No image uploaded</p>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- Name, Price, Badges --}}
                                                <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                                                    <div class="flex-grow-1">
                                                        <h4 class="fw-bold mb-1">{{ $item->name ?? '—' }}</h4>
                                                        <p class="text-muted mb-0"
                                                            style="white-space: pre-wrap; line-height: 1.6;">
                                                            {{ $item->description ?? 'No description provided' }}
                                                        </p>
                                                    </div>
                                                    <div class="text-end flex-shrink-0">
                                                        <div class="mb-2">
                                                            <span class="badge bg-primary fs-6">
                                                                <i
                                                                    class="bi bi-currency-dollar me-1"></i>{{ number_format($item->price, 2) }}
                                                            </span>
                                                        </div>
                                                        <div class="mb-2">
                                                            @if ($item->status === 'active')
                                                                <span class="badge bg-success fs-6" id="showStatus">
                                                                    <i class="bi bi-check-circle me-1"></i>Active
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary fs-6" id="showStatus">
                                                                    <i class="bi bi-x-circle me-1"></i>Inactive
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if ($item->has_offer)
                                                            <div>
                                                                <span class="badge bg-warning text-dark fs-6">
                                                                    <i class="bi bi-tag-fill me-1"></i>Has Offer
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <hr class="my-4" />

                                                {{-- Details Grid --}}
                                                <div class="row g-3">
                                                    <div class="col-sm-6">
                                                        <div class="p-3 bg-light rounded">
                                                            <div class="small text-muted mb-1">
                                                                <i class="bi bi-collection me-1"></i>Section
                                                            </div>
                                                            <div class="fw-semibold">
                                                                {{ $item->menuSubcategory?->menuCategory?->menuSection?->name ?? '—' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="p-3 bg-light rounded">
                                                            <div class="small text-muted mb-1">
                                                                <i class="bi bi-grid me-1"></i>Category
                                                            </div>
                                                            <div class="fw-semibold">
                                                                {{ $item->menuSubcategory?->menuCategory?->name ?? '—' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="p-3 bg-light rounded">
                                                            <div class="small text-muted mb-1">
                                                                <i class="bi bi-diagram-3 me-1"></i>Subcategory
                                                            </div>
                                                            <div class="fw-semibold">
                                                                {{ $item->menuSubcategory?->name ?? '—' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="p-3 bg-light rounded">
                                                            <div class="small text-muted mb-1">
                                                                <i class="bi bi-clock me-1"></i>Preparation Time
                                                            </div>
                                                            <div class="fw-semibold">
                                                                {{ $item->preparation_time ? $item->preparation_time . ' min' : '—' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="p-3 bg-light rounded">
                                                            <div class="small text-muted mb-1">
                                                                <i class="bi bi-toggle-on me-1"></i>Status
                                                            </div>
                                                            <div
                                                                class="fw-semibold {{ $item->status === 'active' ? 'text-success' : 'text-secondary' }}">
                                                                {{ ucfirst($item->status ?? 'active') }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="p-3 bg-light rounded">
                                                            <div class="small text-muted mb-1">
                                                                <i class="bi bi-tag me-1"></i>Has Offer
                                                            </div>
                                                            <div
                                                                class="fw-semibold {{ $item->has_offer ? 'text-warning' : 'text-muted' }}">
                                                                {{ $item->has_offer ? 'Yes' : 'No' }}
                                                            </div>
                                                        </div>
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
                                                    <a class="btn btn-primary btn-lg"
                                                        href="{{ route('item.edit', $item->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit Item
                                                    </a>
                                                    <form action="{{ route('item.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-lg w-100"
                                                            onclick="return confirm('Are you sure you want to delete this item?')">
                                                            <i class="bi bi-trash me-2"></i> Delete Item
                                                        </button>
                                                    </form>
                                                </div>

                                                <hr class="my-4" />

                                                {{-- Quick Info --}}
                                                <div class="mb-4">
                                                    <h6 class="text-muted fw-semibold mb-3 small text-uppercase">Quick Info
                                                    </h6>
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                                            <span class="text-muted small">Price</span>
                                                            <span
                                                                class="fw-semibold">${{ number_format($item->price, 2) }}</span>
                                                        </li>
                                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                                            <span class="text-muted small">Section</span>
                                                            <span
                                                                class="fw-semibold">{{ $item->menuSubcategory?->menuCategory?->menuSection?->name ?? '—' }}</span>
                                                        </li>
                                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                                            <span class="text-muted small">Category</span>
                                                            <span
                                                                class="fw-semibold">{{ $item->menuSubcategory?->menuCategory?->name ?? '—' }}</span>
                                                        </li>
                                                        <li class="d-flex justify-content-between py-2 border-bottom">
                                                            <span class="text-muted small">Subcategory</span>
                                                            <span
                                                                class="fw-semibold">{{ $item->menuSubcategory?->name ?? '—' }}</span>
                                                        </li>
                                                        <li class="d-flex justify-content-between py-2">
                                                            <span class="text-muted small">Has Offer</span>
                                                            <span
                                                                class="fw-semibold">{{ $item->has_offer ? 'Yes' : 'No' }}</span>
                                                        </li>
                                                    </ul>
                                                </div>

                                                <div class="d-grid">
                                                    <a href="{{ route('item.index') }}"
                                                        class="btn btn-outline-secondary">
                                                        <i class="bi bi-arrow-left me-2"></i> Back to Items
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
@endsection
