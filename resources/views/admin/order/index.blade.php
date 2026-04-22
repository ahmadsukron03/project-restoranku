@extends('admin.layouts.master')
@section('title', 'Daftar Pesanan')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">
@endsection
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p>
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
        </div>
    @endif
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Daftar Pesanan</h3>
                    <p class="text-subtitle text-muted">Daftar pesanan dari customer.</p>
                </div>
                {{-- <div class="col-12 col-md-6 order-md-2 order-first">
                    <a href="{{ route('items.create') }}" class="btn btn-primary float-start float-lg-end">Tambah
                        Menu</a> --}}
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Kode Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>No. Meja</th>
                            <th>Metode Pembayaran</th>
                            <th>Catatan</th>
                            <th>Dipesan Pada</th>
                            <th class="text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <td>{{ $order->order_code }}</td>

                                <td>{{ $order->user->fullname ?? '-' }}</td>

                                <td class="text-nowrap">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>

                                <td>
                                    <span
                                        class="badge 
                        {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'settlement' ? 'bg-success' : 'bg-primary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>

                                <td>{{ $order->table_number ?? '-' }}</td>

                                <td>{{ $order->payment_method ?? '-' }}</td>

                                <td>{{ Str::limit($order->note, 30) ?? '-' }}</td>

                                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>

                                <td class="text-center align-middle">
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="btn btn-info btn-sm d-inline-flex align-items-center justify-content-center gap-1 py-1">
                                        <i class="bi bi-eye" style="line-height: 1;"></i>
                                        <span>Lihat</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/admin/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/admin/static/js/pages/simple-datatables.js') }}"></script>

@endsection
