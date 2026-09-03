<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Restaurant')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
        integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI=" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.css') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @include('partials.nav')
        @include('partials.main-sidebar')

        <main class="app-main">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('dist/js/adminlte.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.sidebar-menu .nav-item.has-treeview > .nav-link').forEach(function (link) {
                const parentItem = link.closest('.nav-item.has-treeview');
                const childMenu = parentItem ? parentItem.querySelector(':scope > .nav-treeview') : null;

                if (!parentItem || !childMenu) {
                    return;
                }

                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    const isOpen = parentItem.classList.contains('menu-open');

                    document.querySelectorAll('.sidebar-menu .nav-item.has-treeview.menu-open').forEach(function (openItem) {
                        if (openItem !== parentItem) {
                            openItem.classList.remove('menu-open');
                            const openMenu = openItem.querySelector(':scope > .nav-treeview');
                            if (openMenu) {
                                openMenu.style.display = 'none';
                            }
                        }
                    });

                    parentItem.classList.toggle('menu-open', !isOpen);
                    childMenu.style.display = isOpen ? 'none' : 'block';
                });
            });
        });
    </script>
</body>
</html>


