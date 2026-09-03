@extends('layouts.app')

@section('title', 'Edit Table')

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
                            <li class="breadcrumb-item"><a href="{{ route('table.index') }}">Tables</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Table</li>
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
                                <h3 class="card-title">Edit Table</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>

                            @include('components.session-messages')

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

                                <form method="POST" action="{{ route('table.update', $table->id) }}" class="needs-validation" novalidate>
                                    @csrf
                                    @method('PUT')

                                    <!-- Table Number -->
                                    <div class="mb-3">
                                        <label for="table_number" class="form-label">Table Number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-table"></i></span>
                                            <input id="table_number" type="text" name="table_number" value="{{ old('table_number', $table->table_number) }}" autofocus
                                                class="form-control @error('table_number') is-invalid @enderror"
                                                placeholder="e.g., T01, VIP-1, A1" maxlength="50" required>
                                        </div>
                                        <small class="text-muted d-block mt-1">Alphanumeric only, must be unique</small>
                                    </div>

                                    <!-- Table Type -->
                                    <div class="mb-3">
                                        <label class="form-label">Table Type <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="type_private" name="type" value="private"
                                                {{ old('type', $table->type) === 'private' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="type_private">
                                                <strong>Private Table</strong>
                                                <small class="d-block text-muted">For reserved/VIP guests in a separate area</small>
                                            </label>
                                        </div>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="radio" id="type_public" name="type" value="public"
                                                {{ old('type', $table->type) === 'public' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="type_public">
                                                <strong>Public Table</strong>
                                                <small class="d-block text-muted">General seating area, first-come-first-served</small>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Capacity Section -->
                                    <div class="card card-light mb-3">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Seating Capacity</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Minimum Members -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="min_capacity" class="form-label">Minimum Members <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                                        <input id="min_capacity" type="number" name="min_capacity" value="{{ old('min_capacity', $table->min_capacity) }}"
                                                            class="form-control @error('min_capacity') is-invalid @enderror"
                                                            placeholder="e.g., 2" min="1" step="1" required>
                                                    </div>

                                                </div>

                                                <!-- Maximum Members -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="max_capacity" class="form-label">Maximum Members <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-people"></i></span>
                                                        <input id="max_capacity" type="number" name="max_capacity" value="{{ old('max_capacity', $table->max_capacity) }}"
                                                            class="form-control @error('max_capacity') is-invalid @enderror"
                                                            placeholder="e.g., 4" min="1" step="1" required>
                                                    </div>

                                                </div>
                                            </div>
                                            <small class="text-muted">Example: A table for 2-4 people</small>
                                        </div>
                                    </div>

                                    <!-- Location/Area -->
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location/Area</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                            <select id="location" name="location" class="form-select @error('location') is-invalid @enderror">
                                                <option value="">Select a location (Optional)</option>
                                                <option value="Ground Floor" {{ old('location', $table->location) === 'Ground Floor' ? 'selected' : '' }}>Ground Floor</option>
                                                <option value="First Floor" {{ old('location', $table->location) === 'First Floor' ? 'selected' : '' }}>First Floor</option>
                                                <option value="Garden" {{ old('location', $table->location) === 'Garden' ? 'selected' : '' }}>Garden</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-info-circle"></i></span>
                                            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                                <option value="">Select a status</option>
                                                <option value="available" {{ old('status', $table->status) === 'available' ? 'selected' : '' }}>Available</option>
                                                <option value="occupied" {{ old('status', $table->status) === 'occupied' ? 'selected' : '' }}>Occupied</option>
                                                <option value="reserved" {{ old('status', $table->status) === 'reserved' ? 'selected' : '' }}>Reserved</option>
                                                <option value="maintenance" {{ old('status', $table->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea id="notes" name="notes" rows="3"
                                            class="form-control @error('notes') is-invalid @enderror"
                                            placeholder="Add any additional information about this table..."
                                            maxlength="1000">{{ old('notes', $table->notes) }}</textarea>
                                        <small class="text-muted">Optional - max 1000 characters</small>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="d-flex gap-2 mt-4">
                                        <a href="{{ route('table.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-x-lg me-1"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-lg me-1"></i> Update Table
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Validate max capacity >= min capacity
        const minInput = document.getElementById('min_capacity');
        const maxInput = document.getElementById('max_capacity');

        function validateCapacity() {
            if (minInput.value && maxInput.value) {
                const min = parseInt(minInput.value);
                const max = parseInt(maxInput.value);

                if (max < min) {
                    maxInput.classList.add('is-invalid');
                } else {
                    maxInput.classList.remove('is-invalid');
                }
            }
        }

        minInput.addEventListener('change', validateCapacity);
        maxInput.addEventListener('change', validateCapacity);
    </script>
@endsection
