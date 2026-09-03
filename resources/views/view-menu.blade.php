@extends('layouts.app')

@section('title', 'Menu')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
            <div>
                <h3 class="mb-1">Browse Menu</h3>
                <div class="text-muted small">Sections → Categories → Subcategories → Items</div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="card-header">Browse by Sections</div>
                    <div class="card-body p-0">
                        <ul id="sectionsList" class="list-group list-group-flush"></ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="card-header">Categories</div>
                    <div class="card-body p-0">
                        <ul id="categoriesList" class="list-group list-group-flush"></ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="card-header">Subcategories</div>
                    <div class="card-body p-0">
                        <ul id="subcategoriesList" class="list-group list-group-flush"></ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between gap-2">
                        <span>Items</span>
                        <span id="itemsCount" class="badge bg-secondary">0</span>
                    </div>
                    <div class="card-body">
                        <div id="itemsGrid" class="row g-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load data from your Eloquent models via controllers, not hardcoded JSON.
        // We reuse existing API endpoints that already query Eloquent models.
        const api = {
            sections: '{{ route('menu.sections.active') ?? url('/api/sections') }}',
            categories: '{{ url('/api/categories') }}',
            subcategories: '{{ url('/api/subcategories') }}',
            items: '{{ url('/api/items') }}',
        };

        const state = {
            sectionId: null,
            categoryId: null,
            subcategoryId: null,
        };

        function escapeHtml(str) {
            return String(str)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '<')
                .replaceAll('>', '>')
                .replaceAll('"', '"')
                .replaceAll("'", '&#039;');
        }

        function setActive(listEl, activeId) {
            listEl.querySelectorAll('[data-id]').forEach(li => {
                li.classList.toggle('active', String(li.dataset.id) === String(activeId));
            });
        }

        async function loadSections() {
            const res = await fetch(api.sections);
            const sections = await res.json();

            const list = document.getElementById('sectionsList');
            list.innerHTML = '';

            if (!sections || sections.length === 0) {
                list.innerHTML = '<li class="list-group-item text-muted">No active sections</li>';
                return;
            }

            sections.forEach(s => {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action';
                li.dataset.id = s.id;
                li.textContent = s.name;
                li.addEventListener('click', () => {
                    state.sectionId = s.id;
                    document.getElementById('categoriesList').innerHTML = '';
                    document.getElementById('subcategoriesList').innerHTML = '';
                    document.getElementById('itemsGrid').innerHTML = '';
                    document.getElementById('itemsCount').textContent = '0';

                    loadCategories(s.id);
                    setActive(list, s.id);
                });
                list.appendChild(li);
            });

            // Auto-load first section
            state.sectionId = sections[0].id;
            setActive(list, sections[0].id);
            await loadCategories(sections[0].id);
        }

        async function loadCategories(sectionId) {
            const res = await fetch(api.categories + '?section_id=' + encodeURIComponent(sectionId));
            const categories = await res.json();

            const list = document.getElementById('categoriesList');
            list.innerHTML = '';

            if (!categories || categories.length === 0) {
                list.innerHTML = '<li class="list-group-item text-muted">No categories</li>';
                return;
            }

            categories.forEach(c => {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action';
                li.dataset.id = c.id;
                li.textContent = c.name;
                li.addEventListener('click', () => {
                    document.getElementById('subcategoriesList').innerHTML = '';
                    document.getElementById('itemsGrid').innerHTML = '';
                    document.getElementById('itemsCount').textContent = '0';

                    loadSubcategories(c.id);
                    setActive(list, c.id);
                });
                list.appendChild(li);
            });

            setActive(list, categories[0].id);
            await loadSubcategories(categories[0].id);
        }

        async function loadSubcategories(categoryId) {
            const res = await fetch(api.subcategories + '?category_id=' + encodeURIComponent(categoryId));
            const subcategories = await res.json();

            const list = document.getElementById('subcategoriesList');
            list.innerHTML = '';

            if (!subcategories || subcategories.length === 0) {
                list.innerHTML = '<li class="list-group-item text-muted">No subcategories</li>';
                return;
            }

            subcategories.forEach(sc => {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action';
                li.dataset.id = sc.id;
                li.textContent = sc.name;
                li.addEventListener('click', () => {
                    document.getElementById('itemsGrid').innerHTML = '';
                    document.getElementById('itemsCount').textContent = '0';

                    loadItems(sc.id);
                    setActive(list, sc.id);
                });
                list.appendChild(li);
            });

            setActive(list, subcategories[0].id);
            await loadItems(subcategories[0].id);
        }

        function itemImageUrl(path) {
            if (!path) return '';
            if (String(path).startsWith('http')) return path;
            return '/storage/' + String(path).replaceAll('public/', '');
        }

        async function loadItems(subcategoryId) {
            const res = await fetch(api.items + '?subcategory_id=' + encodeURIComponent(subcategoryId));
            const items = await res.json();

            const grid = document.getElementById('itemsGrid');
            const count = document.getElementById('itemsCount');
            grid.innerHTML = '';
            count.textContent = String(items?.length || 0);

            if (!items || items.length === 0) {
                grid.innerHTML = '<div class="text-muted small">No items</div>';
                return;
            }

            items.forEach(item => {
                const col = document.createElement('div');
                col.className = 'col-12';

                const imgUrl = itemImageUrl(item.image);
                const tags = (item.tags && Array.isArray(item.tags)) ? item.tags : [];

                col.innerHTML = `
                <div class="card shadow-sm h-100">
                    <div class="row g-0">
                        <div class="col-4 p-2">
                            <img
                                src="${imgUrl || '/storage/menu-items/placeholder.svg'}"
                                alt="${escapeHtml(item.name)}"
                                class="img-fluid rounded"
                                style="height:100px; object-fit:cover; width:100%;"
                            />
                        </div>
                        <div class="col-8">
                            <div class="card-body p-2">
                                <div class="d-flex align-items-start justify-content-between gap-2">
                                    <div>
                                        <div class="fw-semibold">${escapeHtml(item.name)}</div>
                                        <div class="text-muted small">${item.preparation_time ? (item.preparation_time + ' min') : ''}</div>
                                    </div>
                                    <div class="fw-bold text-primary">${escapeHtml(item.price ?? '')}</div>
                                </div>

                                <div class="small text-muted mt-2" style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                                    ${escapeHtml(item.description ?? '')}
                                </div>

                                ${tags.length ? `
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            ${tags.slice(0, 6).map(t => `<span class="badge text-bg-light border">${escapeHtml(t)}</span>`).join('')}
                                        </div>
                                    ` : ''}

                                <button class="btn btn-sm btn-success mt-2 w-100" type="button" data-item-id="${item.id}">
                                    Add to Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                col.querySelector('button[data-item-id]').addEventListener('click', () => {
                    const btn = col.querySelector('button');
                    btn.disabled = true;
                    btn.textContent = 'Added';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.textContent = 'Add to Order';
                    }, 800);
                });

                grid.appendChild(col);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadSections().catch(err => {
                console.error(err);
                const list = document.getElementById('sectionsList');
                if (list) list.innerHTML =
                    '<li class="list-group-item text-danger">Failed to load menu</li>';
            });
        });
    </script>
@endsection
