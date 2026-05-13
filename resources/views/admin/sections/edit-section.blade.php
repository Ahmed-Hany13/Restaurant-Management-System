@extends('layouts.app')

@section('title', 'Edit Section')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Sections</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('section.index') }}">Sections</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                                <h3 class="card-title">Edit Section</h3>
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
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('section.update', $section->id) }}" class="needs-validation" novalidate>
                                    @csrf
                                    @method('PUT')

                                    <div class="row g-4">
                                        <div class="col-lg-7">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                                    <input id="name" type="text" name="name" value="{{ old('name', $section->name) }}"
                                                        autofocus
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        autocomplete="off" required>
                                                </div>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea id="description" name="description" rows="4"
                                                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $section->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-5">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="display_order" class="form-label">Display order</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-sort-numeric-up"></i></span>
                                                        <input id="display_order" type="number" name="display_order"
                                                            value="{{ old('display_order', $section->display_order ?? 1) }}"
                                                            class="form-control @error('display_order') is-invalid @enderror"
                                                            min="1" step="1" placeholder="e.g. 1" required>
                                                    </div>
                                                    @error('display_order')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                                        <option value="active" {{ old('status', $section->status) === 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ old('status', $section->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                    @error('status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between gap-2 mt-3">
                                                <a href="{{ route('section.index') }}" class="text-decoration-none">
                                                    <i class="bi bi-arrow-left-circle me-1"></i> Back
                                                </a>

                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-pencil me-1"></i> Update Section
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

