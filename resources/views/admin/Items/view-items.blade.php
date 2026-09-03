@extends('layouts.app')

@section('title', 'Menu Items')

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
                            <li class="breadcrumb-item active" aria-current="page">Menu Items</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-12">
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">Manage Menu Items</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>

                            @include('components.session-messages')

                            <div class="card-body">
                                {{-- Header --}}
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-4">
                                    <div>
                                        <h5 class="mb-1">All Menu Items</h5>
                                    </div>
                                    <a href="{{ route('item.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Add Item
                                    </a>
                                </div>

                                {{-- Filters --}}
                                <div class="card bg-light mb-4">
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            {{-- Search --}}
                                            <div class="col-lg-4">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                    <input type="text" id="searchInput" class="form-control"
                                                        placeholder="Search by name..." autocomplete="off">
                                                </div>
                                            </div>

                                            {{-- Section Filter --}}
                                            <div class="col-lg-2">
                                                <select id="filterSection" class="form-select">
                                                    <option value="">All Sections</option>
                                                    @foreach ($items->unique('menuSubcategory.menuCategory.menuSection.id') as $item)
                                                        @if ($item->menuSubcategory && $item->menuSubcategory->menuCategory && $item->menuSubcategory->menuCategory->menuSection)
                                                            <option
                                                                value="{{ $item->menuSubcategory->menuCategory->menuSection->id }}">
                                                                {{ $item->menuSubcategory->menuCategory->menuSection->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Category Filter --}}
                                            <div class="col-lg-2">
                                                <select id="filterCategory" class="form-select">
                                                    <option value="">All Categories</option>
                                                    @foreach ($items->unique('menuSubcategory.menuCategory.id') as $item)
                                                        @if ($item->menuSubcategory && $item->menuSubcategory->menuCategory)
                                                            <option value="{{ $item->menuSubcategory->menuCategory->id }}"
                                                                data-section="{{ $item->menuSubcategory->menuCategory->menu_section_id }}">
                                                                {{ $item->menuSubcategory->menuCategory->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Subcategory Filter --}}
                                            <div class="col-lg-2">
                                                <select id="filterSubcategory" class="form-select">
                                                    <option value="">All Subcategories</option>
                                                    @foreach ($items->unique('menuSubcategory.id') as $item)
                                                        @if ($item->menuSubcategory)
                                                            <option value="{{ $item->menuSubcategory->id }}"
                                                                data-category="{{ $item->menuSubcategory->menu_category_id }}">
                                                                {{ $item->menuSubcategory->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Status Filter --}}
                                            <div class="col-lg-2">
                                                <select id="filterStatus" class="form-select">
                                                    <option value="">All Status</option>
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                </select>
                                            </div>

                                            {{-- Reset Filters --}}
                                            <div class="col-lg-12">
                                                <button id="resetFilters" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Table --}}
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 120px;">Actions</th>
                                                <th style="width: 80px;">Image</th>
                                                <th style="width: 180px;">Name</th>
                                                <th style="width: 150px;">Subcategory</th>
                                                <th style="width: 150px;">Category</th>
                                                <th style="width: 140px;">Section</th>
                                                <th style="width: 100px;">Price</th>
                                                <th style="width: 110px;">Status</th>
                                                <th style="width: 100px;">Offer</th>
                                            </tr>
                                        </thead>

                                        <tbody id="itemsTableBody">
                                            @forelse ($items as $item)
                                                <tr data-item-row data-name="{{ strtolower($item->name) }}"
                                                    data-section="{{ $item->menuSubcategory->menuCategory->menu_section_id ?? '' }}"
                                                    data-category="{{ $item->menuSubcategory->menu_category_id ?? '' }}"
                                                    data-subcategory="{{ $item->menu_subcategory_id }}"
                                                    data-status="{{ $item->status }}">
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            <a href="{{ route('item.show', $item->id) }}"
                                                                class="btn btn-sm btn-outline-secondary" title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </a>

                                                            <a href="{{ route('item.edit', $item->id) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>


                                                            <form action="{{ route('item.destroy', $item->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger w-100"
                                                                    title="Delete"
                                                                    onclick="return confirm('Are you sure?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        @if ($item->image)
                                                            <img src="{{ asset('storage/' . $item->image) }}"
                                                                alt="{{ $item->name }}" class="img-thumbnail"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 60px; height: 60px;">
                                                                <i class="bi bi-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <div class="fw-semibold">{{ $item->name }}</div>
                                                        <small class="text-muted">{{ $item->description ?? '--' }}</small>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="badge bg-info">{{ $item->menuSubcategory->name ?? '--' }}</span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="badge bg-warning text-dark">{{ $item->menuSubcategory->menuCategory->name ?? '--' }}</span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="badge bg-secondary">{{ $item->menuSubcategory->menuCategory->menuSection->name ?? '--' }}</span>
                                                    </td>

                                                    <td>
                                                        @php
                                                            $activeOffers = $item->getActiveOffers();
                                                            $hasActiveOffer = $activeOffers->count() > 0;
                                                        @endphp

                                                        @if ($hasActiveOffer)
                                                            @php
                                                                $offer = $activeOffers->first();
                                                                $originalPrice = $item->price;

                                                                if ($offer->discount_type === 'percentage') {
                                                                    $discountedPrice = $originalPrice * (100 - $offer->discount_value) / 100;
                                                                } else {
                                                                    $discountedPrice =  $originalPrice - $offer->discount_value;
                                                                }
                                                            @endphp
                                                            <div>
                                                                <div class="fw-bold text-danger">
                                                                    <s>${{ number_format($originalPrice, 2) }}</s>
                                                                </div>
                                                                <div class="fw-bold text-success">
                                                                    ${{ number_format($discountedPrice, 2) }}
                                                                </div>
                                                                <small class="text-muted">
                                                                    @if ($offer->discount_type === 'percentage')
                                                                        Save {{ $offer->discount_value }}%
                                                                    @else
                                                                        Save ${{ number_format($offer->discount_value, 2) }}
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        @else
                                                            <div class="fw-bold text-success">
                                                                ${{ number_format($item->price, 2) }}</div>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="badge @if ($item->status === 'active') bg-success @else bg-danger @endif">
                                                            {{ ucfirst($item->status) }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span
                                                            class="badge @if ($item->has_offer) bg-primary @else bg-secondary @endif">
                                                            {{ $item->has_offer ? 'Yes' : 'No' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">No menu items found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    @if (count($items) === 0)
                                        <div class="alert alert-info text-center mt-3">
                                            <i class="bi bi-info-circle me-2"></i> No menu items found.
                                        </div>
                                    @endif
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <style>
                                        .pagination {
                                            margin: 0;
                                            gap: 0.25rem;
                                            justify-content: flex-end;
                                        }

                                        .pagination .page-link {
                                            padding: 0.35rem 0.6rem;
                                            font-size: 0.875rem;
                                            line-height: 1.25;
                                            border-radius: 0.25rem;
                                        }

                                        .pagination .page-link:hover {
                                            background-color: #e9ecef;
                                        }

                                        .pagination .page-item.active .page-link {
                                            background-color: #0d6efd;
                                            border-color: #0d6efd;
                                        }

                                        .pagination .page-item.disabled .page-link {
                                            color: #6c757d;
                                            pointer-events: none;
                                            background-color: #fff;
                                            border-color: #dee2e6;
                                        }
                                    </style>
                                    <div class="d-flex justify-content-end">
                                        {{ $items->links() }}
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
            const searchInput = document.getElementById('searchInput');
            const filterSection = document.getElementById('filterSection');
            const filterCategory = document.getElementById('filterCategory');
            const filterSubcategory = document.getElementById('filterSubcategory');
            const filterStatus = document.getElementById('filterStatus');
            const resetBtn = document.getElementById('resetFilters');
            const tbody = document.getElementById('itemsTableBody');

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const sectionId = filterSection.value;
                const categoryId = filterCategory.value;
                const subcategoryId = filterSubcategory.value;
                const status = filterStatus.value;

                // Update category options based on section
                const categoryOptions = document.querySelectorAll('#filterCategory option[data-section]');
                categoryOptions.forEach(opt => {
                    if (sectionId === '' || opt.getAttribute('data-section') === sectionId) {
                        opt.style.display = '';
                    } else {
                        opt.style.display = 'none';
                    }
                });

                // Update subcategory options based on category
                const subcategoryOptions = document.querySelectorAll('#filterSubcategory option[data-category]');
                subcategoryOptions.forEach(opt => {
                    if (categoryId === '' || opt.getAttribute('data-category') === categoryId) {
                        opt.style.display = '';
                    } else {
                        opt.style.display = 'none';
                    }
                });

                // Filter rows
                const rows = tbody.querySelectorAll('tr[data-item-row]');
                rows.forEach(row => {
                    const name = row.getAttribute('data-name');
                    const rowSection = row.getAttribute('data-section');
                    const rowCategory = row.getAttribute('data-category');
                    const rowSubcategory = row.getAttribute('data-subcategory');
                    const rowStatus = row.getAttribute('data-status');

                    const matchesSearch = name.includes(searchTerm);
                    const matchesSection = sectionId === '' || rowSection === sectionId;
                    const matchesCategory = categoryId === '' || rowCategory === categoryId;
                    const matchesSubcategory = subcategoryId === '' || rowSubcategory === subcategoryId;
                    const matchesStatus = status === '' || rowStatus === status;

                    row.style.display = matchesSearch && matchesSection && matchesCategory &&
                        matchesSubcategory && matchesStatus ? '' : 'none';
                });
            }

            function resetFilters() {
                searchInput.value = '';
                filterSection.value = '';
                filterCategory.value = '';
                filterSubcategory.value = '';
                filterStatus.value = '';
                applyFilters();
            }

            searchInput?.addEventListener('input', applyFilters);
            filterSection?.addEventListener('change', applyFilters);
            filterCategory?.addEventListener('change', applyFilters);
            filterSubcategory?.addEventListener('change', applyFilters);
            filterStatus?.addEventListener('change', applyFilters);
            resetBtn?.addEventListener('click', resetFilters);
        })();
    </script>
@endsection
