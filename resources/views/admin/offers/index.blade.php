@extends('layouts.app')
@section('title', 'Offers')
@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Manage Offers</h3>
            <div class="card-tools">
                <span class="badge text-bg-light">Admin</span>
            </div>
        </div>

        @include('components.session-messages')
        <!-- /.card-header -->
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h5 class="mb-1">All Offers</h5>
                </div>

                <a href="{{ route('offers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Create Offer
                </a>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>actions</th>
                        <th>name</th>
                        <th>discount type</th>
                        <th>discount value</th>
                        <th>item count</th>
                        <th>validity period</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offers as $offer)
                        <tr class="align-middle">
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-sm btn-outline-secondary mt-1" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('offers.edit', $offer->id) }}" class="btn btn-sm btn-outline-primary mt-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this offer?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $offer->name }}</div>
                            </td>
                            <td class="text-muted">{{ $offer->discount_type ?? '--' }}</td>
                            <td>{{ $offer->discount_value ?? '--' }}</td>
                            <td><span class="badge text-bg-light">{{ $offer->items_count ?? '--' }}</span></td>
                            <td class="text-muted">{{ $offer->validity_period ?? '--' }}</td>
                            <td><span class="badge bg-success">{{ $offer->status ?? 'active' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No offers found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <style>
                .pagination {
                    margin: 0;
                    gap: 0.25rem;
                    justify-content: flex-end;
                }

                .pagination .page-link {
                    padding: 0.35rem 0.6rem;
                    font-size: 0.875rem;
                    line-height: 1.25;
                    border-radius: 0.25rem;
                }

                .pagination .page-link:hover {
                    background-color: #e9ecef;
                }

                .pagination .page-item.active .page-link {
                    background-color: #0d6efd;
                    border-color: #0d6efd;
                }

                .pagination .page-item.disabled .page-link {
                    color: #6c757d;
                    pointer-events: none;
                    background-color: #fff;
                    border-color: #dee2e6;
                }
            </style>
            <div class="d-flex justify-content-end">
                {{ $offers->links() }}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
