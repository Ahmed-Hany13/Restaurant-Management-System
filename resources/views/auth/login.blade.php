<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Restaurant System') }} - Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <style>
        body {
            /* Restaurant-themed background: dark boards + subtle "spot" highlights */
            background:
                radial-gradient(900px circle at 15% 15%, rgba(220, 53, 69, 0.22), transparent 40%),
                radial-gradient(700px circle at 85% 20%, rgba(13, 110, 253, 0.18), transparent 45%),
                radial-gradient(1000px circle at 50% 100%, rgba(52, 87, 213, 0.20), transparent 55%),
                linear-gradient(180deg, #060910 0%, #0b1220 55%, #0a1021 100%);
            background-color: #0b1220;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            color: #e9eef8;
            position: relative;
            overflow-x: hidden;
        }

        /* Subtle "table" dots */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                radial-gradient(rgba(255, 255, 255, 0.10) 1px, transparent 1px);
            background-size: 26px 26px;
            opacity: 0.08;
        }

        /* Soft vignette */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(circle at 50% 20%, transparent 0%, rgba(0, 0, 0, 0.45) 70%, rgba(0, 0, 0, 0.65) 100%);
        }

        .rm-register-card {
            width: 100%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .rm-register-header {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.10);
            background: linear-gradient(90deg, #0d6efd 0%, #3457d5 100%);
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }

        .rm-input-group .input-group-text {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.9);
        }

        .rm-register-card .form-control,
        .rm-register-card .form-select {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.14);
            color: #fff;
        }

        .rm-register-card .form-control::placeholder {
            color: rgba(255, 255, 255, 0.45);
        }

        .rm-register-card label {
            color: rgba(255, 255, 255, 0.92);
        }

        /* Make sure select dropdown values are readable */
        .rm-register-card .form-select option {
            color: #0b1220;
        }

        /* Force select to behave like a dropdown (not floating/scrolling too much) */
        .rm-register-card select.form-select {
            appearance: auto;
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
        }

        .rm-register-actions .btn {
            border-radius: 12px;
        }

        .invalid-feedback {
            color: #ffb4b4;
        }
    </style>
</head>

<body>
    <div class="rm-register-card">
        <div class="rm-register-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-0">Restaurant Accounts</h4>
                </div>
                <div class="text-white" style="font-size: 28px;">
                    <i class="bi bi-shop-window"></i>
                </div>
            </div>
        </div>

        <div class="p-4">
            <form method="POST" action="{{ route('login') }}" class="mt-3">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <div class="rm-input-group input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" autocomplete="username" />
                    </div>
                    @error('email')
                        <div class="invalid-feedback mt-1">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="rm-input-group input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" />
                        <button type="button" class="btn btn-outline-light" data-toggle-password="password"
                            aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <input type="checkbox" name="remember"> Remember Me
                <div class="rm-register-actions d-flex align-items-center justify-content-between mt-4 gap-2">
                    <a href="{{ route('register') }}" class="btn btn-outline-light">
                        <i class="bi bi-arrow-left-circle me-1"></i> Create Account
                    </a>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        (function() {
            const buttons = document.querySelectorAll('[data-toggle-password]');
            buttons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const inputId = btn.getAttribute('data-toggle-password');
                    const input = document.getElementById(inputId);
                    if (!input) return;

                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';

                    const icon = btn.querySelector('i');
                    if (!icon) return;

                    icon.classList.toggle('bi-eye', !isHidden);
                    icon.classList.toggle('bi-eye-slash', isHidden);
                });
            });
        })();
    </script>
</body>

</html>
