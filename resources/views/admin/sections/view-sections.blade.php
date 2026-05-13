@extends('layouts.app')

@section('title', 'Sections')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Sections</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sections</li>
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
                                <h3 class="card-title">Manage Sections</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>

                            @include('components.session-messages')

                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <div>
                                        <h5 class="mb-1">All Sections</h5>
                                    </div>

                                    <a href="{{ route('section.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Create Section
                                    </a>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 160px;">Actions</th>
                                                <th style="width: 240px;">Name</th>
                                                <th>Description</th>
                                                <th style="width: 180px;">Display order</th>
                                                <th style="width: 160px;">Status</th>
                                            </tr>
                                        </thead>

                                        {{-- UI-only mock data. Replace with backend data when available. --}}
                                        <tbody id="sectionsTableBody">
                                            @foreach ($sections as $section)
                                                <tr data-section-row>
                                                    <td>
                                                        <div class="d-flex flex-column gap-1">
                                                            <a href="{{ route('section.show',$section->id) }}" class="btn btn-sm btn-outline-secondary mt-1" title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            
                                                            <a href="{{ route('section.edit',$section->id) }}" class="btn btn-sm btn-outline-primary mt-1" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>



                                                            <form action="{{ route('section.destroy', $section->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete" onclick="return confirm('Are you sure you want to delete this section?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>





                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="fw-semibold">{{ $section->name }}</div>
                                                    </td>
                                                    <td class="text-muted">{{ $section->description ?? '--' }}</td>
                                                    <td><span class="badge text-bg-light" data-display-order>{{ $section->display_order }}</span></td>
                                                    <td><span class="badge bg-success" data-status>{{ $section->status }}</span></td>
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
            const tbody = document.getElementById('sectionsTableBody');
            if (!tbody) return;

            function visibleRows() {
                return Array.from(tbody.querySelectorAll('tr[data-section-row]'));
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
                    const row = e.target.closest('tr[data-section-row]');
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
                    const ok = confirm('Delete this section? (UI only)');
                    if (!ok) return;

                    const row = deleteBtn.closest('tr[data-section-row]');
                    if (row) row.remove();
                    refreshOrderBadges();
                }

            });

            refreshOrderBadges();
        })();
    </script>
@endsection
