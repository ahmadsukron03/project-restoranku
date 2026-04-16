@extends('customer.layouts.master')
@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Keranjang</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item active text-primary">Silakan cek esanan anda</li>
        </ol>
    </div>
    <div class="container-fluid py-5">
        <div class="container py-5">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (empty($cart))
                <div class="text-center py-5">
                    <h4 class="mb-4">Keranjang Anda Kosong</h4>
                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4 py-2">Lihat Menu</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Gambar</th>
                                <th scope="col">Menu</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Jumlah</th>
                                <th scope="col">Total</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $subtotal = 0; @endphp
                            @foreach ($cart as $item)
                                @php
                                    $itemTotal = $item['price'] * $item['qty'];
                                    $subtotal += $itemTotal;
                                @endphp
                                <tr>
                                    <th scope="row">
                                        <div class="d-flex align-items-center">
                                            {{-- Gunakan image dari item jika ada --}}
                                            <img src="{{ $item['img'] ?? 'https://images.unsplash.com/photo-1591325418441-ff678baf78ef' }}"
                                                class="img-fluid rounded-circle"
                                                style="width: 80px; height: 80px; object-fit: cover;" alt="">
                                        </div>
                                    </th>
                                    <td>
                                        <p class="mb-0 mt-4">{{ $item['name'] }}</p>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4">Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <div class="input-group quantity mt-4" style="width: 100px;">
                                            <div class="input-group-btn">
                                                <button type="button"
                                                    class="btn btn-sm btn-minus rounded-circle bg-light border"
                                                    onclick="updateQuantity('{{ $item['id'] }}', -1)">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            </div>
                                            <input id="qty-{{ $item['id'] }}" type="text"
                                                class="form-control form-control-sm text-center border-0 bg-transparent"
                                                value="{{ $item['qty'] }}" readonly>
                                            <div class="input-group-btn">
                                                <button type="button"
                                                    class="btn btn-sm btn-plus rounded-circle bg-light border"
                                                    onclick="updateQuantity('{{ $item['id'] }}', 1)">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 mt-4">Rp{{ number_format($itemTotal, 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-md rounded-circle bg-light border mt-4"
                                            onclick="confirmDelete('{{ $item['id'] }}')">
                                            <i class="fa fa-times text-danger"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @php
                    $tax = $subtotal * 0.1;
                    $total = $subtotal + $tax;
                @endphp

                <div class="d-flex justify-content-end">
                    <a href="{{ route('cart.clear') }}" class="btn btn-danger"
                        onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')">Kosongkan Keranjang</a>
                </div>

                <div class="row g-4 justify-content-end mt-1">
                    <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                        <div class="bg-light rounded">
                            <div class="p-4">
                                <h2 class="display-6 mb-4">Total <span class="fw-normal">Pesanan</span></h2>
                                <div class="d-flex justify-content-between mb-4">
                                    <h5 class="mb-0 me-4">Subtotal</h5>
                                    <p class="mb-0">Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                                </div>
                                @php
                                    $tax = $subtotal * 0.1;
                                    $grandTotal = $subtotal + $tax;
                                @endphp
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0 me-4">Pajak (10%)</p>
                                    <p class="mb-0">Rp{{ number_format($tax, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="py-4 mb-4 border-top d-flex justify-content-between">
                                <h4 class="mb-0 ps-4 me-4">Total</h4>
                                <h5 class="mb-0 pe-4 text-primary">Rp{{ number_format($grandTotal, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('checkout') }}"
                                class="btn border-secondary py-3 px-4 text-primary text-uppercase mb-4 rounded-pill">
                                Lanjut ke Pembayaran
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <script>
        function updateQuantity(itemId, change) {

            const qtyInput = document.getElementById('qty-' + itemId);
            const currentQty = parseInt(qtyInput.value);
            const newQty = currentQty + change;

            if (newQty <= 0) {
                confirmDelete(itemId);
                return;
            }

            fetch("{{ route('cart.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: itemId,
                        qty: newQty
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message)
                    }
                }).catch((error) => {
                    console.error('Error:', error)
                    alert('Terjadi Kesalahan saat mengubah keranjang')
                });
        }

        function confirmDelete(itemId) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                fetch("{{ route('cart.remove') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: itemId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message)
                        }
                    }).catch((error) => {
                        console.error('Error:', error)
                        alert('Terjadi Kesalahan saat menghapus keranjang')
                    });
            }
        }
    </script>
@endsection
