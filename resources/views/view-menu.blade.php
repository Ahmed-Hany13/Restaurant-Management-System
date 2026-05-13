@extends('layouts.app')

@section('title', 'Menu')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Menu</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Menu</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Sidebar Navigation -->
                    <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Browse Menu</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush" id="sectionsList">
                                    <!-- Sections will be loaded here -->
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div id="categoriesContainer" style="display: none;" class="mt-3">
                            <div class="card card-outline card-secondary">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Categories</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush" id="categoriesList">
                                        <!-- Categories will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subcategories -->
                        <div id="subcategoriesContainer" style="display: none;" class="mt-3">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Subcategories</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush" id="subcategoriesList">
                                        <!-- Subcategories will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Grid -->
                    <div class="col-lg-9 col-md-8">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <span id="currentSection">Select a Section</span>
                                    <span id="categoryBreadcrumb"></span>
                                    <span id="subcategoryBreadcrumb"></span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="itemsGrid" class="row g-3">
                                    <div class="col-12">
                                        <div class="alert alert-info" role="alert">
                                            <i class="bi bi-info-circle me-2"></i> Select a section from the left to browse items.
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectionsList = document.getElementById('sectionsList');
            const categoriesList = document.getElementById('categoriesList');
            const subcategoriesList = document.getElementById('subcategoriesList');
            const itemsGrid = document.getElementById('itemsGrid');
            const categoriesContainer = document.getElementById('categoriesContainer');
            const subcategoriesContainer = document.getElementById('subcategoriesContainer');
            const currentSection = document.getElementById('currentSection');
            const categoryBreadcrumb = document.getElementById('categoryBreadcrumb');
            const subcategoryBreadcrumb = document.getElementById('subcategoryBreadcrumb');

            let selectedSectionId = null;
            let selectedCategoryId = null;
            let selectedSubcategoryId = null;

            // Load sections
            loadSections();

            function loadSections() {
                fetch('/api/sections')
                    .then(response => response.json())
                    .then(sections => {
                        sectionsList.innerHTML = '';
                        sections.forEach(section => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'list-group-item list-group-item-action';
                            btn.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>${section.name}</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            `;
                            btn.addEventListener('click', () => selectSection(section.id, section.name));
                            sectionsList.appendChild(btn);
                        });
                    })
                    .catch(error => console.error('Error loading sections:', error));
            }

            function selectSection(sectionId, sectionName) {
                selectedSectionId = sectionId;
                selectedCategoryId = null;
                selectedSubcategoryId = null;

                // Highlight section
                document.querySelectorAll('#sectionsList .list-group-item').forEach(btn => {
                    btn.classList.remove('active');
                });
                event.target.closest('button').classList.add('active');

                currentSection.textContent = sectionName;
                categoryBreadcrumb.textContent = '';
                subcategoryBreadcrumb.textContent = '';
                itemsGrid.innerHTML = '';

                // Load categories
                loadCategories(sectionId);
                categoriesContainer.style.display = 'block';
                subcategoriesContainer.style.display = 'none';
            }

            function loadCategories(sectionId) {
                fetch(`/api/categories?section_id=${sectionId}`)
                    .then(response => response.json())
                    .then(categories => {
                        categoriesList.innerHTML = '';
                        if (categories.length === 0) {
                            categoriesList.innerHTML = '<div class="list-group-item">No categories available</div>';
                            return;
                        }
                        categories.forEach(category => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'list-group-item list-group-item-action';
                            btn.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>${category.name}</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            `;
                            btn.addEventListener('click', () => selectCategory(category.id, category.name));
                            categoriesList.appendChild(btn);
                        });
                    })
                    .catch(error => console.error('Error loading categories:', error));
            }

            function selectCategory(categoryId, categoryName) {
                selectedCategoryId = categoryId;
                selectedSubcategoryId = null;

                // Highlight category
                document.querySelectorAll('#categoriesList .list-group-item').forEach(btn => {
                    btn.classList.remove('active');
                });
                event.target.closest('button').classList.add('active');

                categoryBreadcrumb.textContent = ' > ' + categoryName;
                subcategoryBreadcrumb.textContent = '';
                itemsGrid.innerHTML = '';

                // Load subcategories
                loadSubcategories(categoryId);
                subcategoriesContainer.style.display = 'block';
            }

            function loadSubcategories(categoryId) {
                fetch(`/api/subcategories?category_id=${categoryId}`)
                    .then(response => response.json())
                    .then(subcategories => {
                        subcategoriesList.innerHTML = '';
                        if (subcategories.length === 0) {
                            subcategoriesList.innerHTML = '<div class="list-group-item">No subcategories available</div>';
                            return;
                        }
                        subcategories.forEach(subcategory => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'list-group-item list-group-item-action';
                            btn.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>${subcategory.name}</span>
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            `;
                            btn.addEventListener('click', () => selectSubcategory(subcategory.id, subcategory.name));
                            subcategoriesList.appendChild(btn);
                        });
                    })
                    .catch(error => console.error('Error loading subcategories:', error));
            }

            function selectSubcategory(subcategoryId, subcategoryName) {
                selectedSubcategoryId = subcategoryId;

                // Highlight subcategory
                document.querySelectorAll('#subcategoriesList .list-group-item').forEach(btn => {
                    btn.classList.remove('active');
                });
                event.target.closest('button').classList.add('active');

                subcategoryBreadcrumb.textContent = ' > ' + subcategoryName;

                // Load items
                loadItems(subcategoryId);
            }

            function loadItems(subcategoryId) {
                fetch(`/api/items?subcategory_id=${subcategoryId}`)
                    .then(response => response.json())
                    .then(items => {
                        itemsGrid.innerHTML = '';
                        if (items.length === 0) {
                            itemsGrid.innerHTML = '<div class="col-12"><div class="alert alert-info">No items available in this subcategory.</div></div>';
                            return;
                        }
                        items.forEach(item => {
                            const itemCard = createItemCard(item);
                            itemsGrid.appendChild(itemCard);
                        });
                    })
                    .catch(error => console.error('Error loading items:', error));
            }

            function createItemCard(item) {
                const col = document.createElement('div');
                col.className = 'col-lg-4 col-md-6 col-sm-12';

                const imageUrl = item.image ? `/storage/${item.image}` : '/images/placeholder.svg';

                col.innerHTML = `
                    <div class="card h-100 shadow-sm hover-shadow transition">
                        <div class="item-image-wrapper" style="height: 200px; overflow: hidden; background: #f0f0f0;">
                            <img src="${imageUrl}" alt="${item.name}" class="card-img-top" style="height: 100%; object-fit: cover; width: 100%;">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${item.name}</h5>
                            <p class="card-text text-muted small" style="flex-grow: 1;">
                                ${item.description ? item.description.substring(0, 100) + '...' : 'No description'}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-success fs-6">$${parseFloat(item.price).toFixed(2)}</span>
                                ${item.preparation_time ? `<span class="badge bg-info"><i class="bi bi-clock me-1"></i>${item.preparation_time}m</span>` : ''}
                            </div>
                            <button class="btn btn-primary btn-sm w-100" onclick="addToOrder(${item.id}, '${item.name.replace(/'/g, "\\'")}', ${item.price})">
                                <i class="bi bi-plus-circle me-1"></i> Add to Order
                            </button>
                        </div>
                    </div>
                `;

                return col;
            }

            // Add to Order function
            window.addToOrder = function(itemId, itemName, price) {
                alert(`Added "${itemName}" ($${parseFloat(price).toFixed(2)}) to order!\n\nThis would be integrated with your order system.`);
                // TODO: Integrate with order management system
            };
        });
    </script>

    <style>
        .hover-shadow {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-4px);
        }

        .list-group-item {
            border: none;
            border-bottom: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            transform: translateX(4px);
        }

        .list-group-item.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .card-title {
            font-weight: 600;
            color: #333;
        }

        #currentSection {
            font-size: 1.1rem;
            font-weight: 600;
        }

        #categoryBreadcrumb,
        #subcategoryBreadcrumb {
            font-size: 0.95rem;
            color: #6c757d;
        }

        .item-image-wrapper {
            position: relative;
        }

        .item-image-wrapper img {
            transition: transform 0.3s ease;
        }

        .card:hover .item-image-wrapper img {
            transform: scale(1.05);
        }
    </style>
@endsection
