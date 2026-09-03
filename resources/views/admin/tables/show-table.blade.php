@extends('layouts.app')

@section('title', 'Table ' . $table->table_number . ' Menu')

@section('content')
    <div class="container-fluid mt-5">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-3 text-gray-800">Welcome to Table {{ $table->table_number }}</h1>
                <p class="text-muted">Table Number: <strong>{{ $table->table_number }}</strong></p>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="card-title">Menu List</h2>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th style="width: 40px">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menuItems as $item)
                            <tr class="align-middle">
                                <td>{{ $item->name }}</td>
                                <td>
                                    <div class="text-truncate" style="max-width: 300px;">
                                        {{ $item->description ?? 'No description available.' }}
                                    </div>
                                </td>
                                <td><span class="badge text-bg-warning">${{ number_format($item->price, 2) }}</span></td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>
            <!-- /.card-body -->
            <div class="d-flex justify-content-end mt-3">
                {{ $menuItems->links() }}
            </div>
        </div>
        <!-- Footer Note -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note:</strong> Ordering is not available via this view.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
