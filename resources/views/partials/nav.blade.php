<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="{{ route('dashboard.my-account') }}" class="nav-link">Profile</a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    @php
                        $btnClass = 'btn btn-sm p-0';
                    @endphp
                    @include('_logout_beautiful_snippet', ['class' => ''])
                </form>
            </li>
        </ul>
    </div>
</nav>
