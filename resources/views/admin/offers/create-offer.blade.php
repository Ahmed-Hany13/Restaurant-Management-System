@extends('layouts.app')
@section('title', 'Create Offer')
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
                            <li class="breadcrumb-item active" aria-current="page">Create Offer</li>
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
                                <h3 class="card-title">Create / Manage Offers</h3>
                                <div class="card-tools">
                                    <span class="badge text-bg-light">Admin</span>
                                </div>
                            </div>
                            @include('components.session-messages')
                            <div class="card-body">
                                <div class="row g-4">
                                    {{-- Create form --}}
                                    <div class="col-lg-5">
                                        <div class="mb-3">
                                            <h5 class="mb-1">New Offer</h5>
                                        </div>
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
                                        <form method="POST" action="{{ route('offers.store') }}" class="needs-validation"
                                            novalidate>
                                            @csrf

                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                                    <input id="name" type="text" name="name"
                                                        value="{{ old('name') }}" autofocus
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        autocomplete="off" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea id="description" name="description" rows="3"
                                                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="discount_type" class="form-label">Discount Type <span
                                                        class="text-danger">*</span></label>
                                                <select id="discount_type" name="discount_type"
                                                    class="form-select @error('discount_type') is-invalid @enderror"
                                                    required>
                                                    <option value="" disabled
                                                        {{ old('discount_type') ? '' : 'selected' }}>
                                                        Select discount type</option>
                                                    <option value="percentage"
                                                        {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
                                                        Percentage
                                                        (1-100%)</option>
                                                    <option value="fixed"
                                                        {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Fixed
                                                        Amount
                                                    </option>
                                                </select>
                                            </div>



                                            <div class="col-md-6 mb-3">
                                                <label for="discount_value" class="form-label">Discount Value <span
                                                        class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="discount_value_symbol">%</span>
                                                    <input id="discount_value" type="number" name="discount_value"
                                                        value="{{ old('discount_value') }}"
                                                        class="form-control @error('discount_value') is-invalid @enderror"
                                                        placeholder="Enter discount value" step="0.01" required>
                                                </div>
                                                <small class="text-muted d-block mt-1" id="discount_value_help">
                                                    Enter value between 1 and 100
                                                </small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select id="status" name="status"
                                                    class="form-select @error('status') is-invalid @enderror">
                                                    <option value="" disabled {{ old('status') ? '' : 'selected' }}>
                                                        Select status</option>
                                                    <option value="active"
                                                        {{ old('status') === 'active' ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="inactive"
                                                        {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive
                                                    </option>
                                                </select>
                                            </div>

                                            {{-- Validity Period Section --}}
                                            <div class="mb-3 pb-3 border-bottom">
                                                <h6 class="mb-3">Validity Period</h6>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                                        <input id="start_date" type="date" name="start_date"
                                                            value="{{ old('start_date') }}"
                                                            class="form-control @error('start_date') is-invalid @enderror"
                                                            required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                                        <input id="end_date" type="date" name="end_date"
                                                            value="{{ old('end_date') }}"
                                                            class="form-control @error('end_date') is-invalid @enderror"
                                                            required>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="start_time" class="form-label">Start Time <span class="text-muted">(optional)</span></label>
                                                        <input id="start_time" type="time" name="start_time"
                                                            value="{{ old('start_time') }}"
                                                            class="form-control @error('start_time') is-invalid @enderror">
                                                        <small class="text-muted">e.g., 18:00 for happy hour</small>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="end_time" class="form-label">End Time <span class="text-muted">(optional)</span></label>
                                                        <input id="end_time" type="time" name="end_time"
                                                            value="{{ old('end_time') }}"
                                                            class="form-control @error('end_time') is-invalid @enderror">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Applicable Days</label>
                                                    <div class="row">
                                                        @php
                                                            $days = [1 => 'Sat', 2 => 'Sun', 3 => 'Mon', 4 => 'Tue', 5 => 'Wed', 6 => 'Thu', 7 => 'Fri'];
                                                            $selectedDays = old('applicable_days') ? explode(',', old('applicable_days')) : [];
                                                        @endphp
                                                        @foreach($days as $dayNum => $dayName)
                                                            <div class="col-6 col-sm-4 mb-2">
                                                                <div class="form-check">
                                                                    <input class="form-check-input applicable-day-checkbox" type="checkbox"
                                                                        name="applicable_days[]" value="{{ $dayNum }}"
                                                                        id="day_{{ $dayNum }}"
                                                                        {{ in_array($dayNum, $selectedDays) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="day_{{ $dayNum }}">
                                                                        {{ $dayName }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" id="selected_items_input" name="menu_items" value="">

                                            <div class="d-flex align-items-center justify-content-between gap-2 mt-4">
                                                <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                                    <i class="bi bi-arrow-left-circle me-1"></i> Back
                                                </a>

                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-plus-lg me-1"></i> Create Offer
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Applicable Items Section --}}
                                    <div class="col-lg-7">
                                        <div class="mb-3">
                                            <h5 class="mb-1">Applicable Items <span class="text-danger">*</span></h5>
                                            <small class="text-muted">Select at least 1 item to apply this offer</small>
                                        </div>

                                        {{-- Search Box --}}
                                        <div class="input-group mb-3">
                                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                                            <input type="text" id="itemSearchInput" class="form-control"
                                                placeholder="Search menu items..." autocomplete="off">
                                        </div>

                                        {{-- Available Items --}}
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Available Items</label>
                                            <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                                                @forelse($menuItems as $item)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input menu-item-checkbox" type="checkbox"
                                                            id="item_{{ $item->id }}"
                                                            data-item-id="{{ $item->id }}"
                                                            data-item-name="{{ $item->name }}"
                                                            data-item-category="{{ $item->menuSubcategory->name ?? '' }}"
                                                            data-item-price="{{ $item->price }}"
                                                            data-search-text="{{ strtolower($item->name . ' ' . ($item->menuSubcategory->name ?? '')) }}">
                                                        <label class="form-check-label" for="item_{{ $item->id }}">
                                                            <span class="fw-semibold">{{ $item->name }}</span>
                                                            <span class="text-muted">({{ $item->menuSubcategory->name ?? 'N/A' }})</span>
                                                            <span class="badge bg-success ms-2">${{ number_format($item->price, 2) }}</span>
                                                        </label>
                                                    </div>
                                                @empty
                                                    <div class="text-muted text-center py-3">
                                                        <i class="bi bi-inbox"></i> No active menu items available
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>

                                        {{-- Selected Items Preview --}}
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Selected Items (<span id="selected_count">0</span>)</label>
                                            <div id="selectedItemsContainer" class="border rounded p-3 bg-white" style="max-height: 400px; overflow-y: auto;">
                                                <div class="text-muted text-center py-3">
                                                    <i class="bi bi-check-circle"></i> No items selected yet
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Validation Message --}}
                                        <div id="itemsValidationMessage" class="alert alert-warning d-none">
                                            <i class="bi bi-exclamation-triangle me-2"></i>Please select at least 1 item
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function() {
            // ===== Discount Type & Value Validation =====
            const discountTypeSelect = document.getElementById('discount_type');
            const discountValueInput = document.getElementById('discount_value');
            const discountValueSymbol = document.getElementById('discount_value_symbol');
            const discountValueHelp = document.getElementById('discount_value_help');

            function updateDiscountValueValidation() {
                const type = discountTypeSelect.value;
                if (type === 'percentage') {
                    discountValueInput.min = '1';
                    discountValueInput.max = '100';
                    discountValueInput.step = '1';
                    discountValueSymbol.textContent = '%';
                    discountValueHelp.textContent = 'Enter value between 1 and 100';
                } else if (type === 'fixed') {
                    discountValueInput.min = '0';
                    discountValueInput.max = '';
                    discountValueInput.step = '0.01';
                    discountValueSymbol.textContent = '$';
                    discountValueHelp.textContent = 'Enter any positive amount';
                }
            }

            discountTypeSelect.addEventListener('change', updateDiscountValueValidation);
            if (discountTypeSelect.value) {
                updateDiscountValueValidation();
            }

            // ===== Applicable Items Multi-Select =====
            const selectedItems = new Map();
            const searchInput = document.getElementById('itemSearchInput');
            const checkboxes = document.querySelectorAll('.menu-item-checkbox');
            const selectedItemsContainer = document.getElementById('selectedItemsContainer');
            const selectedCountSpan = document.getElementById('selected_count');
            const selectedItemsInput = document.getElementById('selected_items_input');
            const itemsValidationMessage = document.getElementById('itemsValidationMessage');

            function calculateDiscountedPrice(originalPrice, discountValue, discountType) {
                if (discountType === 'percentage') {
                    return (originalPrice * (100 - discountValue) / 100).toFixed(2);
                } else if (discountType === 'fixed') {
                    return Math.max(0, (originalPrice - discountValue)).toFixed(2);
                }
                return originalPrice.toFixed(2);
            }

            function renderSelectedItems() {
                if (selectedItems.size === 0) {
                    selectedItemsContainer.innerHTML = `
                        <div class="text-muted text-center py-3">
                            <i class="bi bi-check-circle"></i> No items selected yet
                        </div>
                    `;
                    selectedCountSpan.textContent = '0';
                    itemsValidationMessage.classList.remove('d-none');
                    return;
                }

                itemsValidationMessage.classList.add('d-none');
                selectedCountSpan.textContent = selectedItems.size;

                let html = '';
                selectedItems.forEach((item, itemId) => {
                    const discountType = discountTypeSelect.value;
                    const discountValue = parseFloat(discountValueInput.value) || 0;
                    const discountedPrice = calculateDiscountedPrice(item.price, discountValue, discountType);

                    html += `
                        <div class="border rounded p-2 mb-2 bg-light">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">${item.name}</div>
                                    <small class="text-muted">${item.category}</small>
                                    <div class="mt-2">
                                        <span class="badge bg-secondary">Original: $${item.price.toFixed(2)}</span>
                                        <span class="badge bg-success ms-2">
                                            <s>$${item.price.toFixed(2)}</s> → $${discountedPrice}
                                        </span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-item-btn"
                                    data-item-id="${itemId}">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });

                selectedItemsContainer.innerHTML = html;
                attachRemoveButtonListeners();
                updateSelectedItemsInput();
            }

            function attachRemoveButtonListeners() {
                selectedItemsContainer.querySelectorAll('.remove-item-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const itemId = parseInt(this.dataset.itemId);
                        removeItem(itemId);
                    });
                });
            }

            function addItem(itemId, itemName, itemCategory, itemPrice) {
                selectedItems.set(itemId, {
                    id: itemId,
                    name: itemName,
                    category: itemCategory,
                    price: parseFloat(itemPrice)
                });
                renderSelectedItems();
            }

            function removeItem(itemId) {
                selectedItems.delete(itemId);
                const checkbox = document.getElementById(`item_${itemId}`);
                if (checkbox) {
                    checkbox.removeEventListener('change', handleCheckboxChange);
                    checkbox.checked = false;
                    checkbox.addEventListener('change', handleCheckboxChange);
                }
                renderSelectedItems();
            }

            function handleCheckboxChange(e) {
                if (this.checked) {
                    addItem(
                        this.dataset.itemId,
                        this.dataset.itemName,
                        this.dataset.itemCategory,
                        this.dataset.itemPrice
                    );
                } else {
                    removeItem(parseInt(this.dataset.itemId));
                }
            }

            function updateSelectedItemsInput() {
                const itemIds = Array.from(selectedItems.keys());
                selectedItemsInput.value = itemIds.join(',');
            }

            // Handle checkbox changes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', handleCheckboxChange);
            });

            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                checkboxes.forEach(checkbox => {
                    const searchText = checkbox.dataset.searchText;
                    const parentDiv = checkbox.closest('.form-check');

                    if (searchText.includes(searchTerm)) {
                        parentDiv.style.display = '';
                    } else {
                        parentDiv.style.display = 'none';
                    }
                });
            });

            // Update preview when discount changes
            discountTypeSelect.addEventListener('change', renderSelectedItems);
            discountValueInput.addEventListener('input', renderSelectedItems);

            // Form submission validation
            document.querySelector('form').addEventListener('submit', function(e) {
                if (selectedItems.size === 0) {
                    e.preventDefault();
                    itemsValidationMessage.classList.remove('d-none');
                    alert('Please select at least one item for this offer.');
                }
            });
        })();
    </script>
@endsection
