@extends('customer.layouts.master')
@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Checkout</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item active text-primary">Silakan lakukan proses pembayaran</li>
        </ol>
    </div>

    <div class="container-fluid py-5">
        <div class="container py-5">
            <h1 class="mb-4">Detail Pembayaran</h1>

            {{-- Form dimulai di sini untuk membungkus semua input --}}
            <form action="{{ route('checkout.store') }}" id="checkout-form" method="POST">
                @csrf
                <div class="row g-5">
                    {{-- SISI KIRI: Form Identitas & Tabel Pesanan --}}
                    <div class="col-md-12 col-lg-7 col-xl-8">
                        <div class="row">
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nama Lengkap<sup>*</sup></label>
                                    <input type="text" name="fullname" class="form-control"
                                        placeholder="Masukan Nama Anda" required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nomor WhatsApp<sup>*</sup></label>
                                    <input type="text" name="phone" class="form-control" placeholder="081..." required>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-4">
                                <div class="form-item w-100">
                                    <label class="form-label my-3">Nomor Meja<sup>*</sup></label>
                                    <input type="text" name="tableNumber" class="form-control"
                                        value="{{ $tableNumber ? $tableNumber : 'Tidak ada nomor meja' }}" readonly
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="form-item mt-3">
                            <label class="form-label my-3">Catatan (Opsional)</label>
                            <textarea name="note" class="form-control" spellcheck="false" cols="30" rows="4"
                                placeholder="Contoh: Tidak pakai sambal"></textarea>
                        </div>

                        {{-- Tabel Detail Pesanan --}}
                        <div class="table-responsive mt-5">
                            <h4 class="mb-4">Detail Pesanan</h4>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Menu</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Jumlah</th>
                                        <th scope="col">Total</th>
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
                                                        style="width: 80px; height: 80px; object-fit: cover;"
                                                        alt="">
                                                </div>
                                            </th>
                                            <td class="py-3 text-nowrap">Rp{{ number_format($item['price'], 0, ',', '.') }}
                                            </td>
                                            <td class="py-3">{{ $item['qty'] }}</td>
                                            <td class="py-3 text-nowrap">Rp{{ number_format($itemTotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- SISI KANAN: Ringkasan Total & Pembayaran (Sidebar) --}}
                    <div class="col-md-12 col-lg-5 col-xl-4">
                        <div class="bg-light rounded p-4 sticky-top" style="top: 100px; z-index: 1;">
                            <h3 class="display-6 mb-4" style="font-size: 1.5rem;">Total <span
                                    class="fw-normal">Pesanan</span></h3>

                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">Subtotal</h5>
                                <p class="mb-0">Rp{{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>

                            @php
                                $tax = $subtotal * 0.1;
                                $grandTotal = $subtotal + $tax;
                            @endphp

                            <div class="d-flex justify-content-between mb-3">
                                <p class="mb-0">Pajak (10%)</p>
                                <p class="mb-0">Rp{{ number_format($tax, 0, ',', '.') }}</p>
                            </div>

                            <div class="py-3 border-top border-bottom d-flex justify-content-between mb-4">
                                <h4 class="mb-0">Total</h4>
                                <h5 class="mb-0 text-primary">Rp{{ number_format($grandTotal, 0, ',', '.') }}</h5>
                            </div>

                            <h5 class="mb-3">Metode Pembayaran</h5>
                            <div class="form-check mb-2">
                                <input type="radio" class="form-check-input bg-primary border-0" id="qris"
                                    name="payment_method" value="qris" checked>
                                <label class="form-check-label" for="qris">QRIS (Otomatis)</label>
                            </div>
                            <div class="form-check mb-4">
                                <input type="radio" class="form-check-input bg-primary border-0" id="cash"
                                    name="payment_method" value="tunai">
                                <label class="form-check-label" for="cash">Tunai (Di Kasir)</label>
                            </div>

                            <button id="pay-button" type="button"
                                class="btn border-secondary py-3 px-4 text-uppercase text-primary w-100 rounded-pill">
                                Konfirmasi Pesanan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            const form = document.querySelector('form');

            payButton.addEventListener('click', function() {
                let paymentMethod = document.querySelector('input[name="payment_method"]:checked');

                if (!paymentMethod) {
                    alert('Pilih metode pembayaran terlebih dahulu!');
                    return;
                }

                paymentMethod = paymentMethod.value;
                let formData = new FormData(form);

                if (paymentMethod == 'tunai') {
                    form.submit();
                } else {
                    fetch("{{ route('checkout.store') }}", {
                            method: "POST",
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.snap_token) {
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        window.location.href = "/checkout/success/" + data
                                            .order_code;
                                    },
                                    onPending: function(result) {
                                        alert("Menunggu Pembayaran");
                                    },
                                    onError: function(result) {
                                        alert("Pembayaran Gagal");
                                    }
                                });
                            } else {
                                alert("Gagal mendapatkan token pembayaran");
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert("Terjadi kesalahan");
                        });
                }
            });
        });
    </script>
@endsection
