<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kitchen</title>

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

        .small-muted {
            color: rgba(0, 0, 0, .55);
        }

        .list-row {
            border-bottom: 1px dashed rgba(0, 0, 0, .08);
            padding: 12px 0;
        }

        .list-row:last-child {
            border-bottom: 0;
        }

        .mono {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }

        .badge-soft {
            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .6rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .status-awaiting {
            background: rgba(13, 110, 253, .12);
            color: #0d6efd;
        }

        .status-ready {
            background: rgba(25, 135, 84, .12);
            color: #198754;
        }

        .status-completed {
            background: rgba(108, 117, 125, .12);
            color: #6c757d;
        }

        .rm-kitchen-actions button {
            min-width: 120px;
        }

        .empty-state {
            padding: 18px;
            border-radius: 12px;
            border: 1px dashed rgba(0, 0, 0, .12);
            background: rgba(255, 255, 255, .6);
        }
    </style>
</head>

<body>

    <div class="rm-topbar">
        <div class="container py-3 d-flex align-items-center justify-content-between gap-2 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                    style="width:40px;height:40px;">
                    <i class="bi bi-flame" style="font-size:1.1rem"></i>
                </div>
                <div>
                    <div class="fw-bold">Kitchen</div>
                    <div class="small-muted small">Kitchen orders only • update status</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @auth
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-house me-1"></i> Home
                    </a>
                    <a href="{{ route('billing_page') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-card-list me-1"></i> Billings
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
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Log in
                    </a>
                @endauth
            </div>
        </div>
    </div>

    @include('components.session-messages')

    <div class="container py-4">
        <div class="row g-3">
            <!-- KITCHEN LIST -->
            <div class="col-12 col-xl-8">
                <div class="rm-panel h-100">
                    <div class="rm-panel-head d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="fw-bold fs-5"><i class="bi bi-kanban me-1"></i> Orders</div>
                            <div class="small-muted small">Only orders where <span class="mono">status
                                    is 'Active'</span></div>
                        </div>
                        <div class="text-end">
                            <div class="small-muted small">Kitchen updates are instant (demo)</div>
                            <div class="fw-bold mono" style="font-size:1.05rem;">Count: <span id="kitchenCount">0</span>
                            </div>
                        </div>
                    </div>

                    <div class="rm-panel-body">
                        <div class="d-flex gap-2 flex-wrap align-items-center mb-3">
                            <button id="btnMarkAllReady" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-check2-circle me-1"></i> Mark all Ready
                            </button>
                            <button id="btnMarkAllCompleted" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-box-seam me-1"></i> Mark all Completed
                            </button>
                            <button id="btnResetDemo" class="btn btn-outline-danger btn-sm ms-auto">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset demo
                            </button>
                        </div>

                        <div id="ordersWrap"></div>
                        <div id="ordersEmpty" class="empty-state small-muted" style="display:none;">
                            <div class="fw-semibold mb-1" style="color: rgba(0,0,0,.7);">
                                No kitchen orders
                            </div>
                            <div class="small">Create orders from <span class="mono">Orders</span> page to see them
                                here (demo).</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ORDER DETAILS / LEGEND -->
            <div class="col-12 col-xl-4">
                <div class="rm-panel h-100">
                    <div class="rm-panel-head">
                        <div class="fw-bold fs-5"><i class="bi bi-info-circle me-1"></i> Legend</div>
                        <div class="small-muted small">Status mapping (demo)</div>
                    </div>
                    <div class="rm-panel-body">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <span class="status-badge status-awaiting"><i class="bi bi-hourglass-split"></i>
                                    Active</span>
                                <span class="small-muted">Needs kitchen</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <span class="status-badge status-ready"><i class="bi bi-check2"></i> Ready</span>
                                <span class="small-muted">Ready to serve</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <span class="status-badge status-completed"><i class="bi bi-clipboard-check"></i>
                                    Completed</span>
                                <span class="small-muted">Done</span>
                            </div>
                        </div>

                        <hr class="my-3" />

                        <div class="alert alert-info mb-0" role="alert">
                            <div class="d-flex gap-2 align-items-start">
                                <i class="bi bi-lightning-charge"></i>
                                <div>
                                    <div class="fw-semibold">How to use</div>
                                    <div class="small">
                                        Use <b>Mark Prepared</b> to move <span class="mono">Active → Ready</span>,
                                        then <b>Mark Served</b> to move <span class="mono">Ready → Completed</span>.
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
        const MENU_ITEM_MAP = {
            1: {
                name: 'Chicken Shawarma',
                price: 80.00
            },
            2: {
                name: 'Beef Burger',
                price: 120.00
            },
            3: {
                name: 'Caesar Salad',
                price: 90.00
            },
            4: {
                name: 'Margherita Pizza',
                price: 160.00
            },
            5: {
                name: 'Pasta Alfredo',
                price: 140.00
            },
            6: {
                name: 'Fresh Juice',
                price: 60.00
            },
            7: {
                name: 'Chocolate Cake',
                price: 110.00
            },
            8: {
                name: 'Espresso',
                price: 50.00
            }
        };

        const money = (n) => (Number(n) || 0).toFixed(2);

        // status values are intentionally limited for the demo
        // kitchen orders only: status === 'Active'
        const Status = {
            ACTIVE: 'Active',
            READY: 'Ready',
            COMPLETED: 'Completed'
        };

        const makeOrder = ({
            id,
            tableLabel,
            createdAt,
            status,
            items
        }) => {
            const mappedItems = items.map(it => {
                const mi = MENU_ITEM_MAP[it.menuItemId];
                return {
                    menuItemId: it.menuItemId,
                    name: mi?.name ?? ('Item #' + it.menuItemId),
                    qty: it.qty,
                    price: mi?.price ?? 0
                };
            });

            const total = mappedItems.reduce((sum, it) => sum + (Number(it.price) * Number(it.qty)), 0);

            return {
                id,
                tableLabel,
                createdAt,
                status,
                items: mappedItems,
                total
            };
        };

        const initialState = () => ({
            orders: [
                makeOrder({
                    id: 101,
                    tableLabel: 'Table 3',
                    createdAt: new Date(Date.now() - 7 * 60 * 1000).toISOString(),
                    status: Status.ACTIVE,
                    items: [{
                        menuItemId: 1,
                        qty: 2
                    }, {
                        menuItemId: 6,
                        qty: 1
                    }]
                }),
                makeOrder({
                    id: 102,
                    tableLabel: 'Table 1',
                    createdAt: new Date(Date.now() - 12 * 60 * 1000).toISOString(),
                    status: Status.READY,
                    items: [{
                        menuItemId: 4,
                        qty: 1
                    }]
                }),
                makeOrder({
                    id: 103,
                    tableLabel: 'Table 6',
                    createdAt: new Date(Date.now() - 4 * 60 * 1000).toISOString(),
                    status: Status.ACTIVE,
                    items: [{
                        menuItemId: 5,
                        qty: 2
                    }, {
                        menuItemId: 8,
                        qty: 2
                    }]
                }),
                makeOrder({
                    id: 104,
                    tableLabel: 'Table 2',
                    createdAt: new Date(Date.now() - 18 * 60 * 1000).toISOString(),
                    status: Status.COMPLETED,
                    items: [{
                        menuItemId: 3,
                        qty: 1
                    }]
                })
            ]
        });

        const state = initialState();

        const fmtTime = (iso) => {
            try {
                return new Date(iso).toLocaleString();
            } catch (e) {
                return iso;
            }
        };

        const statusBadge = (status) => {
            if (status === Status.ACTIVE) {
                return `<span class="status-badge status-awaiting"><i class="bi bi-hourglass-split"></i> Active</span>`;
            }
            if (status === Status.READY) {
                return `<span class="status-badge status-ready"><i class="bi bi-check2"></i> Ready</span>`;
            }
            return `<span class="status-badge status-completed"><i class="bi bi-clipboard-check"></i> Completed</span>`;
        };

        const computeKitchenOrders = () => {
            // kitchen orders only
            return state.orders.filter(o => o.status === Status.ACTIVE);
        };

        const renderOrders = () => {
            const wrap = document.getElementById('ordersWrap');
            const empty = document.getElementById('ordersEmpty');
            const kitchenOrders = computeKitchenOrders();

            document.getElementById('kitchenCount').textContent = kitchenOrders.length;

            wrap.innerHTML = '';

            if (!kitchenOrders.length) {
                empty.style.display = 'block';
                return;
            }
            empty.style.display = 'none';

            kitchenOrders.forEach((o) => {
                const div = document.createElement('div');
                div.className = 'list-row';

                const itemsHtml = o.items.map(it => {
                    return `<div class="small-muted small">${it.qty}× ${it.name}</div>`;
                }).join('');

                div.innerHTML = `
                    <div class="d-flex align-items-start justify-content-between gap-3">
                        <div>
                            <div class="fw-semibold">Order #${o.id} <span class="small-muted">• ${o.tableLabel}</span></div>
                            <div class="mt-1">${statusBadge(o.status)}</div>
                            <div class="small-muted small mt-1">Created: ${fmtTime(o.createdAt)}</div>
                            <div class="mt-2">${itemsHtml}</div>
                        </div>
                        <div class="text-end rm-kitchen-actions">
                            <div class="mono fw-semibold">${money(o.total)}</div>
                            <div class="mt-2 d-flex flex-column gap-2 align-items-end">
                                <button class="btn btn-sm btn-outline-success"
                                    data-action="mark-ready"
                                    data-order-id="${o.id}">
                                    <i class="bi bi-check2-circle me-1"></i> Mark Prepared
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                wrap.appendChild(div);
            });
        };

        const markOrder = (orderId, newStatus) => {
            const o = state.orders.find(x => x.id === orderId);
            if (!o) return;
            o.status = newStatus;
            renderOrders();
        };

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;

            const action = btn.getAttribute('data-action');
            const orderId = Number(btn.getAttribute('data-order-id'));

            if (action === 'mark-ready') {
                markOrder(orderId, Status.READY);
            }
        });

        document.getElementById('btnMarkAllReady').addEventListener('click', () => {
            const kitchenOrders = computeKitchenOrders();
            kitchenOrders.forEach(o => (o.status = Status.READY));
            renderOrders();
        });

        document.getElementById('btnMarkAllCompleted').addEventListener('click', () => {
            // mark ALL Ready orders as Completed
            state.orders.forEach(o => {
                if (o.status === Status.READY) o.status = Status.COMPLETED;
            });
            renderOrders();
        });

        document.getElementById('btnResetDemo').addEventListener('click', () => {
            Object.assign(state, initialState());
            renderOrders();
        });

        // Boot
        renderOrders();
    </script>
</body>

</html>
