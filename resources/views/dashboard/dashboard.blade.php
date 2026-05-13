@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Dashboard</h3>
                        @include('components.session-messages')
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <!-- Statistics cards -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-primary">
                            <div class="inner">
                                <h3>$ <span>0.00</span></h3>
                                <p>Today&apos;s Revenue</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path
                                    d="M12 1.75a.75.75 0 01.75.75v1.02a8.5 8.5 0 016.996 6.75h-1.01a.75.75 0 000 1.5h1.01a8.5 8.5 0 01-6.996 6.75v1.02a.75.75 0 01-1.5 0v-1.02a8.5 8.5 0 01-6.996-6.75h1.01a.75.75 0 000-1.5H4.254a8.5 8.5 0 016.996-6.75V2.5a.75.75 0 01.75-.75Z" />
                                <path
                                    d="M12 7.5a.75.75 0 01.75.75v.17c.52.15.98.46 1.31.92a.75.75 0 11-1.21.88c-.18-.25-.37-.3-.63-.3-.44 0-.64.2-.64.44 0 .26.1.36.65.53l.28.09c1.02.32 1.6.88 1.6 1.86 0 1.02-.66 1.7-1.66 1.87v.14a.75.75 0 01-1.5 0v-.15a2.27 2.27 0 01-1.55-1.01.75.75 0 011.28-.78c.19.31.48.52.88.52.54 0 .76-.22.76-.47 0-.22-.08-.36-.62-.52l-.3-.1c-1.03-.32-1.6-.86-1.6-1.84 0-.96.62-1.62 1.56-1.79v-.19A.75.75 0 0112 7.5Z" />
                            </svg>
                            <a href="#"
                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                                onclick="return false;">
                                View details <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-success">
                            <div class="inner">
                                <h3>0</h3>
                                <p>Total Orders</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path
                                    d="M7.75 7.5A2.25 2.25 0 0010 5.25h4A2.25 2.25 0 0016.25 7.5v11A2.25 2.25 0 0114 20.75H10A2.25 2.25 0 017.75 18.5v-11Z" />
                                <path
                                    d="M9 9.25a.75.75 0 000 1.5h6a.75.75 0 000-1.5H9Zm0 3.5a.75.75 0 000 1.5h6a.75.75 0 000-1.5H9Zm0 3.5a.75.75 0 000 1.5h4a.75.75 0 000-1.5H9Z" />
                            </svg>
                            <a href="#"
                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                                onclick="return false;">
                                View orders <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-warning">
                            <div class="inner">
                                <h3>0</h3>
                                <p>Occupied Tables</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M6.5 9.5a3 3 0 013-3h5a3 3 0 013 3v3a3 3 0 01-3 3h-5a3 3 0 01-3-3v-3Z" />
                                <path
                                    d="M4.75 20.25a.75.75 0 01-.75-.75c0-1.3.5-2.3 1.23-3.01a.75.75 0 111.03 1.09c-.48.46-.76.95-.76 1.92a.75.75 0 01-.75.75Zm14.5 0a.75.75 0 01-.75-.75c0-.97-.28-1.46-.76-1.92a.75.75 0 111.03-1.09c.73.71 1.23 1.71 1.23 3.01a.75.75 0 01-.75.75Z" />
                            </svg>
                            <a href="#"
                                class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover"
                                onclick="return false;">
                                Manage tables <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-6">
                        <div class="small-box text-bg-danger">
                            <div class="inner">
                                <h3>0</h3>
                                <p>Pending Orders</p>
                            </div>
                            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path
                                    d="M12 2.25c5.385 0 9.75 4.365 9.75 9.75S17.385 21.75 12 21.75 2.25 17.385 2.25 12 6.615 2.25 12 2.25Z" />
                                <path
                                    d="M12 7.5a.75.75 0 01.75.75v4.05l2.3 1.33a.75.75 0 11-.75 1.3l-2.7-1.56a.75.75 0 01-.35-.64V8.25A.75.75 0 0112 7.5Z" />
                            </svg>
                            <a href="#"
                                class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                                onclick="return false;">
                                Resolve now <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent orders + quick links -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Recent Orders (Last 10)</h3>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th style="width: 110px;">Order ID</th>
                                                <th>Table</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th style="width: 160px;">Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#1008</td>
                                                <td>Table 3</td>
                                                <td>$42.50</td>
                                                <td><span class="badge text-bg-warning">Pending</span></td>
                                                <td>Today, 10:12</td>
                                            </tr>
                                            <tr>
                                                <td>#1007</td>
                                                <td>Table 1</td>
                                                <td>$18.75</td>
                                                <td><span class="badge text-bg-success">Completed</span></td>
                                                <td>Today, 09:55</td>
                                            </tr>
                                            <tr>
                                                <td>#1006</td>
                                                <td>Table 5</td>
                                                <td>$76.20</td>
                                                <td><span class="badge text-bg-primary">In Progress</span></td>
                                                <td>Today, 09:33</td>
                                            </tr>
                                            <tr>
                                                <td>#1005</td>
                                                <td>Table 2</td>
                                                <td>$29.99</td>
                                                <td><span class="badge text-bg-warning">Pending</span></td>
                                                <td>Today, 09:10</td>
                                            </tr>
                                            <tr>
                                                <td>#1004</td>
                                                <td>Table 7</td>
                                                <td>$55.00</td>
                                                <td><span class="badge text-bg-success">Completed</span></td>
                                                <td>Today, 08:48</td>
                                            </tr>
                                            <tr>
                                                <td>#1003</td>
                                                <td>Table 4</td>
                                                <td>$12.40</td>
                                                <td><span class="badge text-bg-primary">In Progress</span></td>
                                                <td>Today, 08:25</td>
                                            </tr>
                                            <tr>
                                                <td>#1002</td>
                                                <td>Table 6</td>
                                                <td>$64.10</td>
                                                <td><span class="badge text-bg-warning">Pending</span></td>
                                                <td>Today, 08:02</td>
                                            </tr>
                                            <tr>
                                                <td>#1001</td>
                                                <td>Table 8</td>
                                                <td>$23.60</td>
                                                <td><span class="badge text-bg-success">Completed</span></td>
                                                <td>Today, 07:40</td>
                                            </tr>
                                            <tr>
                                                <td>#1000</td>
                                                <td>Table 9</td>
                                                <td>$41.00</td>
                                                <td><span class="badge text-bg-primary">In Progress</span></td>
                                                <td>Today, 07:18</td>
                                            </tr>
                                            <tr>
                                                <td>#999</td>
                                                <td>Table 10</td>
                                                <td>$88.90</td>
                                                <td><span class="badge text-bg-warning">Pending</span></td>
                                                <td>Yesterday, 21:30</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Quick Links</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="#" class="btn btn-primary" onclick="return false;">
                                        <i class="bi bi-people me-2"></i> Manage Users
                                    </a>
                                    <a href="#" class="btn btn-success" onclick="return false;">
                                        <i class="bi bi-card-list me-2"></i> Manage Menu
                                    </a>
                                    <a href="#" class="btn btn-warning" onclick="return false;">
                                        <i class="bi bi-layout-wtf me-2"></i> Manage Tables
                                    </a>
                                    <a href="#" class="btn btn-danger" onclick="return false;">
                                        <i class="bi bi-file-earmark-bar-graph me-2"></i> View Reports
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="app-footer">
        <div class="float-end d-none d-sm-inline">Anything you want</div>
        <strong>
            Copyright &copy; 2014-2025&nbsp;
            <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        All rights reserved.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
