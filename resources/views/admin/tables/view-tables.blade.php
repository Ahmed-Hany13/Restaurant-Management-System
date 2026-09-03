@extends('layouts.app')

@section('title', 'Tables')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Tables</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tables</li>
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
                                <h3 class="card-title">Manage Tables</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>

                            @include('components.session-messages')

                            <div class="card-body">

                                {{-- Header --}}
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-4">
                                    <h5 class="mb-0">All Tables
                                        <span class="badge bg-secondary ms-1" id="visibleCount">{{ count($tables) }}</span>
                                    </h5>
                                    <a href="{{ route('table.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-lg me-1"></i> Add Table
                                    </a>
                                </div>

                                {{-- Filters --}}
                                <div class="card bg-light mb-4">
                                    <div class="card-body p-3">
                                        <div class="row g-3 align-items-end">

                                            {{-- Search --}}
                                            <div class="col-lg-4">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                    <input type="text" id="searchInput" class="form-control"
                                                        placeholder="Search by table number..." autocomplete="off">
                                                </div>
                                            </div>

                                            {{-- Type Filter --}}
                                            <div class="col-lg-2">
                                                <select id="filterType" class="form-select">
                                                    <option value="">All Types</option>
                                                    <option value="private">Private</option>
                                                    <option value="public">Public</option>
                                                </select>
                                            </div>

                                            {{-- Status Filter --}}
                                            <div class="col-lg-2">
                                                <select id="filterStatus" class="form-select">
                                                    <option value="">All Status</option>
                                                    <option value="available">Available</option>
                                                    <option value="occupied">Occupied</option>
                                                    <option value="reserved">Reserved</option>
                                                    <option value="maintenance">Maintenance</option>
                                                </select>
                                            </div>

                                            {{-- Reset --}}
                                            <div class="col-lg-2">
                                                <button id="resetFilters" class="btn btn-sm btn-outline-secondary w-100">
                                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Filters
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cards Grid --}}
                                <div class="row g-3" id="tablesGrid">
                                    @foreach ($tables as $table)
                                        @php
                                            $statusClass = match($table->status) {
                                                'available'   => 'bg-success',
                                                'occupied'    => 'bg-danger',
                                                'reserved'    => 'bg-warning text-dark',
                                                'maintenance' => 'bg-secondary',
                                                default       => 'bg-light text-dark',
                                            };
                                            $statusDot = match($table->status) {
                                                'available'   => 'text-success',
                                                'occupied'    => 'text-danger',
                                                'reserved'    => 'text-warning',
                                                'maintenance' => 'text-secondary',
                                                default       => 'text-muted',
                                            };
                                        @endphp

                                        <div class="col-xl-3 col-lg-4 col-md-6 table-card-col"
                                            data-number="{{ strtolower($table->table_number) }}"
                                            data-type="{{ $table->type }}"
                                            data-status="{{ $table->status }}">

                                            <div class="card h-100 shadow-sm border-0 table-card">

                                                {{-- Card Top Bar --}}
                                                <div class="card-header d-flex align-items-center justify-content-between py-2 px-3
                                                    @if($table->type === 'private') bg-primary text-white @else bg-info text-white @endif">
                                                    <span class="fw-bold fs-6">
                                                        <i class="bi bi-table me-1"></i>
                                                        {{ $table->table_number }}
                                                    </span>
                                                    <span class="badge bg-white
                                                        @if($table->type === 'private') text-primary @else text-info @endif
                                                        fw-semibold">
                                                        {{ ucfirst($table->type) }}
                                                    </span>
                                                </div>

                                                <div class="card-body px-3 py-3">

                                                    {{-- Status Row --}}
                                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                                        <div class="d-flex align-items-center gap-1">
                                                            <i class="bi bi-circle-fill {{ $statusDot }}" style="font-size: 0.55rem;"></i>
                                                            <span class="badge {{ $statusClass }} fw-normal">
                                                                {{ ucfirst($table->status) }}
                                                            </span>
                                                        </div>

                                                        {{-- QR Code Icon --}}
                                                        {{-- <a href="{{ route('QrCode', [$table->id, $table->unique_token]) }}"
                                                            class="btn btn-sm btn-outline-secondary px-2 py-1"
                                                            title="View QR Code">
                                                            <i class="bi bi-qr-code" style="font-size: 1rem;"></i>
                                                        </a> --}}
                                                    </div>

                                                    {{-- Capacity --}}
                                                    <div class="d-flex align-items-center gap-2 mb-3">
                                                        <i class="bi bi-people text-muted"></i>
                                                        <div>
                                                            <small class="text-muted d-block" style="line-height:1.2;">Capacity</small>
                                                            <span class="fw-semibold">
                                                                {{ $table->min_capacity }}
                                                                <span class="text-muted fw-normal">–</span>
                                                                {{ $table->max_capacity }}
                                                                <small class="text-muted fw-normal">guests</small>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    {{-- Location --}}
                                                    @if($table->location)
                                                        <div class="d-flex align-items-center gap-2 mb-3">
                                                            <i class="bi bi-geo-alt text-muted"></i>
                                                            <div>
                                                                <small class="text-muted d-block" style="line-height:1.2;">Location</small>
                                                                <span class="fw-semibold">{{ $table->location }}</span>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- Notes --}}
                                                    @if($table->notes)
                                                        <p class="text-muted small mb-0 border-top pt-2 mt-2">
                                                            <i class="bi bi-sticky me-1"></i>{{ $table->notes }}
                                                        </p>
                                                    @endif
                                                </div>

                                                {{-- Actions Footer --}}
                                                <div class="card-footer bg-transparent d-flex gap-2 py-2 px-3">
                                                    <a href="{{ route('table.show', $table->id) }}"
                                                        class="btn btn-sm btn-outline-secondary flex-fill" title="View">
                                                        <i class="bi bi-eye me-1"></i> View
                                                    </a>
                                                    <a href="{{ route('table.edit', $table->id) }}"
                                                        class="btn btn-sm btn-outline-primary flex-fill" title="Edit">
                                                        <i class="bi bi-pencil me-1"></i> Edit
                                                    </a>
                                                    <form action="{{ route('table.destroy', $table->id) }}"
                                                        method="POST" class="flex-fill">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger w-100"
                                                            title="Delete"
                                                            onclick="return confirm('Are you sure you want to delete this table?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Empty state --}}
                                @if(count($tables) === 0)
                                    <div class="alert alert-info text-center mt-3">
                                        <i class="bi bi-info-circle me-2"></i> No tables found.
                                    </div>
                                @endif

                                {{-- No results from filter --}}
                                <div id="noResults" class="alert alert-warning text-center mt-3 d-none">
                                    <i class="bi bi-funnel me-2"></i> No tables match the current filters.
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
        (function () {
            const searchInput  = document.getElementById('searchInput');
            const filterType   = document.getElementById('filterType');
            const filterStatus = document.getElementById('filterStatus');
            const resetBtn     = document.getElementById('resetFilters');
            const grid         = document.getElementById('tablesGrid');
            const noResults    = document.getElementById('noResults');
            const countBadge   = document.getElementById('visibleCount');

            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const type       = filterType.value;
                const status     = filterStatus.value;

                const cards = grid.querySelectorAll('.table-card-col');
                let visible = 0;

                cards.forEach(col => {
                    const matchesSearch = col.getAttribute('data-number').includes(searchTerm);
                    const matchesType   = type   === '' || col.getAttribute('data-type')   === type;
                    const matchesStatus = status === '' || col.getAttribute('data-status') === status;
                    const show = matchesSearch && matchesType && matchesStatus;

                    col.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                countBadge.textContent = visible;
                noResults.classList.toggle('d-none', visible > 0);
            }

            function resetFilters() {
                searchInput.value  = '';
                filterType.value   = '';
                filterStatus.value = '';
                applyFilters();
            }

            searchInput?.addEventListener('input',  applyFilters);
            filterType?.addEventListener('change',  applyFilters);
            filterStatus?.addEventListener('change', applyFilters);
            resetBtn?.addEventListener('click', resetFilters);
        })();
    </script>

    <style>
        .table-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12) !important;
        }
        .table-card .card-header {
            border-radius: 0 !important;
        }
    </style>
@endsection
