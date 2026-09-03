@extends('layouts.app')

@section('title', 'Kitchen View')

@section('content')
    <div class="app-content px-4 py-4">
        <div
            class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Kitchen View</h1>
                <p class="text-secondary mb-0">Pending and in-preparation orders refresh automatically every 15 seconds.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-danger text-white">Pending Orders</span>
                <span class="badge bg-warning text-dark">In Preparation</span>
                <span class="badge bg-secondary">Updated at {{ now()->format('H:i:s') }}</span>
            </div>
        </div>
        @include('components.session-messages')
        <div class="row gx-4 gy-4">
            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm h-100" style="background: #dc3545;">
                    <div class="card-header border-0 bg-dark text-white">
                        <h2 class="h5 mb-0">Pending Orders</h2>
                    </div>
                    <div class="card-body overflow-auto text-white" style="max-height: calc(100vh - 170px);">
                        @forelse($pendingOrders as $orderNumber => $orderLines)
                            @php
                                $firstOrder = $orderLines->first();
                                $createdAt = $firstOrder->created_at;
                                $elapsedMinutes = now()->diffInMinutes($createdAt);
                            @endphp
                            <div class="card mb-4 border-0 bg-white text-dark shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <div class="fs-2 fw-bold">{{ $orderNumber }}</div>
                                            <div class="small text-muted">Table
                                                {{ $firstOrder->table->table_number ?? $firstOrder->table_id }}</div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger mb-2">Pending</span>
                                            <div class="small text-muted">{{ $createdAt->diffForHumans() }}</div>
                                            <div class="fw-semibold">Elapsed: {{ floor($elapsedMinutes / 60) }}h
                                                {{ $elapsedMinutes % 60 }}m</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        @foreach ($orderLines as $line)
                                            <div
                                                class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                <div>
                                                    <div class="fw-semibold">{{ $line->menuItems->name ?? 'Item' }}</div>
                                                    <div class="small text-muted">Qty: {{ $line->quantity }}</div>
                                                    @if (!empty($line->special_instructions))
                                                        <div class="mt-1 badge bg-danger text-white">
                                                            {{ $line->special_instructions }}</div>
                                                    @endif
                                                </div>
                                                <div class="fs-5 text-secondary">×{{ $line->quantity }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <form method="POST"
                                        action="{{ route('kitchen.start-preparing', ['order_number' => $orderNumber]) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-dark w-100">Start Preparing</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-white-75">There are no pending orders at the moment.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="card border-0 shadow-sm h-100" style="background: #ffc107;">
                    <div class="card-header border-0 bg-dark text-white">
                        <h2 class="h5 mb-0">In Preparation</h2>
                    </div>
                    <div class="card-body overflow-auto text-dark" style="max-height: calc(100vh - 170px);">
                        @forelse($inPreparationOrders as $orderNumber => $orderLines)
                            @php
                                $firstOrder = $orderLines->first();
                                $startedAt = $firstOrder->preparation_started_at ?? $firstOrder->created_at;
                                $elapsedMinutes = now()->diffInMinutes($startedAt);
                                $cardStyle = $elapsedMinutes > 25 ? 'bg-danger text-white' : ($elapsedMinutes >= 15 ? 'bg-warning text-dark' : 'bg-white text-dark');
                                $badgeStyle = $elapsedMinutes > 25 ? 'bg-danger text-white' : ($elapsedMinutes >= 15 ? 'bg-warning text-dark' : 'bg-secondary text-white');
                                $cardBorder = $elapsedMinutes > 25 ? 'border-danger' : ($elapsedMinutes >= 15 ? 'border-warning' : 'border-secondary');
                                $detailsId = 'orderDetails' . md5($orderNumber);
                            @endphp

                            <div class="card mb-3 shadow-sm {{ $cardStyle }} border {{ $cardBorder }}">
                                <div class="card-body p-3">
                                    <button class="btn btn-transparent text-start w-100 p-0 border-0" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $detailsId }}"
                                        aria-expanded="false" aria-controls="{{ $detailsId }}">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <div class="fs-1 fw-bold">{{ $orderNumber }}</div>
                                                <div class="small text-muted">Table {{ $firstOrder->table->table_number ?? $firstOrder->table_id }}</div>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge {{ $badgeStyle }} mb-2">In Preparation</span>
                                                <div class="fs-5 fw-semibold">{{ floor($elapsedMinutes / 60) }}h {{ $elapsedMinutes % 60 }}m</div>
                                                <div class="small text-muted">Started {{ $startedAt->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </button>

                                    <div class="collapse" id="{{ $detailsId }}">
                                        <div class="bg-light rounded p-3 mb-3">
                                            <div class="fw-semibold mb-2">Items</div>
                                            @foreach ($orderLines as $line)
                                                <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
                                                    <div>
                                                        <div class="fw-semibold">{{ $line->menuItems->name ?? 'Item' }}</div>
                                                        <div class="small text-muted">Qty: {{ $line->quantity }}</div>
                                                        @if (!empty($line->special_instructions))
                                                            <div class="mt-2 badge bg-secondary text-white">
                                                                {{ $line->special_instructions }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="fs-4 text-muted">×{{ $line->quantity }}</div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <form method="POST"
                                            action="{{ route('kitchen.mark-ready', ['order_number' => $orderNumber]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-dark w-100">Mark as Ready</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-dark-75">There are no orders in preparation right now.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const kitchenSuccess = @json(session('success'));
        if (kitchenSuccess && kitchenSuccess.toLowerCase().includes('ready')) {
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                oscillator.type = 'sine';
                oscillator.frequency.value = 880;
                oscillator.connect(audioContext.destination);
                oscillator.start();
                setTimeout(() => oscillator.stop(), 120);
            } catch (e) {
                console.warn('Kitchen alert audio unavailable', e);
            }
        }

        setTimeout(function() {
            window.location.reload();
        }, 15000);
    </script>
@endsection
