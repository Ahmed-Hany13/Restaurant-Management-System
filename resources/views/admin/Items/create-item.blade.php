@extends('layouts.app')

@section('title', 'Create Menu Item')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Menu Items</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('item.index') }}">Items</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Item</li>
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
                                <h3 class="card-title">Create New Menu Item</h3>
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

                                <form method="POST" action="{{ route('item.store') }}" enctype="multipart/form-data"
                                    class="needs-validation" novalidate>
                                    @csrf
                                    @method('post')
                                    <!-- Name -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                            <input id="name" type="text" name="name" value="{{ old('name') }}"
                                                autofocus class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Enter item name (max 200 characters)" maxlength="200" required>
                                        </div>
                                    </div>

                                    <!-- Section, Category, Subcategory -->
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="menu_section_id" class="form-label">Section <span
                                                    class="text-danger">*</span></label>
                                            <select id="menu_section_id" name="menu_section_id"
                                                class="form-select @error('menu_section_id') is-invalid @enderror" required>
                                                <option value="" disabled
                                                    {{ old('menu_section_id') ? '' : 'selected' }}>Select section</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->id }}"
                                                        {{ (string) old('menu_section_id') === (string) $section->id ? 'selected' : '' }}>
                                                        {{ $section->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="menu_category_id" class="form-label">Category <span
                                                    class="text-danger">*</span></label>
                                            <select id="menu_category_id" name="menu_category_id"
                                                class="form-select @error('menu_category_id') is-invalid @enderror" required
                                                disabled>
                                                <option value="" disabled selected>Select category</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <label for="menu_subcategory_id" class="form-label">Subcategory <span
                                                    class="text-danger">*</span></label>
                                            <select id="menu_subcategory_id" name="menu_subcategory_id"
                                                class="form-select @error('menu_subcategory_id') is-invalid @enderror"
                                                required disabled>
                                                <option value="" disabled selected>Select subcategory</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea id="description" name="description" rows="3"
                                            class="form-control @error('description') is-invalid @enderror"
                                            placeholder="Enter item description (max 500 characters)" maxlength="500">{{ old('description') }}</textarea>
                                        <small class="text-muted">Optional - max 500 characters</small>
                                    </div>

                                    <!-- Price and Preparation Time -->
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="price" class="form-label">Price <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                                                <input id="price" type="number" name="price"
                                                    value="{{ old('price') }}"
                                                    class="form-control @error('price') is-invalid @enderror"
                                                    placeholder="0.00" step="0.01" min="0" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="preparation_time" class="form-label">Preparation Time
                                                (minutes)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                                <input id="preparation_time" type="number" name="preparation_time"
                                                    value="{{ old('preparation_time') }}"
                                                    class="form-control @error('preparation_time') is-invalid @enderror"
                                                    placeholder="e.g. 15" step="1" min="0">
                                            </div>
                                            <small class="text-muted">Optional</small>
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <select id="status" name="status"
                                            class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="" disabled {{ old('status') ? '' : 'selected' }}>Select
                                                status</option>
                                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i> Create Item
                                        </button>
                                        <a href="{{ route('item.index') }}" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-1"></i> Cancel
                                        </a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const sectionSelect = document.getElementById('menu_section_id');
            const categorySelect = document.getElementById('menu_category_id');
            const subcategorySelect = document.getElementById('menu_subcategory_id');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const imageUploadArea = document.getElementById('imageUploadArea');
            const dropZone = document.getElementById('dropZone');

            // Fetch categories based on section
            sectionSelect.addEventListener('change', async function() {
                const sectionId = this.value;
                categorySelect.innerHTML =
                    '<option value="" disabled selected>Select category</option>';
                categorySelect.disabled = true;
                subcategorySelect.innerHTML =
                    '<option value="" disabled selected>Select subcategory</option>';
                subcategorySelect.disabled = true;

                if (sectionId) {
                    try {
                        const response = await fetch(`/api/categories?section_id=${sectionId}`);
                        const categories = await response.json();
                        categorySelect.disabled = false;

                        categories.forEach(category => {
                            const option = document.createElement('option');
                            option.value = category.id;
                            option.textContent = category.name;
                            categorySelect.appendChild(option);
                        });

                        // If editing and have old value, select it
                        const oldCategoryId = '{{ old('menu_category_id') }}';
                        if (oldCategoryId) {
                            categorySelect.value = oldCategoryId;
                            categorySelect.dispatchEvent(new Event('change'));
                        }
                    } catch (error) {
                        console.error('Error fetching categories:', error);
                    }
                }
            });

            // Fetch subcategories based on category
            categorySelect.addEventListener('change', async function() {
                const categoryId = this.value;
                subcategorySelect.innerHTML =
                    '<option value="" disabled selected>Select subcategory</option>';
                subcategorySelect.disabled = true;

                if (categoryId) {
                    try {
                        const response = await fetch(`/api/subcategories?category_id=${categoryId}`);
                        const subcategories = await response.json();
                        subcategorySelect.disabled = false;

                        subcategories.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.name;
                            subcategorySelect.appendChild(option);
                        });

                        // If editing and have old value, select it
                        const oldSubcategoryId = '{{ old('menu_subcategory_id') }}';
                        if (oldSubcategoryId) {
                            subcategorySelect.value = oldSubcategoryId;
                        }
                    } catch (error) {
                        console.error('Error fetching subcategories:', error);
                    }
                }
            });

            // Trigger on page load if section is pre-selected
            if (sectionSelect.value) {
                sectionSelect.dispatchEvent(new Event('change'));
            }

            // Image upload functionality
            // Click on dropZone to open file picker
            dropZone.addEventListener('click', function(e) {
                if (e.target !== imageInput) {
                    imageInput.click();
                }
            });

            // File input change handler
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    displayImagePreview(file);
                } else if (file) {
                    alert('Please select a valid image file');
                    imageInput.value = '';
                }
            });

            // Display image preview
            function displayImagePreview(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    imageUploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }

            // Drag and drop functionality
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.style.borderColor = '#0d6efd';
                dropZone.style.backgroundColor = 'rgba(13, 110, 253, 0.1)';
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.style.borderColor = '#dee2e6';
                dropZone.style.backgroundColor = 'transparent';
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.style.borderColor = '#dee2e6';
                dropZone.style.backgroundColor = 'transparent';

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        imageInput.files = files;
                        displayImagePreview(file);
                    } else {
                        alert('Please drop an image file');
                    }
                }
            });

            // Bootstrap form validation
            const form = document.querySelector('.needs-validation');
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>

    <style>
        .border-dashed {
            border: 2px dashed #dee2e6 !important;
        }

        #dropZone {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #dropZone:hover {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
            transform: scale(1.01);
        }

        #dropZone.dragover {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }

        .input-group-text {
            background-color: #f8f9fa;
        }

        .form-select:disabled {
            background-color: #e9ecef;
            opacity: 1;
            cursor: not-allowed;
        }

        #imagePreview {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
