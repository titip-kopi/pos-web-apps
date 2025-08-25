@extends('layouts.app')

@section('title', 'Orders')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <style>
        table.table-bordered th,
        table.table-bordered td {
            border: 1px solid #dee2e6 !important;
        }

        .column-total-item {
            width: 118px;
            text-align: center;
        }

        .no-bullet {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Orders</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">Dashboard</div>
                    <div class="breadcrumb-item">Orders</div>
                    <div class="breadcrumb-item">All Orders</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card">
                                <div class="card-header">
                                    <h4>All Orders</h4>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="{{ route('orders.filter') }}" class="mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <input type="date" id="start_date" name="start_date" class="form-control"
                                                    value="{{ request('start_date') }}"
                                                    max="{{ now()->toDateString() }}">
                                            </div>
                                            <div class="col-auto px-0">
                                                <span style="font-size: 15px;">&nbsp;&nbsp;s/d&nbsp;&nbsp;</span>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="date" id="end_date" name="end_date" class="form-control"
                                                    value="{{ request('end_date') }}"
                                                    disabled>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-primary px-4">Filter</button>
                                            </div>
                                            <div class="col-auto">
                                                <a href="{{ route('orders.print', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                                                    class="btn btn-download ml-2">
                                                    <i class="fas fa-download"></i> Download PDF
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Produk</th>
                                                    <th>Total Item</th>
                                                    <th>Total Harga</th>
                                                    <th>Kasir</th>
                                                    <th>Waktu Transaksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $grandTotal = 0;
                                                    $no = ($orders->currentPage() - 1) * $orders->perPage() + 1;
                                                @endphp
                                                @forelse ($orders as $order)
                                                    <tr>
                                                        <td style="width: 10px; text-align: center;">{{ $no++ }}
                                                        </td>
                                                        <td>
                                                            <ul class="no-bullet">
                                                                @foreach ($order->orderItems as $item)
                                                                    <li>
                                                                        <strong>{{ $item->product->name ?? '-' }}</strong><br>
                                                                        {{ $item->quantity }} x Rp
                                                                        {{ number_format($item->product->price ?? 0, 0, ',', '.') }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </td>
                                                        <td class="column-total-item">{{ $order->total_item }}</td>
                                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                                        <td>{{ $order->kasir->name ?? 'N/A' }}</td>
                                                        <td>
                                                            {{ $order->transaction_time }}</a>
                                                        </td>
                                                    </tr>
                                                    @php $grandTotal += $order->total_price; @endphp
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">Tidak ada data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-center">Total Keseluruhan</th>
                                                    <th colspan="3">Rp {{ number_format($grandTotal, 0, ',', '.') }}
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="float-right">
                                        {{ $orders->withQueryString()->links() }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <script>
        const startDateInput = document.getElementById("start_date");
        const endDateInput = document.getElementById("end_date");

        startDateInput.addEventListener("change", function () {
            if (this.value) {
                endDateInput.disabled = false;
                endDateInput.min = this.value; // end_date tidak boleh < start_date
                endDateInput.max = new Date().toISOString().split("T")[0]; // max hari ini
                endDateInput.value = new Date().toISOString().split("T")[0];
            } else {
                endDateInput.disabled = true;
                endDateInput.value = "";
            }
        });

        // jika halaman di-refresh dengan start_date sudah ada, otomatis aktifkan end_date
        if (startDateInput.value) {
            endDateInput.disabled = false;
            endDateInput.min = startDateInput.value;
            endDateInput.max = new Date().toISOString().split("T")[0];
        }
    </script>
@endpush
