<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Management System</title>

    <!-- Bootstrap + Icons (for landing page only) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: radial-gradient(900px 450px at 10% 10%, rgba(245, 80, 3, 0.15), transparent 55%),
                radial-gradient(800px 420px at 90% 20%, rgba(248, 183, 3, 0.14), transparent 50%),
                radial-gradient(850px 420px at 50% 100%, rgba(32, 201, 151, 0.12), transparent 55%),
                #ffffff;
            min-height: 100vh;
        }

        .glass {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.06);
            border-radius: 18px;
        }

        .hero {
            padding: 44px 22px;
        }

        .feature {
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.7);
        }

        .feature i {
            width: 46px;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: rgba(13, 110, 253, 0.10);
            color: #0d6efd;
            font-size: 1.2rem;
        }

        .btn-pill {
            border-radius: 999px;
            padding: 10px 18px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light" style="background: transparent;">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ url('/') }}">
                <span class="d-inline-flex align-items-center gap-2">
                    <span
                        class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                        style="width:40px;height:40px;">
                        <i class="bi bi-shop" style="font-size:1.1rem"></i>
                    </span>
                    Restaurant System
                </span>
            </a>
            <div class="d-flex gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-pill">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-pill">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Log in
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('components.session-messages')
                <div class="glass hero">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-6">
                            <h1 class="fw-bold" style="letter-spacing: -0.02em;">
                                Restaurant Management System
                                <span class="text-primary">—</span>
                                Built for speed & clarity
                            </h1>
                            <p class="text-muted mt-3 mb-4">
                                Create staff accounts, manage orders, handle billing, and keep the kitchen in sync.
                                Role-based access helps each team focus on their work.
                            </p>

                            @auth
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-pill">
                                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                    </a>
                                    <a href="{{ route('create-staff') }}" class="btn btn-outline-primary btn-pill">
                                        <i class="bi bi-person-plus me-1"></i> Create Staff
                                    </a>
                                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-pill">
                                        <i class="bi bi-receipt me-1"></i> Orders
                                    </a>
                                    <a href="{{ route('kitchen') }}" class="btn btn-outline-secondary btn-pill">
                                        <i class="bi bi-fire me-1"></i> Kitchen
                                    </a>
                                </div>
                            @else
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-pill">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Log in
                                    </a>
                                </div>
                            @endauth

                            <div class="mt-4 text-muted small">
                                Tip: Start by creating staff accounts for Admin / Waiter / Cashier / Kitchen Staff.
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="feature p-3 h-100">
                                        <i class="bi bi-people"></i>
                                        <div class="mt-3 fw-semibold">Staff Management</div>
                                        <div class="text-muted small">Create accounts & assign roles.</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="feature p-3 h-100">
                                        <i class="bi bi-receipt"></i>
                                        <div class="mt-3 fw-semibold">Orders & Billing</div>
                                        <div class="text-muted small">Track orders and process payments.</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="feature p-3 h-100">
                                        <i class="bi bi-fire"></i>
                                        <div class="mt-3 fw-semibold">Kitchen View</div>
                                        <div class="text-muted small">Stay up-to-date in real-time.</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="feature p-3 h-100">
                                        <i class="bi bi-shield-lock"></i>
                                        <div class="mt-3 fw-semibold">Role-Based Access</div>
                                        <div class="text-muted small">Keep each team focused.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 text-muted small">
                    © {{ date('Y') }} Restaurant System. All rights reserved.
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
