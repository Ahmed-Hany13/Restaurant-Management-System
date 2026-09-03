@extends('layouts.app')

@section('title', 'Reservation Details')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">Reservation Details</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('reservations.index') }}">Reservations</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-xl-10">
                        <div class="card card-outline card-primary shadow-sm">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h3 class="card-title">{{ $reservation->reservation_number }}</h3>
                                <a href="{{ route('reservations.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to list
                                </a>
                            </div>

                            @include('components.session-messages')

                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Customer</dt>
                                            <dd class="col-sm-7">{{ $reservation->customer_name }}</dd>

                                            <dt class="col-sm-5">Phone</dt>
                                            <dd class="col-sm-7">{{ $reservation->phone }}</dd>

                                            <dt class="col-sm-5">Reservation Type</dt>
                                            <dd class="col-sm-7">
                                                {{ $reservation->reservation_type === 'now' ? 'Walk-in / Immediate' : 'Scheduled' }}
                                            </dd>

                                            <dt class="col-sm-5">Date</dt>
                                            <dd class="col-sm-7">
                                                {{ $reservation->reservation_date?->format('Y-m-d') ?? $reservation->created_at->format('Y-m-d') }}
                                            </dd>

                                            <dt class="col-sm-5">Time</dt>
                                            <dd class="col-sm-7">
                                                {{ $reservation->reservation_time ?? $reservation->created_at->format('H:i') }}
                                            </dd>

                                            <dt class="col-sm-5">Guests</dt>
                                            <dd class="col-sm-7">{{ $reservation->guest_count }}</dd>
                                        </dl>
                                    </div>

                                    <div class="col-lg-6">
                                        <dl class="row">
                                            <dt class="col-sm-5">Table</dt>
                                            <dd class="col-sm-7">{{ $reservation->table?->table_number ?? 'N/A' }}</dd>

                                            <dt class="col-sm-5">Status</dt>
                                            <dd class="col-sm-7">
                                                <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                                            </dd>

                                            <dt class="col-sm-5">Notes</dt>
                                            <dd class="col-sm-7">{{ $reservation->notes ?? 'No notes' }}</dd>

                                            <dt class="col-sm-5">Created</dt>
                                            <dd class="col-sm-7">{{ $reservation->created_at->format('Y-m-d H:i') }}</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
