@extends('layouts.app')

@section('title', 'Reservations')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Reservations</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reservations</li>
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
                            <div class="card-header d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h3 class="card-title mb-0">Reservations List</h3>

                                </div>
                            </div>

                            @include('components.session-messages')

                            <div class="card-body">
                                <div class="card bg-light mb-4">
                                    <div class="card-body p-3">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-lg-4">
                                                <label for="searchInput" class="form-label visually-hidden">Search</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                    <input type="text" id="searchInput" class="form-control"
                                                        placeholder="Search by name, phone, reservation number"
                                                        autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-lg-2">
                                                <label for="date_filter" class="form-label visually-hidden">Date</label>
                                                <select id="date_filter" class="form-select">
                                                    <option value="all">Date: All</option>
                                                    <option value="today">Today</option>
                                                    <option value="tomorrow">Tomorrow</option>
                                                    <option value="this_week">This Week</option>
                                                    <option value="custom">Custom Date</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-2">
                                                <label for="status" class="form-label visually-hidden">Status</label>
                                                <select id="status" class="form-select">
                                                    @foreach ($statusOptions as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-2">
                                                <label for="type" class="form-label visually-hidden">Type</label>
                                                <select id="type" class="form-select">
                                                    @foreach ($typeOptions as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-12 d-flex gap-2">
                                                <button id="applyFilters" type="button" class="btn btn-primary">Apply
                                                    filters</button>
                                                <button id="resetFilters" type="button"
                                                    class="btn btn-outline-secondary">Reset</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Reservation No.</th>
                                                    <th>Customer</th>
                                                    <th>Phone</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Guests</th>
                                                    <th>Table</th>
                                                    <th>Status</th>
                                                    <th>Type</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($reservations as $reservation)
                                                    <tr class="reservation-row"
                                                        data-reservation-id="{{ $reservation->id }}"
                                                        data-reservation-date="{{ $reservation->reservation_date?->format('Y-m-d') ?? $reservation->created_at->format('Y-m-d') }}"
                                                        data-reservation-time="{{ $reservation->reservation_time ?? $reservation->created_at->format('H:i') }}"
                                                        data-reservation-table="{{ $reservation->table_id ?? '' }}"
                                                        data-status="{{ strtolower($reservation->status) }}"
                                                        data-type="{{ $reservation->reservation_type }}">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $reservation->reservation_number }}</td>
                                                        <td>{{ $reservation->customer_name }}</td>
                                                        <td>{{ $reservation->phone }}</td>
                                                        <td>{{ $reservation->reservation_date?->format('Y-m-d') ?? $reservation->created_at->format('Y-m-d') }}
                                                        </td>
                                                        <td>{{ $reservation->reservation_time ?? $reservation->created_at->format('H:i') }}
                                                        </td>
                                                        <td>{{ $reservation->guest_count }}</td>
                                                        <td>{{ $reservation->table?->table_number ?? 'N/A' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                                                        </td>
                                                        <td>{{ $reservation->reservation_type === 'now' ? 'Walk-in / Immediate' : 'Scheduled' }}
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm" role="group"
                                                                aria-label="Reservation actions">
                                                                <a href="{{ route('reservations.show', $reservation) }}"
                                                                    class="btn btn-outline-secondary" title="View details">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                                <form method="POST"
                                                                    action="{{ route('reservations.arrive', $reservation) }}"
                                                                    class="d-inline">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="btn btn-outline-success"
                                                                        title="Mark as arrived">
                                                                        <i class="bi bi-person-check"></i>
                                                                    </button>
                                                                </form>
                                                                <button type="button"
                                                                    class="btn btn-outline-primary action-edit"
                                                                    data-id="{{ $reservation->id }}"
                                                                    title="Modify reservation">
                                                                    <i class="bi bi-pencil"></i>
                                                                </button>
                                                                <form method="POST"
                                                                    action="{{ route('reservations.cancel', $reservation) }}"
                                                                    class="d-inline"
                                                                    onsubmit="return confirm('Cancel this reservation?');">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="btn btn-outline-danger"
                                                                        title="Cancel reservation">
                                                                        <i class="bi bi-x-circle"></i>
                                                                    </button>
                                                                </form>
                                                                <form method="POST"
                                                                    action="{{ route('reservations.no_show', $reservation) }}"
                                                                    class="d-inline"
                                                                    onsubmit="return confirm('Mark this reservation as no-show?');">
                                                                    @csrf
                                                                    <button type="submit"
                                                                        class="btn btn-outline-warning"
                                                                        title="Mark no-show">
                                                                        <i class="bi bi-slash-circle"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="no-data-row">
                                                        <td colspan="11" class="text-center text-muted">No reservations
                                                            found.</td>
                                                    </tr>
                                                @endforelse
                                                <tr class="no-match-row d-none">
                                                    <td colspan="11" class="text-center text-muted">No matching
                                                        reservations found.</td>
                                                </tr>
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

    <div class="modal fade" id="modifyReservationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modify Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modifyReservationForm" method="POST" action="">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="modifyReservationId">
                        <div class="mb-3">
                            <label for="modifyReservationDate" class="form-label">Date</label>
                            <input type="date" id="modifyReservationDate" name="reservation_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modifyReservationTime" class="form-label">Time</label>
                            <input type="time" id="modifyReservationTime" name="reservation_time" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="modifyReservationTable" class="form-label">Table</label>
                            <select id="modifyReservationTable" name="table_id" class="form-select">
                                @foreach ($tables as $table)
                                    <option value="{{ $table->id }}">{{ $table->table_number }}
                                        ({{ ucfirst($table->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="modifyReservationForm" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const dateFilter = document.getElementById('date_filter');
            const customDate = document.getElementById('custom_date');
            const statusFilter = document.getElementById('status');
            const typeFilter = document.getElementById('type');
            const applyFilters = document.getElementById('applyFilters');
            const resetFilters = document.getElementById('resetFilters');
            const reservationRows = Array.from(document.querySelectorAll('tbody tr.reservation-row'));
            const noMatchRow = document.querySelector('tbody tr.no-match-row');

            function matchesDate(rowDate, filter, custom) {
                if (filter === 'all') return true;
                const row = new Date(rowDate);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (filter === 'today') {
                    return row.getTime() === today.getTime();
                }

                if (filter === 'tomorrow') {
                    const tomorrow = new Date(today);
                    tomorrow.setDate(today.getDate() + 1);
                    return row.getTime() === tomorrow.getTime();
                }

                if (filter === 'this_week') {
                    const weekEnd = new Date(today);
                    weekEnd.setDate(today.getDate() + (7 - today.getDay()));
                    return row >= today && row <= weekEnd;
                }

                if (filter === 'custom' && custom) {
                    const matchDate = new Date(custom);
                    return row.getTime() === matchDate.getTime();
                }

                return true;
            }

            function filterReservations() {
                const query = searchInput.value.trim().toLowerCase();
                const dateValue = dateFilter.value;
                const customValue = customDate.value;
                const statusValue = statusFilter.value;
                const typeValue = typeFilter.value;
                let visibleCount = 0;

                reservationRows.forEach((row) => {
                    const rowText = row.innerText.trim().toLowerCase();
                    const rowDate = row.dataset.reservationDate;
                    const rowStatus = row.dataset.status;
                    const rowType = row.dataset.type;

                    const matchesSearch = query === '' || rowText.includes(query);
                    const matchesStatus = statusValue === 'all' || rowStatus === statusValue;
                    const matchesType = typeValue === 'all' || rowType === typeValue;
                    const matchesDateValue = matchesDate(rowDate, dateValue, customValue);

                    const show = matchesSearch && matchesStatus && matchesType && matchesDateValue;
                    row.style.display = show ? '' : 'none';
                    if (show) visibleCount += 1;
                });

                if (noMatchRow) {
                    noMatchRow.classList.toggle('d-none', visibleCount > 0);
                }
            }

            const actionEditButtons = Array.from(document.querySelectorAll('.action-edit'));
            const modifyReservationModalEl = document.getElementById('modifyReservationModal');
            const modifyReservationModal = new bootstrap.Modal(modifyReservationModalEl);
            const modifyReservationForm = document.getElementById('modifyReservationForm');
            const modifyReservationId = document.getElementById('modifyReservationId');
            const modifyReservationDate = document.getElementById('modifyReservationDate');
            const modifyReservationTime = document.getElementById('modifyReservationTime');
            const modifyReservationTable = document.getElementById('modifyReservationTable');
            const modifyUrlBase = '{{ url('/reservations') }}';

            function getReservationRow(id) {
                return document.querySelector(`tr.reservation-row[data-reservation-id="${id}"]`);
            }

            actionEditButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    if (!id) return;
                    const row = getReservationRow(id);
                    if (!row) return;
                    modifyReservationId.value = id;
                    modifyReservationDate.value = row.dataset.reservationDate || '';
                    modifyReservationTime.value = row.dataset.reservationTime || '';
                    modifyReservationTable.value = row.dataset.reservationTable || '';
                    modifyReservationForm.action = `${modifyUrlBase}/${id}`;
                    modifyReservationModal.show();
                });
            });

            searchInput.addEventListener('input', filterReservations);
            applyFilters.addEventListener('click', filterReservations);
            resetFilters.addEventListener('click', function() {
                searchInput.value = '';
                dateFilter.value = 'all';
                customDate.value = '';
                statusFilter.value = 'all';
                typeFilter.value = 'all';
                filterReservations();
            });

            filterReservations();
        });
    </script>
@endsection
