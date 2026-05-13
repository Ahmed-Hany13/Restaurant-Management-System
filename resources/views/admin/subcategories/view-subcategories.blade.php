@extends('layouts.app')

@section('title', 'Subcategories')
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
                            <li class="breadcrumb-item active" aria-current="page">Subcategories</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">Manage Subcategories</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>

                            @include('components.session-messages')

                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <div>
                                        <h5 class="mb-1">All Subcategories</h5>
                                    </div>

                                    <a href="{{ route('subcategory.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Create Subcategory
                                    </a>
                                </div>

                                <!-- Filters Section -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="filterSection" class="form-label">Filter by Section</label>
                                        <select id="filterSection" class="form-select" onchange="applyFilters()">
                                            <option value="all" selected>All Sections</option>
                                            @foreach ($subcategories->pluck('menuCategory.menuSection')->unique('id')->filter() as $section)
                                                <option value="section-{{ $section->id }}">{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="filterCategory" class="form-label">Filter by Category</label>
                                        <select id="filterCategory" class="form-select" onchange="applyFilters()">
                                            <option value="all" selected>All Categories</option>
                                            @foreach ($subcategories->pluck('menuCategory')->unique('id')->filter() as $category)
                                                <option value="category-{{ $category->id }}"
                                                    data-section-id="section-{{ $category->menu_section_id }}">
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 150px;">Actions</th>
                                                <th style="width: 200px;">Name</th>
                                                <th style="width: 200px;">Category</th>
                                                <th style="width: 180px;">Section</th>
                                                <th style="width: 120px;">Items Count</th>
                                                <th style="width: 130px;">Display Order</th>
                                                <th style="width: 120px;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="subcategoriesTableBody">
                                            @forelse ($subcategories as $subcategory)
                                                <tr data-subcategory-row
                                                    data-section-id="section-{{ $subcategory->menuCategory->menu_section_id }}"
                                                    data-category-id="category-{{ $subcategory->menuCategory->id }}">
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            <a href="{{ route('subcategory.show', $subcategory->id) }}"
                                                                class="btn btn-sm btn-outline-secondary"
                                                                title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </a>

                                                            <a href="{{ route('subcategory.edit', $subcategory->id) }}"
                                                                class="btn btn-sm btn-outline-primary"
                                                                title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>

                                                            <form action="{{ route('subcategory.destroy', $subcategory->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger w-100"
                                                                    title="Delete"
                                                                    onclick="return confirm('Are you sure you want to delete this subcategory?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="fw-semibold">{{ $subcategory->name }}</div>
                                                    </td>

                                                    <td>
                                                        <span class="badge text-bg-info">
                                                            {{ $subcategory->menuCategory->name ?? '--' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="badge text-bg-light">
                                                            {{ $subcategory->menuCategory->menuSection->name ?? '--' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="badge text-bg-secondary">
                                                            {{ $subcategory->item_count }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="badge text-bg-light" data-display-order>
                                                            {{ $subcategory->display_order }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="badge {{ $subcategory->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $subcategory->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted py-4">
                                                        <i class="bi bi-inbox"></i> No subcategories found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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
            const tbody = document.getElementById('subcategoriesTableBody');
            const filterSectionEl = document.getElementById('filterSection');
            const filterCategoryEl = document.getElementById('filterCategory');

            if (!tbody || !filterSectionEl || !filterCategoryEl) return;

            window.applyFilters = function() {
                const selectedSection = filterSectionEl.value;
                const selectedCategory = filterCategoryEl.value;
                const rows = Array.from(tbody.querySelectorAll('tr[data-subcategory-row]'));

                rows.forEach((row) => {
                    const rowSectionId = row.getAttribute('data-section-id');
                    const rowCategoryId = row.getAttribute('data-category-id');

                    const sectionMatch = selectedSection === 'all' || rowSectionId === selectedSection;
                    const categoryMatch = selectedCategory === 'all' || rowCategoryId === selectedCategory;

                    row.style.display = (sectionMatch && categoryMatch) ? '' : 'none';
                });

                refreshOrderBadges();
            };

            function visibleRows() {
                return Array.from(tbody.querySelectorAll('tr[data-subcategory-row]')).filter(
                    r => r.style.display !== 'none'
                );
            }

            function refreshOrderBadges() {
                const rows = visibleRows();
                rows.forEach((r, i) => {
                    const badge = r.querySelector('[data-display-order]');
                    if (badge) badge.textContent = String(i + 1);
                });
            }

            // Update category filter based on section selection
            filterSectionEl.addEventListener('change', function() {
                const selectedSection = this.value;

                // Reset category filter if section changes
                if (selectedSection !== 'all') {
                    filterCategoryEl.value = 'all';
                }
            });
        })();
    </script>
@endsection
