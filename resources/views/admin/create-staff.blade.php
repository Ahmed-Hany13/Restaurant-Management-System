@extends('layouts.app')

@section('title', 'Create Staff')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Create Staff</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Staff</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-10">
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-header">
                                <h3 class="card-title">Staff Registration</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>

                                    <div class="card-body">

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <div class="fw-semibold">Please fix the following:</div>
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif



                                    <form method="POST" action="{{ route('create_staff') }}" class="needs-validation" novalidate>
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                    <input id="name" type="text" name="name" value="{{ old('name') }}" autofocus
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        autocomplete="name" required>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        autocomplete="username" required>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                    <input id="password" type="password" name="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        autocomplete="new-password" required>
                                                    <button type="button" class="btn btn-outline-secondary" data-toggle-password="password"
                                                        aria-label="Toggle password visibility">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                                        autocomplete="new-password" required>
                                                    <button type="button" class="btn btn-outline-secondary" data-toggle-password="password_confirmation"
                                                        aria-label="Toggle confirm password visibility">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="role" class="form-label">Role</label>
                                        <select id="role" name="role"
                                            class="form-select @error('role') is-invalid @enderror" required>
                                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select role</option>
                                            <option value="waiter" {{ old('role') === 'waiter' ? 'selected' : '' }}>Waiter</option>
                                            <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>Cashier</option>
                                            <option value="kitchen_staff" {{ old('role') === 'kitchen_staff' ? 'selected' : '' }}>Kitchen Staff</option>
                                        </select>

                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-2 mt-4">
                                        <a href="{{ route('login') }}" class="text-decoration-none">
                                            <i class="bi bi-arrow-left-circle me-1"></i> Back to Dashboard
                                        </a>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-person-plus me-1"></i> Register
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        (function () {
            const buttons = document.querySelectorAll('[data-toggle-password]');
            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const inputId = btn.getAttribute('data-toggle-password');
                    const input = document.getElementById(inputId);
                    if (!input) return;

                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';

                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('bi-eye', isHidden);
                        icon.classList.toggle('bi-eye-slash', !isHidden);
                    }
                });
            });
        })();
    </script>
@endsection

