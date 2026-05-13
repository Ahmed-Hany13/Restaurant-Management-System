@extends('layouts.app')

@section('title', 'Categories')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Categories</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Categories</li>
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
                                <h3 class="card-title">Manage Categories</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>

                            @include('components.session-messages')

                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <div>
                                        <h5 class="mb-1">All Categories</h5>
                                    </div>

                                    <a href="{{ route('category.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Create Category
                                    </a>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label for="filterSection" class="form-label">Filter by section</label>
                                        <select id="filterSection" class="form-select" onchange="filterCategories()">
                                            <option value="all" selected>All sections</option>
                                            {{-- Call every section once (UI-only dropdown) --}}
                                            @foreach ($categories->pluck('menu_section_id')->unique()->values() as $sectionId)
                                                @php
                                                    $sectionName =
                                                        optional($categories->firstWhere('menu_section_id', $sectionId))
                                                            ->menuSection->name ?? $sectionId;
                                                @endphp
                                                <option value="{{ $sectionId }}">{{ $sectionName }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 180px;">Actions</th>
                                                <th style="width: 240px;">Name</th>
                                                <th style="width: 200px;">Section</th>
                                                <th>Description</th>
                                                <th style="width: 160px;">Display order</th>
                                                <th style="width: 140px;">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="categoriesTableBody">
                                            @foreach ($categories as $category)
                                                <tr data-category-row data-section-id="{{ $category->menu_section_id }}">
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            <a href="{{ route('category.show', $category->id) }}"
                                                                class="btn btn-sm btn-outline-secondary mt-1"
                                                                title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </a>

                                                            <a href="{{ route('category.edit', $category->id) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                                                    title="Delete" onclick="return confirm('Are you sure you want to delete this section?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>

                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="fw-semibold">{{ $category->name }}</div>
                                                    </td>
                                                    <td><span
                                                            class="badge text-bg-light">{{ $category->menuSection->name ?? '--' }}</span>
                                                    </td>
                                                    <td class="text-muted">{{ $category->description ?? '--' }}</td>
                                                    <td><span class="badge text-bg-light"
                                                            data-display-order>{{ $category->display_order }}</span></td>
                                                    <td><span class="badge bg-success"
                                                            data-status>{{ $category->status }}</span></td>
                                                </tr>
                                            @endforeach
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
            const tbody = document.getElementById('categoriesTableBody');
            const filterEl = document.getElementById('filterSection');
            if (!tbody || !filterEl) return;

            window.filterCategories = function() {
                const selected = filterEl.value;
                const rows = Array.from(tbody.querySelectorAll('tr[data-category-row]'));

                rows.forEach((row) => {
                    const sectionId = String(row.getAttribute('data-section-id'));
                    const shouldShow = selected === 'all' || sectionId === selected;
                    row.style.display = shouldShow ? '' : 'none';
                });

                refreshOrderBadges();
            };

            function visibleRows() {
                return Array.from(tbody.querySelectorAll('tr[data-category-row]')).filter(r => r.style.display !==
                    'none');
            }

            function refreshOrderBadges() {
                const rows = visibleRows();
                rows.forEach((r, i) => {
                    const badge = r.querySelector('[data-display-order]');
                    if (badge) badge.textContent = String(i + 1);
                });
            }

            function moveRow(row, direction) {
                const rows = visibleRows();
                const idx = rows.indexOf(row);
                if (idx === -1) return;

                const targetIdx = idx + direction;
                if (targetIdx < 0 || targetIdx >= rows.length) return;

                const targetRow = rows[targetIdx];
                if (direction < 0) {
                    tbody.insertBefore(row, targetRow);
                } else {
                    tbody.insertBefore(targetRow, row);
                }

                refreshOrderBadges();
            }

            tbody.addEventListener('click', (e) => {
                const upBtn = e.target.closest('[data-move-up]');
                const downBtn = e.target.closest('[data-move-down]');
                if (upBtn || downBtn) {
                    const row = e.target.closest('tr[data-category-row]');
                    if (!row) return;
                    if (upBtn) moveRow(row, -1);
                    if (downBtn) moveRow(row, 1);
                }

                const editBtn = e.target.closest('[data-edit]');
                if (editBtn) {
                    e.preventDefault();
                    alert('Edit is UI only (no backend).');
                }

                const deleteBtn = e.target.closest('[data-delete]');
                if (deleteBtn) {
                    const ok = confirm('Delete this category? (UI only)');
                    if (!ok) return;
                    const row = deleteBtn.closest('tr[data-category-row]');
                    if (row) row.remove();
                    refreshOrderBadges();
                }
            });

            // initial refresh
            refreshOrderBadges();
        })();
    </script>
@endsection
