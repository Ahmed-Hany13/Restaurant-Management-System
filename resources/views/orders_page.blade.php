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
                        <div id="tablesGrid" class="row g-2"></div>
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
                                    <div class="small-muted small">Choose table, add items, then create order.</div>
                                </div>
                                <div class="text-end">
                                    <div class="small-muted small">Table status updates automatically.</div>
                                    <div class="fw-bold mono" style="font-size:1.05rem;">Total: <span
                                            id="orderTotal">0.00</span></div>
                                </div>
                            </div>

                            <div class="rm-panel-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">Table</label>
                                        <select id="orderTableSelect" class="form-select"
                                            aria-label="Select table"></select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">Menu Item</label>
                                        <select id="menuItemSelect" class="form-select"
                                            aria-label="Select menu item"></select>
                                        <div class="small-muted small mt-1">
                                            Price: <span id="menuItemPrice" class="mono">0.00</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">Quantity</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <button id="qtyMinus" class="btn btn-outline-secondary"
                                                type="button"><i class="bi bi-dash"></i></button>
                                            <input id="qtyInput" class="form-control text-center qty-pill mono"
                                                type="number" value="1" min="1" step="1" />
                                            <button id="qtyPlus" class="btn btn-outline-secondary"
                                                type="button"><i class="bi bi-plus"></i></button>
                                        </div>
                                        <div class="small-muted small mt-1">Line total: <span id="lineTotal"
                                                class="mono">0.00</span></div>
                                    </div>

                                    <div class="col-12">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button id="btnAddItem" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-1"></i> Add to Order
                                            </button>
                                            <button id="btnClearDraft" class="btn btn-outline-danger">
                                                <i class="bi bi-trash me-1"></i> Clear Draft
                                            </button>
                                            <button id="btnCreateOrder" class="btn btn-success">
                                                <i class="bi bi-receipt-cutoff me-1"></i> Create Order
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-7">
                                        <div class="fw-semibold mb-2"><i class="bi bi-list-ul me-1"></i> Order Items
                                            (Draft)</div>
                                        <div class="rm-panel" style="border-style:dashed;">
                                            <div class="rm-panel-body">
                                                <div id="draftItemsEmpty" class="small-muted small">No items yet. Add
                                                    from the menu above.</div>
                                                <div id="draftItemsList" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-5">
                                        <div class="fw-semibold mb-2"><i class="bi bi-credit-card me-1"></i> Summary
                                        </div>
                                        <div class="rm-panel" style="border-style:dashed;">
                                            <div class="rm-panel-body">
                                                <div class="d-flex justify-content-between">
                                                    <div class="small-muted">Subtotal</div>
                                                    <div class="mono fw-semibold" id="orderSubtotal">0.00</div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-2">
                                                    <div class="small-muted">Tax (0%)</div>
                                                    <div class="mono fw-semibold">0.00</div>
                                                </div>
                                                <hr />
                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div>
                                                        <div class="small-muted small">Total</div>
                                                        <div class="fw-bold mono" style="font-size:1.25rem;"
                                                            id="orderTotalBig">0.00</div>
                                                    </div>
                                                    <div class="text-end small-muted">
                                                        <i class="bi bi-info-circle"></i>
                                                        <div class="small">Draft only changes lists when you create
                                                            the order.</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3 alert alert-info mb-0" role="alert">
                                            <div class="d-flex gap-2 align-items-start">
                                                <i class="bi bi-lightning-charge"></i>
                                                <div>
                                                    <div class="fw-semibold">Tip</div>
                                                    <div class="small">
                                                        Tables become <span
                                                            class="badge text-bg-warning">Occupied</span> after
                                                        creating an order. Reservations mark tables as <span
                                                            class="badge text-bg-primary">Reserved</span>.
                                                    </div>
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

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Table</label>
                                                <select id="reservationTableSelect" class="form-select"
                                                    aria-label="Select table for reservation"></select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Customer Name</label>
                                                <input id="resName" class="form-control" type="text"
                                                    placeholder="e.g. Ahmed" />
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Phone</label>
                                                <input id="resPhone" class="form-control" type="tel"
                                                    placeholder="e.g. 010xxxxxxx" />
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">Date</label>
                                                <input id="resDate" class="form-control" type="date" />
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">Time</label>
                                                <input id="resTime" class="form-control" type="time" />
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">Party Size</label>
                                                <input id="resPartySize" class="form-control" type="number"
                                                    min="1" step="1" value="2" />
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">Status</label>
                                                <select id="resStatus" class="form-select">
                                                    <option value="Reserved" selected>Reserved</option>
                                                    <option value="Arriving">Arriving</option>
                                                    <option value="Seated">Seated</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Notes (optional)</label>
                                                <textarea id="resNotes" class="form-control" rows="2" placeholder="Any special requests..."></textarea>
                                            </div>
                                            <div class="col-12">
                                                <button id="btnCreateReservation" class="btn btn-primary w-100">
                                                    <i class="bi bi-person-plus me-1"></i> Create Reservation
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-7">
                                <div class="rm-panel h-100">
                                    <div class="rm-panel-head d-flex align-items-start justify-content-between gap-3">
                                        <div>
                                            <div class="fw-bold fs-5"><i class="bi bi-kanban me-1"></i> Active Orders
                                                & Reservations</div>
                    <div class="small-muted small">UI-only demo state (no backend yet).</div>
                                        </div>
                                        <div class="text-end">
                                            <button id="btnClearAll" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-x-circle me-1"></i> Reset Demo
                                            </button>
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
                                                <div id="reservationsList"></div>
                                                <div id="reservationsEmpty" class="small-muted small"
                                                    style="display:none;">No reservations yet.</div>
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
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        // ---------- Demo data (menu) ----------


        const MENU_ITEMS = [{
                id: 1,
                name: 'Chicken Shawarma',
                price: 80.00
            },
            {
                id: 2,
                name: 'Beef Burger',
                price: 120.00
            },
            {
                id: 3,
                name: 'Caesar Salad',
                price: 90.00
            },
            {
                id: 4,
                name: 'Margherita Pizza',
                price: 160.00
            },
            {
                id: 5,
                name: 'Pasta Alfredo',
                price: 140.00
            },
            {
                id: 6,
                name: 'Fresh Juice',
                price: 60.00
            },
            {
                id: 7,
                name: 'Chocolate Cake',
                price: 110.00
            },
            {
                id: 8,
                name: 'Espresso',
                price: 50.00
            }
        ];

        // ---------- Demo state ----------
        // status: Available | Reserved | Occupied
        const currentWaiterId = 1; // UI-only demo: pretend logged-in waiter #1

        const state = {
            tables: new Array(12).fill(0).map((_, i) => ({
                id: i + 1,
                name: 'Table ' + (i + 1),
                status: 'Available',
                lockedUntil: null
            })),
            selectedTableId: null,
            draftItems: [], // { menuItemId, name, price, qty }
            orders: [], // { id, tableId, items[], total, createdAt, status }
            reservations: [] // { id, tableId, name, phone, date, time, partySize, notes, status }
        };

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
                col.className = 'col-6 col-sm-4 col-md-3 col-lg-4';

                col.innerHTML = `
                    <div class="table-card p-2 border rounded-3 ${isActive ? 'active' : ''} ${disabled ? 'opacity-50' : ''}"
                         role="button"
                         data-table-id="${t.id}"
                         style="background:#fff;"
                         ${disabled ? 'aria-disabled="true"' : ''}>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div class="fw-bold">${t.name}</div>
                        </div>
                        <div class="mt-1">${tableBadge(t.status)}</div>
                    </div>
                `;

                grid.appendChild(col);
            });
        };

        const renderTableSelects = () => {
            const orderSel = document.getElementById('orderTableSelect');
            const resSel = document.getElementById('reservationTableSelect');
            const opts = state.tables.map(t => {
                const disabled = t.status === 'Occupied';
                const attr = disabled ? 'disabled' : '';
                return `<option value="${t.id}" ${attr}>${t.name} - ${t.status}</option>`;
            }).join('');

            orderSel.innerHTML = opts;
            resSel.innerHTML = opts;

            if (!state.selectedTableId) {
                const firstAvail = state.tables.find(t => t.status !== 'Occupied');
                state.selectedTableId = firstAvail ? firstAvail.id : state.tables[0]?.id;
            }

            orderSel.value = state.selectedTableId ?? '';
            document.getElementById('selectedTableLabel').textContent = state.selectedTableId ?
                `Table ${state.selectedTableId}` : 'None';
        };

        const computeDraftTotal = () => {
            return state.draftItems.reduce((sum, it) => sum + (Number(it.price) * Number(it.qty)), 0);
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
                const lineTotal = money(it.price * it.qty);

                const row = document.createElement('div');
                row.className = 'list-row d-flex align-items-start justify-content-between gap-3';
                row.innerHTML = `
                    <div>
                        <div class="fw-semibold">${it.name}</div>
                        <div class="small-muted small">${money(it.price)} × ${it.qty}</div>
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
            document.getElementById('orderSubtotal').textContent = money(subtotal);
            document.getElementById('orderTotal').textContent = money(subtotal);
            document.getElementById('orderTotalBig').textContent = money(subtotal);
        };

        const renderOrdersAndReservations = () => {
            const ordersWrap = document.getElementById('ordersList');
            const ordersEmpty = document.getElementById('ordersEmpty');

            const resEmpty = document.getElementById('reservationsEmpty');

            ordersWrap.innerHTML = '';
            resWrap.innerHTML = '';

            if (!myActiveOrders.length) {
                ordersEmpty.style.display = 'block';
            } else {
                ordersEmpty.style.display = 'none';
            }

            if (!state.reservations.length) {
                resEmpty.style.display = 'block';
            } else {
                resEmpty.style.display = 'none';
            }

            const fmtDate = (d, t) => {
                const date = d || '';
                const time = t || '';
                return [date, time].filter(Boolean).join(' ');
            };

            const myActiveOrders = state.orders.filter(o => o.waiterId === currentWaiterId && o.status === 'Active');

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

            myActiveReservations.forEach((r) => {
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
                resWrap.appendChild(div);
            });
        };

        const renderMenuItemSelect = () => {
            const menuSel = document.getElementById('menuItemSelect');
            menuSel.innerHTML = MENU_ITEMS.map(mi => `<option value="${mi.id}">${mi.name}</option>`).join('');
            const first = MENU_ITEMS[0];
            if (first) {
                menuSel.value = first.id;
                document.getElementById('menuItemPrice').textContent = money(first.price);
            }
        };

        // ---------- Actions ----------
        const setSelectedTable = (tableId) => {
            const t = findTable(tableId);
            if (!t) return;

            if (t.status === 'Occupied') return;

            state.selectedTableId = tableId;
            document.getElementById('selectedTableLabel').textContent = `Table ${tableId}`;
            renderTablesGrid();
            document.getElementById('orderTableSelect').value = String(tableId);
        };

        const addDraftItemFromSelect = () => {
            const menuId = Number(document.getElementById('menuItemSelect').value);
            const menuItem = MENU_ITEMS.find(m => m.id === menuId);
            const qty = Math.max(1, Number(document.getElementById('qtyInput').value || 1));
            if (!menuItem || !state.selectedTableId) return;

            const existingIdx = state.draftItems.findIndex(d => d.menuItemId === menuId);
            if (existingIdx >= 0) {
                state.draftItems[existingIdx].qty += qty;
            } else {
                state.draftItems.push({
                    menuItemId: menuItem.id,
                    name: menuItem.name,
                    price: menuItem.price,
                    qty
                });
            }

            document.getElementById('qtyInput').value = 1;
            renderDraftItems();
            renderSummary();
        };

        const clearDraft = () => {
            state.draftItems = [];
            renderDraftItems();
            renderSummary();
        };

        const createOrder = () => {
            if (!state.selectedTableId) return;
            if (!state.draftItems.length) return;

            const newId = state.orders.length ? Math.max(...state.orders.map(o => o.id)) + 1 : 1;
            const total = computeDraftTotal();

            // Occupy the table
            const t = findTable(state.selectedTableId);
            if (t && t.status !== 'Occupied') {
                t.status = 'Occupied';
                t.lockedUntil = Date.now() + 60_000; // tiny demo lock
            }

            state.orders.unshift({
                waiterId: currentWaiterId,
                id: newId,
                tableId: state.selectedTableId,
                items: state.draftItems.map(it => ({
                    ...it
                })),
                total,
                createdAt: new Date().toISOString(),
                status: 'Active'
            });

            // Clear draft
            clearDraft();

            renderTablesGrid();
            renderTableSelects();
            renderOrdersAndReservations();
        };

        const createReservation = () => {
            const tableId = Number(document.getElementById('reservationTableSelect').value);
            const name = document.getElementById('resName').value.trim();
            const phone = document.getElementById('resPhone').value.trim();
            const date = document.getElementById('resDate').value;
            const time = document.getElementById('resTime').value;
            const partySize = Number(document.getElementById('resPartySize').value || 1);
            const status = document.getElementById('resStatus').value;
            const notes = document.getElementById('resNotes').value.trim();

            if (!tableId || !name || !date || !time) {
                alert('Reservation requires: Table, Customer Name, Date, Time');
                return;
            }

            const t = findTable(tableId);
            if (!t || t.status === 'Occupied') {
                alert('This table is occupied. Choose another table.');
                return;
            }

            const newId = state.reservations.length ? Math.max(...state.reservations.map(r => r.id)) + 1 : 1;

            // Mark table as Reserved
            t.status = 'Reserved';

            state.reservations.unshift({
                id: newId,
                tableId,
                name,
                phone,
                date,
                time,
                partySize,
                notes,
                status
            });

            // Reset form (keep status)
            document.getElementById('resName').value = '';
            document.getElementById('resPhone').value = '';
            document.getElementById('resNotes').value = '';

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
            state.tables = new Array(12).fill(0).map((_, i) => ({
                id: i + 1,
                name: 'Table ' + (i + 1),
                status: 'Available',
                lockedUntil: null
            }));
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
            renderTablesGrid();
            renderTableSelects();
            renderMenuItemSelect();
            renderDraftItems();
            renderSummary();
            renderOrdersAndReservations();

            // default reservation date/time
            const now = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            document.getElementById('resDate').value =
                `${now.getFullYear()}-${pad(now.getMonth() + 1)}-${pad(now.getDate())}`;
            document.getElementById('resTime').value = `${pad(now.getHours())}:${pad(now.getMinutes())}`;
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
        document.getElementById('btnAddItem').addEventListener('click', addDraftItemFromSelect);
        document.getElementById('btnCreateOrder').addEventListener('click', createOrder);
        document.getElementById('btnCreateReservation').addEventListener('click', createReservation);
        document.getElementById('btnClearAll').addEventListener('click', resetDemo);

        document.getElementById('orderTableSelect').addEventListener('change', (ev) => {
            const id = Number(ev.target.value);
            setSelectedTable(id);
        });

        document.getElementById('menuItemSelect').addEventListener('change', (ev) => {
            const id = Number(ev.target.value);
            const mi = MENU_ITEMS.find(m => m.id === id);
            document.getElementById('menuItemPrice').textContent = money(mi ? mi.price : 0);
        });

        document.getElementById('qtyMinus').addEventListener('click', () => {
            const inp = document.getElementById('qtyInput');
            const v = Math.max(1, Number(inp.value || 1) - 1);
            inp.value = v;
        });
        document.getElementById('qtyPlus').addEventListener('click', () => {
            const inp = document.getElementById('qtyInput');
            const v = Math.max(1, Number(inp.value || 1) + 1);
            inp.value = v;
        });

        const updateLineTotal = () => {
            const menuId = Number(document.getElementById('menuItemSelect').value);
            const mi = MENU_ITEMS.find(m => m.id === menuId);
            const qty = Math.max(1, Number(document.getElementById('qtyInput').value || 1));
            const line = (mi ? mi.price : 0) * qty;
            document.getElementById('lineTotal').textContent = money(line);
        };

        document.getElementById('qtyInput').addEventListener('input', updateLineTotal);
        document.getElementById('menuItemSelect').addEventListener('change', updateLineTotal);
        document.getElementById('qtyMinus').addEventListener('click', updateLineTotal);
        document.getElementById('qtyPlus').addEventListener('click', updateLineTotal);

        init();
    </script>
</html>
