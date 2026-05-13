@extends('layouts.app')

@section('title', 'Create Section')

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
                            <li class="breadcrumb-item active" aria-current="page">Create Section</li>
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
                                <h3 class="card-title">Create / Manage Sections</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>
                            @include('components.session-messages')
                            <div class="card-body">
                                <div class="row g-4">
                                    {{-- Create form --}}
                                    <div class="col-lg-5">
                                        <div class="mb-3">
                                            <h5 class="mb-1">New Section</h5>
                                        </div>
                                        @if ($errors->any())
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <div class="fw-semibold">Please fix the following:</div>
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                        @endif
                                        <form method="POST" action="{{ route('section.store') }}" class="needs-validation"
                                            novalidate>
                                            @csrf

                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                                    <input id="name" type="text" name="name"
                                                        value="{{ old('name') }}" autofocus
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        autocomplete="off" required>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea id="description" name="description" rows="3"
                                                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="display_order" class="form-label">Display order</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i
                                                                class="bi bi-sort-numeric-up"></i></span>
                                                        <input id="display_order" type="number" name="display_order"
                                                            value="{{ old('display_order') }}"
                                                            class="form-control @error('display_order') is-invalid @enderror"
                                                            min="0">
                                                    </div>

                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select id="status" name="status"
                                                        class="form-select @error('status') is-invalid @enderror">
                                                        <option value="" disabled
                                                            {{ old('status') ? '' : 'selected' }}>Select status</option>
                                                        <option value="active"
                                                            {{ old('status') === 'active' ? 'selected' : '' }}>Active
                                                        </option>
                                                        <option value="inactive"
                                                            {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between gap-2 mt-4">
                                                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                                    <i class="bi bi-arrow-left-circle me-1"></i> Back
                                                </a>

                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-plus-lg me-1"></i> Create Section
                                                </button>
                                            </div>
                                        </form>
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
            const tbody = document.getElementById('sectionsTableBody');
            if (!tbody) return;

            function moveRow(row, direction) {
                const rows = Array.from(tbody.querySelectorAll('tr[data-section-row]'));
                const idx = rows.indexOf(row);
                if (idx === -1) return;

                const targetIdx = idx + direction;
                if (targetIdx < 0 || targetIdx >= rows.length) return;

                // Swap in DOM
                const targetRow = rows[targetIdx];
                if (direction < 0) {
                    tbody.insertBefore(row, targetRow);
                } else {
                    tbody.insertBefore(targetRow, row);
                }

                // Refresh display_order badges to reflect order (UI-only)
                Array.from(tbody.querySelectorAll('tr[data-section-row]')).forEach((r, i) => {
                    const badge = r.querySelector('[data-display-order]');
                    if (badge) badge.textContent = String(i + 1);
                });
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

                const deleteBtn = e.target.closest('[data-delete]');
                if (deleteBtn) {
                    // UI-only: show message and don't actually delete.
                    if (deleteBtn.disabled) {
                        alert('Cannot delete this section because it contains categories.');
                        return;
                    }
                    const ok = confirm('Delete this section? (UI only)');
                    if (ok) {
                        const row = deleteBtn.closest('tr[data-section-row]');
                        if (row) {
                            row.remove();
                            // Refresh display_order badges
                            Array.from(tbody.querySelectorAll('tr[data-section-row]')).forEach((r, i) => {
                                const badge = r.querySelector('[data-display-order]');
                                if (badge) badge.textContent = String(i + 1);
                            });
                        }
                    }
                }
            });
        })();
    </script>
@endsection
