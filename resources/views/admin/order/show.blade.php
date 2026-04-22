@extends('admin.layouts.master')
@section('title', 'Detail Pesanan')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Detail Pesanan</h3>
                    <p class="text-subtitle text-muted">Informasi lengkap pesanan</p>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Kode Pesanan: {{ $order->order_code }}</h5>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Nama Pelanggan</label>
                        <p>{{ $order->user->fullname ?? '-' }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Total</label>
                        <p>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Status</label><br>
                        <span
                            class="badge 
                        {{ $order->status == 'pending' ? 'bg-warning' : ($order->status == 'settlement' ? 'bg-success' : 'bg-primary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">No. Meja</label>
                        <p>{{ $order->table_number ?? '-' }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Metode Pembayaran</label>
                        <p>{{ $order->payment_method ?? '-' }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Dipesan Pada</label>
                        <p>{{ $order->created_at->format('d-m-Y H:i') }}</p>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="fw-bold">Catatan</label>
                        <p>{{ $order->note ?? '-' }}</p>
                    </div>

                </div>
            </div>
        </div>
        @if ($orderItems && $orderItems->count())
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Menu yang Dipesan</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Gambar</th>
                                    <th>Nama Menu</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($orderItems as $menu)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>

                                        <td class="text-center">
                                            <img src="{{ $menu->item && $menu->item->img
                                                ? asset('img_item_upload/' . $menu->item->img)
                                                : 'https://via.placeholder.com/60' }}"
                                                width="50" class="rounded" style="object-fit: cover;">
                                        </td>

                                        <td>{{ $menu->item->name ?? '-' }}</td>

                                        <td class="text-center">{{ $menu->quantity }}</td>

                                        <td class="text-nowrap text-end">
                                            Rp{{ number_format($menu->price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach

                                {{-- GARIS PEMISAH --}}
                                <tr>
                                    <td colspan="5">
                                        <hr class="my-2">
                                    </td>
                                </tr>

                                {{-- TOTAL --}}
                                <tr>
                                    <td colspan="4" class="text-end fw-semibold">Total</td>
                                    <td class="text-end">
                                        Rp{{ number_format($order->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- PAJAK --}}
                                <tr>
                                    <td colspan="4" class="text-end">Pajak</td>
                                    <td class="text-end">
                                        Rp{{ number_format($order->tax, 0, ',', '.') }}
                                    </td>
                                </tr>

                                {{-- GRAND TOTAL --}}
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Grand Total</td>
                                    <td class="text-end fw-bold text-primary">
                                        Rp{{ number_format($order->grandtotal, 0, ',', '.') }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
