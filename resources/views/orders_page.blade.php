<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Orders</title>

    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f6f7fb;
        }

        .rm-topbar {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
        }

        .table-card {
            cursor: pointer;
            user-select: none;
            transition: transform .05s ease-in-out, box-shadow .2s ease-in-out, border-color .2s ease-in-out;
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .table-card:hover {
            transform: translateY(-1px);
        }

        .table-card.active {
            box-shadow: 0 0 0 .2rem rgba(13, 110, 253, .15);
            border-color: rgba(13, 110, 253, .65) !important;
        }

        .badge-soft {
            font-weight: 600;
        }

        .section-title {
            letter-spacing: -0.01em;
        }

        .rm-panel {
            background: white;
            border: 1px solid rgba(0, 0, 0, .06);
            border-radius: 14px;
        }

        .rm-panel .rm-panel-head {
            border-bottom: 1px solid rgba(0, 0, 0, .05);
            padding: 14px 16px;
        }

        .rm-panel .rm-panel-body {
            padding: 16px;
        }

        .qty-pill {
            min-width: 44px;
        }

        .small-muted {
            color: rgba(0, 0, 0, .55);
        }

        .list-row {
            border-bottom: 1px dashed rgba(0, 0, 0, .08);
            padding: 10px 0;
        }

        .list-row:last-child {
            border-bottom: 0;
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
        }
    </style>
</head>

<body>

    <div class="container-fluid px-4 py-3">
        <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap mb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                    style="width:40px;height:40px;">
                    <i class="bi bi-receipt" style="font-size:1.1rem"></i>
                </div>
                <div>
                    <div class="fw-bold">Waiter Dashboard</div>
                    <div class="small-muted small">Available tables • my active orders • quick actions</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-house me-1"></i> Home
                    </a>
                    <a href="{{ route('billing_page') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-cash-coin me-1"></i> Billings
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        @php
                            $btnClass = 'btn btn-sm p-0';
                        @endphp
                        @include('_logout_beautiful_snippet', ['class' => ''])
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const forms = document.querySelectorAll('form[action="{{ route('logout') }}"]');
                            forms.forEach((f) => {
                                f.addEventListener('submit', (e) => {
                                    const btn = f.querySelector('[data-logout-submit]');
                                    if (!btn) return;
                                    e.preventDefault();
                                    btn.classList.add('rm-logout-busy');
                                    // small delay so the spinner is visible
                                    setTimeout(() => f.submit(), 200);
                                });
                            });
                        });
                    </script>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Log in
                    </a>
                @endauth
            </div>
        </div>

        <!-- Quick actions (UI-only) -->
        <div class="d-flex gap-2 flex-wrap mt-3">
            <button class="btn btn-primary btn-sm" type="button" id="qaNewOrder">
                <i class="bi bi-plus-circle me-1"></i> New Order
            </button>
            <button class="btn btn-outline-secondary btn-sm" type="button" id="qaViewTables">
                <i class="bi bi-grid-3x3-gap me-1"></i> View Tables
            </button>
            <button class="btn btn-outline-secondary btn-sm" type="button" id="qaViewReservations">
                <i class="bi bi-calendar-event me-1"></i> View Reservations
            </button>
        </div>
    </div>

    @include('components.session-messages')

    <form id="orderForm" action="{{ route('orders.store') }}" method="POST" style="display:none;">
        @csrf
        <input type="hidden" id="orderFormCustomerName" name="customer_name" value="" />
        <input type="hidden" id="orderFormCustomerPhone" name="phone" value="" />
        <input type="hidden" id="orderFormGuestCount" name="guest_count" value="1" />
        <input type="hidden" id="orderFormTableId" name="table_id" value="" />
        <input type="hidden" id="orderFormOrderItems" name="order_items" value="" />
    </form>

    <div class="container py-4">
        <div id="tablesSection"></div>
        <!-- TABLES -->
        <div class="row g-3">
            <div class="col-12 col-xl-4">
                <div class="rm-panel h-100">
                    <div class="rm-panel-head d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="section-title fw-bold fs-5">Tables</div>
                            <div class="small-muted small">Tap a table to work with it.</div>
                        </div>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge rounded-pill text-bg-success badge-soft" title="Available">
                                <i class="bi bi-check2-circle me-1"></i> Available
                            </span>
                        </div>
                    </div>

                    <div class="rm-panel-body">
                        <div id="tablesGrid" class="tables-grid">
                            @foreach ($tables as $table)
                                <div class="table-card p-2 border rounded-3">
                                    <div class="fw-bold mb-2">{{ $table->table_number }}</div>
                                    <div>
                                        @if ($table->status === 'available')
                                            <span class="badge rounded-pill text-bg-success badge-soft"
                                                title="Available"><i class="bi bi-check2-circle me-1"></i>
                                                Available</span>
                                        @elseif ($table->status === 'reserved')
                                            <span class="badge rounded-pill text-bg-primary badge-soft"
                                                title="Reserved"><i class="bi bi-clock me-1"></i> Reserved</span>
                                        @else
                                            <span class="badge rounded-pill text-bg-warning text-dark badge-soft"
                                                title="Occupied"><i class="bi bi-fire me-1"></i> Occupied</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr />
                        <div class="d-grid gap-2">
                            <button id="btnMarkAvailable" class="btn btn-outline-success">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Mark Selected Available
                            </button>
                        </div>
                        <div id="selectedTableHint" class="small-muted small mt-2">
                            Selected table: <span class="mono" id="selectedTableLabel">None</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CREATE ORDER + RESERVATIONS -->
            <div class="col-12 col-xl-8">
                <div class="row g-3">

                    <div id="createOrderSection"></div>
                    <!-- CREATE ORDER -->
                    <div class="col-12">
                        <div class="rm-panel">
                            <div class="rm-panel-head d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <div class="section-title fw-bold fs-5">Create Order</div>
                                    <div class="small-muted small">Choose table, browse items, then place the order.
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold mono" style="font-size:1.05rem;">Total: <span
                                            id="orderTotal">0.00</span></div>
                                </div>
                            </div>

                            <div class="rm-panel-body">
                                <div class="row g-3">
                                    <div class="col-12 col-xl-4">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Table</label>
                                                <select id="orderTableSelect" class="form-select"
                                                    aria-label="Select table for order">
                                                    @foreach ($tables as $table)
                                                        @if ($table->status !== 'occupied')
                                                            <option value="{{ $table->id }}">
                                                                {{ $table->table_number }}
                                                                ({{ ucfirst($table->status) }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Customer Name</label>
                                                <input id="orderCustomerName" class="form-control" type="text"
                                                    placeholder="e.g. Ahmed" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Phone</label>
                                                <input id="orderCustomerPhone" class="form-control" type="tel"
                                                    placeholder="e.g. 010xxxxxxx" maxlength="10" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Number of Guests</label>
                                                <input id="orderGuestCount" class="form-control" type="number"
                                                    min="1" step="1" value="2" />
                                            </div>

                                            <div class="col-12">
                                                <div class="card border-light shadow-sm">
                                                    <div class="card-header py-2">Sections</div>
                                                    <div class="list-group list-group-flush" id="sectionsList"></div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="card border-light shadow-sm">
                                                    <div class="card-header py-2">Categories</div>
                                                    <div class="list-group list-group-flush" id="categoriesList">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="card border-light shadow-sm">
                                                    <div class="card-header py-2">Subcategories</div>
                                                    <div class="list-group list-group-flush" id="subcategoriesList">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-xl-8">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <div class="fw-semibold">Menu Items</div>
                                                <div class="small-muted small">Browse items by section and category.
                                                </div>
                                            </div>
                                            <div class="small-muted small">Showing <span id="itemsCount">0</span>
                                            </div>
                                        </div>
                                        <div id="itemsGrid" class="row g-3"></div>
                                    </div>

                                    <div class="modal fade" id="itemModal" tabindex="-1"
                                        aria-labelledby="itemModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div>
                                                        <h5 class="modal-title" id="itemModalLabel">Menu item</h5>
                                                        <div class="small-muted small" id="itemModalBadge"></div>
                                                    </div>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-12 col-md-5">
                                                            <img id="itemModalImage" src="" alt="Item image"
                                                                class="img-fluid rounded"
                                                                style="width:100%; height:220px; object-fit:cover;" />
                                                        </div>
                                                        <div class="col-12 col-md-7">
                                                            <div class="mb-2">
                                                                <div class="fw-semibold fs-5" id="itemModalName">Item
                                                                    name</div>
                                                                <div class="small-muted" id="itemModalAvailability">
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-3">
                                                                <div class="mono fw-semibold fs-4"
                                                                    id="itemModalPrice">0.00</div>
                                                                <div class="small-muted" id="itemModalPrepTime"></div>
                                                            </div>
                                                            <div class="small text-muted mb-3"
                                                                id="itemModalDescription"></div>
                                                            <div class="row g-2 mb-3">
                                                                <div class="col-12 col-sm-6">
                                                                    <label
                                                                        class="form-label fw-semibold">Quantity</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <button class="btn btn-outline-secondary"
                                                                            type="button" id="itemModalQtyMinus"><i
                                                                                class="bi bi-dash"></i></button>
                                                                        <input id="itemModalQty" type="number"
                                                                            class="form-control text-center"
                                                                            value="1" min="1"
                                                                            step="1" />
                                                                        <button class="btn btn-outline-secondary"
                                                                            type="button" id="itemModalQtyPlus"><i
                                                                                class="bi bi-plus"></i></button>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <label class="form-label fw-semibold">Special
                                                                        Instructions</label>
                                                                    <textarea id="itemModalInstructions" class="form-control" rows="3" placeholder="Add notes for kitchen..."></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-primary"
                                                        id="itemModalAddButton">
                                                        <i class="bi bi-plus-circle me-1"></i> Add to Order
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-7">
                                        <div class="fw-semibold mb-2"><i class="bi bi-list-ul me-1"></i> Order Items
                                            (Draft)</div>
                                        <div class="rm-panel" style="border-style:dashed;">
                                            <div class="rm-panel-body">
                                                <div id="draftItemsEmpty" class="small-muted small">No items yet. Add
                                                    from the menu below.</div>
                                                <div id="draftItemsList" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <div class="fw-semibold mb-2"><i class="bi bi-credit-card me-1"></i> Order
                                            Summary
                                        </div>
                                        <div class="rm-panel" style="border-style:dashed;">
                                            <div class="rm-panel-body">
                                                <div class="d-flex justify-content-between">
                                                    <div class="small-muted">Subtotal</div>
                                                    <div class="mono fw-semibold" id="orderSubtotal">0.00</div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-2">
                                                    <div class="small-muted">Discount</div>
                                                    <div class="mono fw-semibold text-success" id="orderDiscount">0.00
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-2">
                                                    <div class="small-muted">Tax (0%)</div>
                                                    <div class="mono fw-semibold" id="orderTax">0.00</div>
                                                </div>
                                                <hr />
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div>
                                                        <div class="small-muted small">Grand Total</div>
                                                        <div class="fw-bold mono" style="font-size:1.25rem;"
                                                            id="orderTotalBig">0.00</div>
                                                    </div>
                                                </div>
                                                <div class="d-grid gap-2 mt-4">
                                                    <button id="btnCreateOrder" class="btn btn-success">
                                                        <i class="bi bi-receipt-cutoff me-1"></i> Create Order
                                                    </button>
                                                    <button id="btnClearDraft" class="btn btn-outline-danger">
                                                        <i class="bi bi-trash me-1"></i> Clear Draft
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RESERVATIONS + LISTS -->

                    <div class="col-12">
                        <div id="reservationsSection"></div>
                        <div class="row g-3">

                            <div class="col-12 col-lg-5">
                                <div class="rm-panel h-100">
                                    <div class="rm-panel-head">
                                        <div class="fw-bold fs-5"><i class="bi bi-calendar-check me-1"></i> Take
                                            Reservation</div>
                                        <div class="small-muted small">Create a reservation to reserve a table.</div>
                                    </div>
                                    <div class="rm-panel-body">
                                        <form action="{{ route('reservations.store') }}" method="POST">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Reservation Type</label>
                                                    <select id="reservation_type" name="reservation_type"
                                                        class="form-select">
                                                        <option value="now">Order Now (Immediate)</option>
                                                        <option value="scheduled" selected>Schedule for Date & Time
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Customer Name</label>
                                                    <input id="customer_name" name="customer_name"
                                                        class="form-control" type="text"
                                                        placeholder="e.g. Ahmed" />
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Phone</label>
                                                    <input id="phone" name="phone" class="form-control"
                                                        type="tel" placeholder="e.g. 010xxxxxxx" maxlength="10"
                                                        minlength="10" />
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold">Number of Guests</label>
                                                    <input id="guest_count" name="guest_count" class="form-control"
                                                        type="number" min="1" step="1"
                                                        value="2" />
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold">Table Type</label>
                                                    <select id="table_type" name="table_type" class="form-select">
                                                        <option value="">Any</option>
                                                        <option value="private">Private</option>
                                                        <option value="public">Public</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Table</label>
                                                    <select id="table_id" name="table_id" class="form-select"
                                                        aria-label="Select table for reservation">
                                                        <option value="">Auto Assign</option>
                                                        @foreach ($tables as $table)
                                                            @if ($table->status === 'available')
                                                                <option value="{{ $table->id }}">
                                                                    {{ $table->table_number }}
                                                                    ({{ ucfirst($table->type) }})
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div id="scheduledFields" class="row g-3 mt-0">
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold">Reservation Date</label>
                                                        <input id="reservation_date" name="reservation_date"
                                                            class="form-control" type="date" />
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold">Reservation Time</label>
                                                        <input id="reservation_time" name="reservation_time"
                                                            class="form-control" type="time" />
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Estimated
                                                            Duration</label>
                                                        <select id="duration_hours" name="duration_hours"
                                                            class="form-select">
                                                            <option value="1">1 hour</option>
                                                            <option value="1.5">1.5 hours</option>
                                                            <option value="2" selected>2 hours</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Special Occasion</label>
                                                    <select id="special_occasion" name="special_occasion"
                                                        class="form-select">
                                                        <option value="None">None</option>
                                                        <option value="Birthday">Birthday</option>
                                                        <option value="Anniversary">Anniversary</option>
                                                        <option value="Business">Business</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold">Additional Notes
                                                        (optional)</label>
                                                    <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Any special requests..."></textarea>
                                                </div>
                                                <div class="col-12">
                                                    <button id="btnCreateReservation" class="btn btn-primary w-100"
                                                        type="submit">
                                                        <i class="bi bi-person-plus me-1"></i> Create Reservation
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-7">
                                <div class="rm-panel h-100">
                                    <div class="rm-panel-head d-flex align-items-start justify-content-between gap-3">
                                        <div>
                                            <div class="fw-bold fs-5"><i class="bi bi-kanban me-1"></i> Active Orders
                                                & Reservations</div>
                                        </div>
                                    </div>
                                    <div class="rm-panel-body">

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="fw-semibold mb-2"><i class="bi bi-receipt me-1"></i>
                                                    Orders</div>
                                                <div id="ordersList"></div>
                                                <div id="ordersEmpty" class="small-muted small"
                                                    style="display:none;">No orders yet. Create one to see it here.
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <hr />
                                                <div class="fw-semibold mb-2"><i class="bi bi-calendar me-1"></i>
                                                    Reservations</div>
                                                <div id="reservationsList">
                                                    @forelse ($reservations as $reservation)
                                                        <div class="list-row">
                                                            <div
                                                                class="d-flex align-items-start justify-content-between gap-3">
                                                                <div>
                                                                    <div class="fw-semibold">
                                                                        {{ $reservation->reservation_number }} —
                                                                        {{ $reservation->customer_name }}</div>
                                                                    <div class="small-muted small">
                                                                        <span
                                                                            class="badge text-bg-primary">{{ ucfirst($reservation->status) }}</span>
                                                                        • {{ $reservation->guest_count }} guest(s)
                                                                        •
                                                                        {{ $reservation->table?->table_number ?? 'Auto assign' }}
                                                                    </div>
                                                                    <div class="small-muted small mt-1">
                                                                        {{ $reservation->reservation_date ? $reservation->reservation_date->format('M d, Y') : 'Immediate' }}
                                                                        {{ $reservation->reservation_time ? 'at ' . $reservation->reservation_time : '' }}
                                                                    </div>
                                                                    @if ($reservation->notes)
                                                                        <div class="small-muted small mt-1">
                                                                            {{ $reservation->notes }}</div>
                                                                    @endif
                                                                </div>
                                                                <div class="text-end">
                                                                    <div class="mono fw-semibold">
                                                                        {{ $reservation->phone }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div id="reservationsEmpty" class="small-muted small">No
                                                            reservations yet.</div>
                                                    @endforelse
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const scrollToId = (id) => {
            const el = document.getElementById(id);
            if (!el) return;
            el.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        };

        const normalizeStatus = (status) => {
            const value = String(status || 'available').toLowerCase();
            if (value === 'reserved') return 'Reserved';
            if (value === 'occupied') return 'Occupied';
            return 'Available';
        };

        @php
            $menuSectionsJson = [];
            foreach ($sections as $section) {
                $sectionData = [
                    'id' => $section->id,
                    'name' => $section->name,
                    'categories' => [],
                ];

                foreach ($section->menuCategories as $category) {
                    $categoryData = [
                        'id' => $category->id,
                        'name' => $category->name,
                        'subcategories' => [],
                    ];

                    foreach ($category->menuSubcategories as $subcategory) {
                        $categoryData['subcategories'][] = [
                            'id' => $subcategory->id,
                            'name' => $subcategory->name,
                        ];
                    }

                    $sectionData['categories'][] = $categoryData;
                }

                $menuSectionsJson[] = $sectionData;
            }

            $menuItemsJson = [];
            foreach ($menuItems as $item) {
                $activeOffer = $item->getActiveOffers()->first();
                $offerPrice = null;
                $offerLabel = null;

                if ($activeOffer && isset($activeOffer->pivot->discounted_price)) {
                    $offerPrice = (float) $activeOffer->pivot->discounted_price;
                    $offerLabel = $activeOffer->name;
                }

                $menuItemsJson[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => (float) $item->price,
                    'regular_price' => (float) $item->price,
                    'offer_price' => $offerPrice,
                    'offer_label' => $offerLabel,
                    'image' => $item->image,
                    'status' => $item->status,
                    'menu_subcategory_id' => $item->menu_subcategory_id,
                    'menu_category_id' => $item->menuSubcategory?->menu_category_id,
                    'menu_section_id' => $item->menuSubcategory?->menuCategory?->menu_section_id,
                    'preparation_time' => $item->preparation_time,
                ];
            }

            $initialTablesJson = [];
            foreach ($tables as $table) {
                $initialTablesJson[] = [
                    'id' => $table->id,
                    'name' => $table->table_number,
                    'status' => $table->status,
                    'lockedUntil' => null,
                ];
            }
        @endphp

        const MENU_SECTIONS = @json($menuSectionsJson);
        const MENU_ITEMS = @json($menuItemsJson);
        const initialTables = @json($initialTablesJson);

        const normalizedTables = initialTables.map((table) => ({
            ...table,
            status: normalizeStatus(table.status),
        }));

        const currentWaiterId = 1;

        const state = {
            tables: normalizedTables,
            selectedTableId: null,
            selectedSectionId: null,
            selectedCategoryId: null,
            selectedSubcategoryId: null,
            activeMenuItemId: null,
            draftItems: [], // { menuItemId, name, qty, unitPrice, regularPrice, offerPrice, specialInstructions }
            orders: [], // { id, tableId, items[], total, createdAt, status }
            reservations: [] // { id, tableId, name, phone, date, time, partySize, notes, status }
        };

        let itemModal;

        // ---------- Helpers ----------
        const money = (n) => (Number(n) || 0).toFixed(2);
        const findTable = (id) => state.tables.find(t => t.id === id);

        // ---------- Renderers ----------
        const tableBadge = (status) => {
            if (status === 'Available') {
                return `<span class="badge rounded-pill text-bg-success badge-soft" title="Available"><i class="bi bi-check2-circle me-1"></i> Available</span>`;
            }
            if (status === 'Reserved') {
                return `<span class="badge rounded-pill text-bg-primary badge-soft" title="Reserved"><i class="bi bi-clock me-1"></i> Reserved</span>`;
            }
            return `<span class="badge rounded-pill text-bg-warning text-dark badge-soft" title="Occupied"><i class="bi bi-fire me-1"></i> Occupied</span>`;
        };

        const renderTablesGrid = () => {
            const grid = document.getElementById('tablesGrid');
            grid.innerHTML = '';

            state.tables.forEach((t) => {
                const isActive = state.selectedTableId === t.id;
                const disabled = t.status !== 'Available' && t.status !== 'Reserved';

                const col = document.createElement('div');
                col.innerHTML = `
                    <div class="table-card p-2 border rounded-3 ${isActive ? 'active' : ''} ${disabled ? 'opacity-50' : ''}"
                         role="button"
                         data-table-id="${t.id}"
                         style="background:#fff;"
                         ${disabled ? 'aria-disabled="true"' : ''}>
                        <div class="fw-bold mb-2">${t.name}</div>
                        <div>${tableBadge(t.status)}</div>
                    </div>
                `;

                grid.appendChild(col.firstElementChild);
            });
        };

        const renderTableSelects = () => {
            const orderSel = document.getElementById('orderTableSelect');
            const resSel = document.getElementById('table_id');
            const opts = state.tables.map(t => {
                const disabled = t.status === 'Occupied';
                const attr = disabled ? 'disabled' : '';
                return `<option value="${t.id}" ${attr}>${t.name} - ${t.status}</option>`;
            }).join('');

            orderSel.innerHTML = opts;
            if (resSel) {
                resSel.innerHTML = `<option value="">Auto Assign</option>${opts}`;
            }

            if (!state.selectedTableId) {
                const firstAvail = state.tables.find(t => t.status !== 'Occupied');
                state.selectedTableId = firstAvail ? firstAvail.id : state.tables[0]?.id;
            }

            orderSel.value = state.selectedTableId ?? '';
            document.getElementById('selectedTableLabel').textContent = state.selectedTableId ?
                `Table ${state.selectedTableId}` : 'None';
        };

        const computeDraftTotal = () => {
            return state.draftItems.reduce((sum, it) => sum + (Number(it.unitPrice) * Number(it.qty)), 0);
        };

        const computeDraftDiscount = () => {
            return state.draftItems.reduce((sum, it) => {
                const regular = Number(it.regularPrice) || Number(it.unitPrice);
                const discount = Math.max(0, regular - Number(it.unitPrice));
                return sum + (discount * Number(it.qty));
            }, 0);
        };

        const computeTax = (amount) => {
            return 0;
        };

        const renderDraftItems = () => {
            const list = document.getElementById('draftItemsList');
            const empty = document.getElementById('draftItemsEmpty');
            list.innerHTML = '';

            if (!state.draftItems.length) {
                empty.style.display = 'block';
                return;
            }
            empty.style.display = 'none';

            state.draftItems.forEach((it, idx) => {
                const lineTotal = money(it.unitPrice * it.qty);

                const row = document.createElement('div');
                row.className = 'list-row d-flex align-items-start justify-content-between gap-3';
                row.innerHTML = `
                    <div>
                        <div class="fw-semibold">${it.name}</div>
                        <div class="small-muted small">${money(it.unitPrice)} × ${it.qty}${it.specialInstructions ? ` • ${it.specialInstructions}` : ''}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="mono fw-semibold">${lineTotal}</div>
                        <button class="btn btn-sm btn-outline-danger" data-remove-draft-idx="${idx}" title="Remove item">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;

                list.appendChild(row);
            });
        };

        const renderSummary = () => {
            const subtotal = computeDraftTotal();
            const discount = computeDraftDiscount();
            const tax = computeTax(subtotal - discount);
            const total = subtotal - discount + tax;

            document.getElementById('orderSubtotal').textContent = money(subtotal);
            document.getElementById('orderDiscount').textContent = `-${money(discount)}`;
            document.getElementById('orderTax').textContent = money(tax);
            document.getElementById('orderTotal').textContent = money(total);
            document.getElementById('orderTotalBig').textContent = money(total);
        };

        const renderOrdersAndReservations = () => {
            const ordersWrap = document.getElementById('ordersList');
            const ordersEmpty = document.getElementById('ordersEmpty');

            ordersWrap.innerHTML = '';

            const myActiveOrders = state.orders.filter(o => o.waiterId === currentWaiterId && o.status === 'Active');

            if (!myActiveOrders.length) {
                ordersEmpty.style.display = 'block';
            } else {
                ordersEmpty.style.display = 'none';
            }

            const fmtDate = (d, t) => {
                const date = d || '';
                const time = t || '';
                return [date, time].filter(Boolean).join(' ');
            };

            myActiveOrders.forEach((o) => {
                const t = findTable(o.tableId);
                const statusLabel = o.status === 'Active' ?
                    `<span class="badge text-bg-success">Active</span>` :
                    `<span class="badge text-bg-secondary">${o.status}</span>`;

                const div = document.createElement('div');
                div.className = 'list-row';
                div.innerHTML = `
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">Order #${o.id} — ${t ? t.name : 'Table ' + o.tableId}</div>
                            <div class="small-muted small">${statusLabel} • ${new Date(o.createdAt).toLocaleString()}</div>
                            <div class="small mt-1">
                                ${o.items.map(it => `<span class="mono">${it.qty}× ${it.name}</span>`).join('<span class="small-muted">, </span>')}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="mono fw-semibold">${money(o.total)}</div>
                            <div class="mt-2 d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-outline-secondary" data-print-order="${o.id}"> <i class="bi bi-receipt"></i> Print</button>
                            </div>
                        </div>
                    </div>
                `;
                ordersWrap.appendChild(div);
            });

            state.reservations.forEach((r) => {
                const t = findTable(r.tableId);
                const statusBadge = r.status === 'Reserved' ?
                    `<span class="badge text-bg-primary">Reserved</span>` :
                    r.status === 'Arriving' ?
                    `<span class="badge text-bg-warning text-dark">Arriving</span>` :
                    `<span class="badge text-bg-success">Seated</span>`;

                const div = document.createElement('div');
                div.className = 'list-row';
                div.innerHTML = `
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">${r.name} — ${t ? t.name : 'Table ' + r.tableId}</div>
                            <div class="small-muted small">${statusBadge} • ${fmtDate(r.date, r.time)}</div>
                            ${r.notes ? `<div class="small-muted small mt-1">${r.notes}</div>` : ''}
                            <div class="small-muted small mt-1">${r.phone ? `<i class="bi bi-telephone"></i> ${r.phone}` : ''}</div>
                        </div>
                        <div class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-outline-danger" data-delete-reservation="${r.id}" title="Cancel reservation">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                ordersWrap.appendChild(div);
            });
        };

        const itemImageUrl = (path) => {
            if (!path) return '';
            if (String(path).startsWith('http')) return path;
            return '/storage/' + String(path).replace(/^public\//, '');
        };

        const getCurrentItems = () => {
            if (state.selectedSubcategoryId) {
                return MENU_ITEMS.filter(item => item.menu_subcategory_id === state.selectedSubcategoryId);
            }
            if (state.selectedCategoryId) {
                return MENU_ITEMS.filter(item => item.menu_category_id === state.selectedCategoryId);
            }
            if (state.selectedSectionId) {
                return MENU_ITEMS.filter(item => item.menu_section_id === state.selectedSectionId);
            }
            return MENU_ITEMS;
        };

        const renderSections = () => {
            const list = document.getElementById('sectionsList');
            list.innerHTML = '';
            MENU_SECTIONS.forEach(section => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action text-start';
                button.textContent = section.name;
                button.dataset.sectionId = section.id;
                button.addEventListener('click', () => {
                    state.selectedSectionId = section.id;
                    state.selectedCategoryId = section.categories[0]?.id ?? null;
                    state.selectedSubcategoryId = section.categories[0]?.subcategories[0]?.id ?? null;
                    renderSections();
                    renderCategories();
                    renderSubcategories();
                    renderItemsGrid();
                });
                if (section.id === state.selectedSectionId) {
                    button.classList.add('active');
                }
                list.appendChild(button);
            });
        };

        const renderCategories = () => {
            const list = document.getElementById('categoriesList');
            list.innerHTML = '';
            const section = MENU_SECTIONS.find(s => s.id === state.selectedSectionId);
            if (!section || !section.categories.length) {
                list.innerHTML = '<div class="list-group-item text-muted">No categories</div>';
                return;
            }
            section.categories.forEach(category => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action text-start';
                button.textContent = category.name;
                button.dataset.categoryId = category.id;
                button.addEventListener('click', () => {
                    state.selectedCategoryId = category.id;
                    state.selectedSubcategoryId = category.subcategories[0]?.id ?? null;
                    renderCategories();
                    renderSubcategories();
                    renderItemsGrid();
                });
                if (category.id === state.selectedCategoryId) {
                    button.classList.add('active');
                }
                list.appendChild(button);
            });
        };

        const renderSubcategories = () => {
            const list = document.getElementById('subcategoriesList');
            list.innerHTML = '';
            const section = MENU_SECTIONS.find(s => s.id === state.selectedSectionId);
            const category = section?.categories.find(c => c.id === state.selectedCategoryId);
            if (!category || !category.subcategories.length) {
                list.innerHTML = '<div class="list-group-item text-muted">No subcategories</div>';
                return;
            }
            category.subcategories.forEach(subcategory => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'list-group-item list-group-item-action text-start';
                button.textContent = subcategory.name;
                button.dataset.subcategoryId = subcategory.id;
                button.addEventListener('click', () => {
                    state.selectedSubcategoryId = subcategory.id;
                    renderSubcategories();
                    renderItemsGrid();
                });
                if (subcategory.id === state.selectedSubcategoryId) {
                    button.classList.add('active');
                }
                list.appendChild(button);
            });
        };

        const renderItemsGrid = () => {
            const items = getCurrentItems();
            const grid = document.getElementById('itemsGrid');
            const count = document.getElementById('itemsCount');
            grid.innerHTML = '';
            count.textContent = String(items.length);

            if (!items.length) {
                grid.innerHTML = '<div class="col-12 text-muted small">No menu items in this section.</div>';
                return;
            }

            items.forEach(item => {
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6';
                const offerBadge = item.offer_price ?
                    `<span class="badge text-bg-warning text-dark">Offer</span>` : '';
                const availability = item.status === 'active' ? 'Available' : 'Unavailable';
                const availabilityClass = item.status === 'active' ? 'text-success' : 'text-danger';
                const imageUrl = itemImageUrl(item.image) ||
                'https://via.placeholder.com/320x240?text=No+Image';
                col.innerHTML = `
                    <div class="card h-100 shadow-sm">
                        <div class="row g-0">
                            <div class="col-4">
                                <img src="${imageUrl}" alt="${item.name}" class="img-fluid rounded-start" style="height:100%; width:100%; object-fit:cover;" />
                            </div>
                            <div class="col-8">
                                <div class="card-body d-flex flex-column h-100">
                                    <div class="d-flex align-items-start justify-content-between gap-2">
                                        <div>
                                            <div class="fw-semibold">${item.name}</div>
                                            <div class="small-muted small">${item.description ? item.description.substring(0, 70) : ''}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-primary">${money(item.offer_price || item.price)}</div>
                                            ${item.offer_price ? `<div class="small text-success">${money(item.price - item.offer_price)} off</div>` : ''}
                                        </div>
                                    </div>
                                    <div class="mt-2 mb-3">
                                        <span class="small ${availabilityClass}">${availability}</span>
                                        ${offerBadge}
                                    </div>
                                    <div class="mt-auto d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" data-open-item-modal="${item.id}">Details</button>
                                        <button type="button" class="btn btn-sm btn-success flex-grow-1" data-open-item-modal="${item.id}">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                grid.appendChild(col);
            });
        };

        const openItemModal = (itemId) => {
            const item = MENU_ITEMS.find(i => i.id === itemId);
            if (!item) return;
            state.activeMenuItemId = itemId;
            state.itemModalQty = 1;
            state.itemModalInstructions = '';

            document.getElementById('itemModalLabel').textContent = item.name;
            document.getElementById('itemModalBadge').innerHTML = item.offer_price ?
                `<span class="badge text-bg-warning text-dark">${item.offer_label || 'Special Offer'}</span>` : '';
            document.getElementById('itemModalImage').src = itemImageUrl(item.image) ||
                'https://via.placeholder.com/640x480?text=No+Image';
            document.getElementById('itemModalName').textContent = item.name;
            document.getElementById('itemModalAvailability').textContent = item.status === 'active' ? 'Available' :
                'Unavailable';
            document.getElementById('itemModalAvailability').className = item.status === 'active' ?
                'small text-success' : 'small text-danger';
            document.getElementById('itemModalPrice').textContent = money(item.offer_price || item.price);
            document.getElementById('itemModalPrepTime').textContent = item.preparation_time ?
                `${item.preparation_time} min` : '';
            document.getElementById('itemModalDescription').textContent = item.description || '';
            document.getElementById('itemModalQty').value = '1';
            document.getElementById('itemModalInstructions').value = '';
            document.getElementById('itemModalAddButton').disabled = item.status !== 'active';
            document.getElementById('itemModalAddButton').textContent = item.status === 'active' ? 'Add to Order' :
                'Unavailable';

            itemModal.show();
        };

        const addItemToDraft = () => {
            const item = MENU_ITEMS.find(i => i.id === state.activeMenuItemId);
            if (!item || item.status !== 'active') return;
            const qty = Math.max(1, Number(document.getElementById('itemModalQty').value || 1));
            const notes = document.getElementById('itemModalInstructions').value.trim();
            const existing = state.draftItems.find(d => d.menuItemId === item.id && d.specialInstructions === notes);
            if (existing) {
                existing.qty += qty;
            } else {
                state.draftItems.push({
                    menuItemId: item.id,
                    name: item.name,
                    qty,
                    unitPrice: item.offer_price ?? item.price,
                    regularPrice: item.price,
                    offerPrice: item.offer_price,
                    specialInstructions: notes || null,
                });
            }
            renderDraftItems();
            renderSummary();
            itemModal.hide();
        };

        const clearDraft = () => {
            state.draftItems = [];
            renderDraftItems();
            renderSummary();
        };

        const submitOrder = () => {
            const tableId = Number(document.getElementById('orderTableSelect').value || 0);
            const customerName = document.getElementById('orderCustomerName').value.trim();
            const phone = document.getElementById('orderCustomerPhone').value.trim();
            const guestCount = Number(document.getElementById('orderGuestCount').value || 0);

            if (!tableId) {
                alert('Please select a table for the order.');
                return;
            }
            if (!customerName || !phone || guestCount < 1) {
                alert('Customer name, phone, and number of guests are required.');
                return;
            }
            if (!state.draftItems.length) {
                alert('Add at least one menu item to the order.');
                return;
            }

            document.getElementById('orderFormCustomerName').value = customerName;
            document.getElementById('orderFormCustomerPhone').value = phone;
            document.getElementById('orderFormGuestCount').value = guestCount;
            document.getElementById('orderFormTableId').value = tableId;
            document.getElementById('orderFormOrderItems').value = JSON.stringify(state.draftItems.map((it) => ({
                menu_item_id: it.menuItemId,
                quantity: it.qty,
                offer_price: it.offerPrice ?? undefined,
                special_instructions: it.specialInstructions ?? null
            })));

            document.getElementById('orderForm').submit();
        };

        const createReservation = () => {
            const tableId = Number(document.getElementById('table_id').value || 0);
            const name = document.getElementById('customer_name').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const date = document.getElementById('reservation_date').value;
            const time = document.getElementById('reservation_time').value;
            const partySize = Number(document.getElementById('guest_count').value || 1);
            const notes = document.getElementById('notes').value.trim();

            if (!tableId && document.getElementById('reservation_type').value === 'scheduled') {
                alert('Please select a table or use auto-assign.');
                return;
            }

            if (!name || !phone || !partySize) {
                alert('Reservation requires customer name, phone, and guest count.');
                return;
            }

            const t = findTable(tableId);
            if (tableId && (!t || t.status === 'Occupied')) {
                alert('This table is occupied. Choose another table.');
                return;
            }

            state.reservations.unshift({
                id: Date.now(),
                tableId,
                name,
                phone,
                date,
                time,
                partySize,
                notes,
                status: 'Reserved'
            });

            renderTablesGrid();
            renderTableSelects();
            renderOrdersAndReservations();
        };

        const markSelectedAvailable = () => {
            if (!state.selectedTableId) return;
            const t = findTable(state.selectedTableId);
            if (!t) return;

            // Only allow releasing occupied table to available
            if (t.status === 'Occupied' || t.status === 'Reserved') {
                t.status = 'Available';
                t.lockedUntil = null;
            }

            renderTablesGrid();
            renderTableSelects();
        };

        const resetDemo = () => {
            state.tables = normalizedTables;
            state.selectedTableId = null;
            state.draftItems = [];
            state.orders = [];
            state.reservations = [];

            renderTablesGrid();
            renderTableSelects();
            renderDraftItems();
            renderSummary();
            renderOrdersAndReservations();
        };

        // ---------- Boot ----------
        const init = () => {
            if (MENU_SECTIONS.length) {
                state.selectedSectionId = MENU_SECTIONS[0].id;
                state.selectedCategoryId = MENU_SECTIONS[0].categories[0]?.id ?? null;
                state.selectedSubcategoryId = MENU_SECTIONS[0].categories[0]?.subcategories[0]?.id ?? null;
            }

            itemModal = new bootstrap.Modal(document.getElementById('itemModal'));

            renderTablesGrid();
            renderTableSelects();
            renderSections();
            renderCategories();
            renderSubcategories();
            renderItemsGrid();
            renderDraftItems();
            renderSummary();
            renderOrdersAndReservations();

            // default reservation date/time
            const now = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            const dateField = document.getElementById('reservation_date');
            const timeField = document.getElementById('reservation_time');
            if (dateField) {
                dateField.value = `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`;
            }
            if (timeField) {
                timeField.value = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
            }
        };

        // ---------- Events ----------
        document.addEventListener('click', (e) => {
            const tableBtn = e.target.closest('[data-table-id]');
            if (tableBtn) {
                const tableId = Number(tableBtn.getAttribute('data-table-id'));
                setSelectedTable(tableId);
                return;
            }

            const removeDraftIdx = e.target.closest('[data-remove-draft-idx]');
            if (removeDraftIdx) {
                const idx = Number(removeDraftIdx.getAttribute('data-remove-draft-idx'));
                state.draftItems.splice(idx, 1);
                renderDraftItems();
                renderSummary();
                return;
            }

            const delResId = e.target.closest('[data-delete-reservation]');
            if (delResId) {
                const id = Number(delResId.getAttribute('data-delete-reservation'));
                const rIdx = state.reservations.findIndex(r => r.id === id);
                if (rIdx >= 0) {
                    const r = state.reservations[rIdx];
                    // Free the table only if still reserved and table matches
                    const t = findTable(r.tableId);
                    if (t && t.status === 'Reserved') {
                        t.status = 'Available';
                    }
                    state.reservations.splice(rIdx, 1);
                    renderTablesGrid();
                    renderTableSelects();
                    renderOrdersAndReservations();


                }
                return;
            }

            const printOrderBtn = e.target.closest('[data-print-order]');
            if (printOrderBtn) {
                const id = Number(printOrderBtn.getAttribute('data-print-order'));
                const o = state.orders.find(x => x.id === id);
                if (!o) return;
                alert(
                    `Order #${o.id}\nTotal: ${money(o.total)}\nItems: ${o.items.map(it => `${it.qty}× ${it.name}`).join(', ')}`
                );
                return;
            }
        });

        // Quick actions
        document.getElementById('qaNewOrder').addEventListener('click', () => scrollToId('createOrderSection'));
        document.getElementById('qaViewTables').addEventListener('click', () => scrollToId('tablesSection'));
        document.getElementById('qaViewReservations').addEventListener('click', () => scrollToId('reservationsSection'));

        document.getElementById('btnMarkAvailable').addEventListener('click', markSelectedAvailable);

        document.getElementById('btnClearDraft').addEventListener('click', clearDraft);
        document.getElementById('btnCreateOrder').addEventListener('click', submitOrder);
        const clearAllButton = document.getElementById('btnClearAll');
        if (clearAllButton) {
            clearAllButton.addEventListener('click', resetDemo);
        }

        document.getElementById('orderTableSelect').addEventListener('change', (ev) => {
            const id = Number(ev.target.value);
            setSelectedTable(id);
        });

        document.addEventListener('click', (event) => {
            const actionBtn = event.target.closest('[data-open-item-modal]');
            if (actionBtn) {
                const itemId = Number(actionBtn.dataset.openItemModal);
                openItemModal(itemId);
            }
        });

        document.getElementById('itemModalQtyMinus').addEventListener('click', () => {
            const qtyInput = document.getElementById('itemModalQty');
            qtyInput.value = Math.max(1, Number(qtyInput.value || 1) - 1);
        });
        document.getElementById('itemModalQtyPlus').addEventListener('click', () => {
            const qtyInput = document.getElementById('itemModalQty');
            qtyInput.value = Math.max(1, Number(qtyInput.value || 1) + 1);
        });

        document.getElementById('itemModalAddButton').addEventListener('click', addItemToDraft);

        const reservationTypeSelect = document.getElementById('reservation_type');
        const scheduledFields = document.getElementById('scheduledFields');
        if (reservationTypeSelect && scheduledFields) {
            const toggleScheduledFields = () => {
                scheduledFields.style.display = reservationTypeSelect.value === 'scheduled' ? 'flex' : 'none';
            };
            reservationTypeSelect.addEventListener('change', toggleScheduledFields);
            toggleScheduledFields();
        }

        init();
    </script>

</html>
