<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Billing</title>

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

        .small-muted {
            color: rgba(0, 0, 0, .55);
        }

        .section-title {
            letter-spacing: -0.01em;
        }

        @media print {
            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            #printInvoice {
                display: block !important;
            }
        }

        #printInvoice {
            display: none;
        }

        @media print {
            #printInvoice {
                display: block;
            }
        }
    </style>
</head>

<body>

    <div class="rm-topbar">
        <div class="container py-3 d-flex align-items-center justify-content-between gap-2 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                    style="width:40px;height:40px;">
                    <i class="bi bi-receipt" style="font-size:1.1rem"></i>
                </div>
                <div>
                    <div class="fw-bold">Billing</div>
                    <div class="small-muted small">View orders • Generate bills • Process payments</div>
                </div>
            </div>

            <div class="d-flex gap-2 align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-house me-1"></i> Home
                    </a>
                <a href="{{ route('orders_page') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-card-list me-1"></i> Orders
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
            </div>
        </div>
    </div>
    @include('components.session-messages')


    <div class="container py-4">
        <div class="row g-3">
            <!-- LEFT: orders -->
            <div class="col-12 col-lg-5">
                <div class="rm-panel h-100">
                    <div class="rm-panel-head d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="section-title fw-bold fs-5"><i class="bi bi-receipt me-1"></i> Orders to Bill
                            </div>
                            <div class="small-muted small">Select an active order to generate the bill.</div>
                        </div>

                        <div class="text-end">
                            <div class="small-muted small">Filter</div>
                            <select id="statusFilter" class="form-select form-select-sm" style="min-width: 170px;">
                                <option value="all" selected>All</option>
                                <option value="Active">Active</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                    </div>

                    <div class="rm-panel-body">
                        <div id="ordersList"></div>
                        <div id="ordersEmpty" class="small-muted small" style="display:none;">No orders found.</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: bill + payments -->
            <div class="col-12 col-lg-7">
                <div class="rm-panel h-100">
                    <div class="rm-panel-head d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="section-title fw-bold fs-5"><i class="bi bi-credit-card me-1"></i> Bill &
                                Payment</div>
                            <div class="small-muted small">Generate invoice, accept payment, and mark as paid.</div>
                        </div>

                        <div class="text-end">
                            <div class="small-muted small">Order total</div>
                            <div class="fw-bold mono" style="font-size:1.2rem;" id="selectedOrderTotal">0.00</div>
                        </div>
                    </div>

                    <div class="rm-panel-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="alert alert-info mb-0" role="alert">
                                    <div class="d-flex gap-2 align-items-start">
                                        <i class="bi bi-info-circle"></i>
                                        <div>
                                            <div class="fw-semibold">Demo mode</div>
                                            <div class="small">
                                                This page works with demo data inside the browser (no backend yet). You
                                                can still generate bills,
                                                process payments, print invoices, and update order status.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="rm-panel" style="border-style:dashed;">
                                    <div class="rm-panel-body">
                                        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                                            <div>
                                                <div class="small-muted small">Selected Order</div>
                                                <div class="fw-bold fs-4" id="selectedOrderLabel">None</div>
                                                <div class="small-muted small mt-1" id="selectedOrderMeta"></div>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center no-print">
                                                <button id="btnPrint" class="btn btn-outline-primary">
                                                    <i class="bi bi-printer me-1"></i> Print
                                                </button>
                                                <button id="btnResetDemo" class="btn btn-outline-danger">
                                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Demo
                                                </button>
                                            </div>
                                        </div>

                                        <hr />

                                        <div class="row g-3">
                                            <div class="col-12 col-lg-7">
                                                <div class="fw-semibold mb-2"><i class="bi bi-receipt"></i> Invoice
                                                    Items</div>
                                                <div id="invoiceItems"></div>
                                                <div id="invoiceItemsEmpty" class="small-muted small"
                                                    style="display:none;">
                                                    Select an order to see its items.
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-5">
                                                <div class="fw-semibold mb-2"><i class="bi bi-calculator"></i> Totals
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    <div class="small-muted">Subtotal</div>
                                                    <div class="mono fw-semibold" id="invoiceSubtotal">0.00</div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-2">
                                                    <div class="small-muted">Tax (0%)</div>
                                                    <div class="mono fw-semibold" id="invoiceTax">0.00</div>
                                                </div>

                                                <hr />

                                                <div class="d-flex justify-content-between align-items-end">
                                                    <div>
                                                        <div class="small-muted small">Total</div>
                                                        <div class="fw-bold mono" style="font-size:1.4rem;"
                                                            id="invoiceTotalBig">0.00</div>
                                                    </div>
                                                    <div class="text-end small-muted">
                                                        <i class="bi bi-shield-check"></i>
                                                        <div class="small">Payment updates status immediately.</div>
                                                    </div>
                                                </div>

                                                <hr />

                                                <div class="fw-semibold mb-2"><i class="bi bi-cash-coin me-1"></i>
                                                    Payment</div>

                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Payment method</label>
                                                        <select id="payMethod" class="form-select">
                                                            <option value="Cash" selected>Cash</option>
                                                            <option value="Card">Card</option>
                                                            <option value="Wallet">Wallet</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label fw-semibold">Amount paid</label>
                                                        <input id="amountPaid" class="form-control mono"
                                                            type="number" min="0" step="0.01"
                                                            value="0.00" />
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="small-muted">Remaining</div>
                                                            <div class="mono fw-semibold" id="remainingAmount">0.00
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-2">
                                                            <div class="small-muted">Change</div>
                                                            <div class="mono fw-semibold" id="changeAmount">0.00</div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 d-grid">
                                                        <button id="btnProcessPayment" class="btn btn-success">
                                                            <i class="bi bi-check2-circle me-1"></i> Process Payment
                                                        </button>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="small-muted small">
                                                            Tip: enter a higher amount
                                                            to get change.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr />

                                        <!-- Hidden printable invoice -->
                                        <div id="printInvoice" class="mt-3">
                                            <div class="d-flex align-items-start justify-content-between gap-3">
                                                <div>
                                                    <div class="fw-bold fs-3">Restaurant</div>
                                                    <div class="small-muted">Invoice</div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="small-muted">Invoice ID</div>
                                                    <div class="fw-bold mono" id="invoiceIdPrint">INV-0000</div>
                                                </div>
                                            </div>

                                            <hr />

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="small-muted">Bill for</div>
                                                    <div class="fw-semibold" id="invoiceBillForPrint">Table -</div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <div class="fw-semibold mb-2">Items</div>
                                                <div id="invoiceItemsPrint"></div>
                                            </div>

                                            <hr />

                                            <div class="row">
                                                <div class="col-12 col-sm-6">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="small-muted">Subtotal</div>
                                                        <div class="mono fw-semibold" id="invoiceSubtotalPrint">0.00
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <div class="small-muted">Tax</div>
                                                        <div class="mono fw-semibold" id="invoiceTaxPrint">0.00</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <div class="small-muted">Total</div>
                                                        <div class="mono fw-bold" id="invoiceTotalPrint">0.00</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="d-flex justify-content-between">
                                                        <div class="small-muted">Payment method</div>
                                                        <div class="mono" id="invoicePayMethodPrint">-</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <div class="small-muted">Paid</div>
                                                        <div class="mono" id="invoicePaidPrint">0.00</div>
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-2">
                                                        <div class="small-muted">Change</div>
                                                        <div class="mono" id="invoiceChangePrint">0.00</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="small-muted mt-3">Printed at: <span id="invoicePrintedAtPrint"
                                                    class="mono"></span></div>
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
        // ---------- Demo data ----------
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

        const money = (n) => (Number(n) || 0).toFixed(2);

        // Demo state lives in-memory (per page load)
        const state = {
            selectedOrderId: null,
            orders: [{
                    id: 101,
                    tableId: 3,
                    tableName: 'Table 3',
                    items: [{
                            menuItemId: 1,
                            name: 'Chicken Shawarma',
                            price: 80.00,
                            qty: 2
                        },
                        {
                            menuItemId: 8,
                            name: 'Espresso',
                            price: 50.00,
                            qty: 2
                        },
                    ],
                    status: 'Active',
                    total: 0,
                    createdAt: new Date().toISOString(),
                },
                {
                    id: 102,
                    tableId: 5,
                    tableName: 'Table 5',
                    items: [{
                            menuItemId: 4,
                            name: 'Margherita Pizza',
                            price: 160.00,
                            qty: 1
                        },
                        {
                            menuItemId: 6,
                            name: 'Fresh Juice',
                            price: 60.00,
                            qty: 3
                        },
                    ],
                    status: 'Active',
                    total: 0,
                    createdAt: new Date().toISOString(),
                },
                {
                    id: 103,
                    tableId: 1,
                    tableName: 'Table 1',
                    items: [{
                            menuItemId: 2,
                            name: 'Beef Burger',
                            price: 120.00,
                            qty: 1
                        },
                        {
                            menuItemId: 3,
                            name: 'Caesar Salad',
                            price: 90.00,
                            qty: 1
                        },
                    ],
                    status: 'Paid',
                    total: 0,
                    createdAt: new Date().toISOString(),
                }
            ]
        };

        // compute totals
        state.orders.forEach(o => {
            o.total = o.items.reduce((s, it) => s + it.price * it.qty, 0);
        });


        // ---------- Render ----------
        const el = (id) => document.getElementById(id);

        const renderOrders = () => {
            const list = el('ordersList');
            const empty = el('ordersEmpty');
            const filter = el('statusFilter').value;

            list.innerHTML = '';

            const filtered = state.orders.filter(o => {
                if (filter === 'all') return true;
                return o.status === filter;
            });

            if (!filtered.length) {
                empty.style.display = 'block';
                return;
            }
            empty.style.display = 'none';

            filtered.forEach(o => {
                const card = document.createElement('div');
                const isActive = state.selectedOrderId === o.id;
                card.className = 'table-card p-2 border rounded-3 mb-2 ' + (isActive ? 'active' : '');
                card.style.background = 'white';

                const statusBadge = o.status === 'Active' ?
                    '<span class="badge text-bg-success badge-soft"><i class="bi bi-clock me-1"></i> Active</span>' :
                    '<span class="badge text-bg-primary badge-soft"><i class="bi bi-check2-circle me-1"></i> Paid</span>';

                card.setAttribute('role', 'button');
                card.dataset.order - id = String(o.id);
                card.innerHTML = `
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <div class="fw-semibold">Order #${o.id}</div>
                        <div class="small-muted small">${o.tableName} • ${new Date(o.createdAt).toLocaleString()}</div>
                        <div class="mt-1">${o.items.map(it => `<span class="mono">${it.qty}×${it.name}</span>`).join('<span class="small-muted">, </span>')}</div>
                    </div>
                    <div class="text-end">
                        <div class="mono fw-semibold">${money(o.total)}</div>
                        <div class="mt-2">${statusBadge}</div>
                    </div>
                </div>
            `;

                // Only Active orders can be selected
                if (o.status !== 'Active') {
                    card.style.opacity = '0.6';
                    card.setAttribute('aria-disabled', 'true');
                    card.style.pointerEvents = 'none';
                } else {
                    card.style.pointerEvents = 'auto';
                }

                list.appendChild(card);
            });
        };

        const renderInvoiceForSelectedOrder = () => {
            const itemsWrap = el('invoiceItems');
            const itemsEmpty = el('invoiceItemsEmpty');

            const order = state.orders.find(o => o.id === state.selectedOrderId);

            el('selectedOrderLabel').textContent = order ? `Order #${order.id}` : 'None';
            el('selectedOrderMeta').textContent = order ? `${order.tableName}` : '';
            el('selectedOrderTotal').textContent = money(order ? order.total : 0);

            itemsWrap.innerHTML = '';
            el('invoiceItemsPrint').innerHTML = '';

            if (!order) {
                itemsEmpty.style.display = 'block';
                el('invoiceSubtotal').textContent = '0.00';
                el('invoiceTax').textContent = '0.00';
                el('invoiceTotalBig').textContent = '0.00';
                el('remainingAmount').textContent = '0.00';
                el('changeAmount').textContent = '0.00';
                el('amountPaid').value = '0.00';
                return;
            }

            itemsEmpty.style.display = 'none';

            const subtotal = order.items.reduce((s, it) => s + it.price * it.qty, 0);
            const tax = 0;
            const total = subtotal + tax;

            el('invoiceSubtotal').textContent = money(subtotal);
            el('invoiceTax').textContent = money(tax);
            el('invoiceTotalBig').textContent = money(total);

            // printable items
            const rows = order.items.map(it => {
                return `<div class="d-flex justify-content-between align-items-start gap-3 mb-1">
                <div>
                    <div class="fw-semibold">${it.name}</div>
                    <div class="small-muted small">${it.qty} × ${money(it.price)}</div>
                </div>
                <div class="mono fw-semibold">${money(it.price * it.qty)}</div>
            </div>`;
            });

            itemsWrap.innerHTML = rows.join('');
            el('invoiceItemsPrint').innerHTML = rows.join('');

            // update printable header
            el('invoiceIdPrint').textContent = `INV-${String(order.id).slice(-4)}`;
            el('invoiceBillForPrint').textContent = `${order.tableName}`;
            el('invoiceSubtotalPrint').textContent = money(subtotal);
            el('invoiceTaxPrint').textContent = money(tax);
            el('invoiceTotalPrint').textContent = money(total);
            el('invoicePrintedAtPrint').textContent = new Date().toLocaleString();

            // payment print values updated on payment
            el('invoicePayMethodPrint').textContent = el('payMethod').value;
            el('invoicePaidPrint').textContent = '0.00';
            el('invoiceChangePrint').textContent = '0.00';

            updatePaymentNumbers();
        };

        const updatePaymentNumbers = () => {
            const total = Number(el('invoiceTotalBig').textContent || 0);
            const paid = Number(el('amountPaid').value || 0);
            const remaining = Math.max(0, total - paid);
            const change = Math.max(0, paid - total);

            el('remainingAmount').textContent = money(remaining);
            el('changeAmount').textContent = money(change);

            // keep print updated
            el('invoicePayMethodPrint').textContent = el('payMethod').value;
            el('invoicePaidPrint').textContent = money(paid);
            el('invoiceChangePrint').textContent = money(change);
        };

        // ---------- Actions ----------
        const setSelectedOrder = (id) => {
            const o = state.orders.find(x => x.id === id);
            if (!o || o.status !== 'Active') return;
            state.selectedOrderId = id;
            renderOrders();
            renderInvoiceForSelectedOrder();
        };

        const resetDemo = () => {
            state.selectedOrderId = null;
            // reset statuses
            state.orders.forEach(o => {
                if (o.status !== 'Active') o.status = 'Active';
            });
            renderOrders();
            renderInvoiceForSelectedOrder();
            el('payMethod').value = 'Cash';
            el('amountPaid').value = '0.00';
            updatePaymentNumbers();
        };

        const processPayment = () => {
            const order = state.orders.find(o => o.id === state.selectedOrderId);
            if (!order) {
                alert('Select an active order first.');
                return;
            }

            const total = Number(order.total || 0);
            const paid = Number(el('amountPaid').value || 0);

            if (paid < total) {
                alert(`Paid amount is less than total. Remaining: ${money(total - paid)}`);
                return;
            }

            const change = Math.max(0, paid - total);

            // update order status
            order.status = 'Paid';

            // update printable payment fields
            el('invoicePayMethodPrint').textContent = el('payMethod').value;
            el('invoicePaidPrint').textContent = money(paid);
            el('invoiceChangePrint').textContent = money(change);
            el('invoicePrintedAtPrint').textContent = new Date().toLocaleString();

            // re-render
            renderOrders();
            // keep selected order view as paid but disable payment actions by resetting selection
            state.selectedOrderId = null;
            renderInvoiceForSelectedOrder();
            el('amountPaid').value = '0.00';
            updatePaymentNumbers();

            // auto print
            setTimeout(() => {
                const printable = el('printInvoice');
                printable.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                window.print();
            }, 200);
        };

        const printInvoice = () => {
            const order = state.orders.find(o => o.id === state.selectedOrderId);
            if (!order) {
                alert('Select an active order to print.');
                return;
            }
            window.print();
        };

        // ---------- Events ----------
        el('statusFilter').addEventListener('change', renderOrders);
        el('btnResetDemo').addEventListener('click', resetDemo);
        el('btnProcessPayment').addEventListener('click', processPayment);
        el('btnPrint').addEventListener('click', printInvoice);

        el('amountPaid').addEventListener('input', updatePaymentNumbers);
        el('payMethod').addEventListener('change', updatePaymentNumbers);

        document.addEventListener('click', (e) => {
            const card = e.target.closest('[data-order-id]');
            if (!card) return;
            const id = Number(card.dataset.orderId);
            setSelectedOrder(id);
        });

        // ---------- Init ----------
        const init = () => {
            el('amountPaid').value = '0.00';
            el('payMethod').value = 'Cash';
            renderOrders();
            renderInvoiceForSelectedOrder();
            updatePaymentNumbers();
        };

        init();
    </script>

</body>

</html>
