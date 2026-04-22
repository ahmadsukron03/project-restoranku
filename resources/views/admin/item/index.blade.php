@extends('admin.layouts.master')
@section('title', 'Item')
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
                    <h3>Daftar Menu</h3>
                    <p class="text-subtitle text-muted">Berbagai pilihan menu terbaik.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <a href="{{ route('items.create') }}" class="btn btn-primary float-start float-lg-end">Tambah
                        Menu</a>
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
                                {{-- Matikan panah sorting di kolom Gambar --}}
                                <th class="text-center" data-sortable="false" width="10%">Gambar</th>
                                <th>Nama Item</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th class="text-center" width="10%">Status</th>
                                {{-- Matikan panah sorting di kolom Aksi --}}
                                <th class="text-center" data-sortable="false" width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('img_item_upload/' . $item->img) }}" class="img-fluid rounded"
                                            width="60" height="60" alt=""
                                            onerror="this.onerror=null;this.src='{{ $item->img }}'">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ Str::limit($item->description, 25) }}</td>
                                    <td class="text-nowrap">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge  {{ $item->category->cat_name == 'Makanan' ? 'bg-warning' : 'bg-info' }}">
                                            {{ $item->category->cat_name ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $item->is_active == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->is_active == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if ($item->is_active == 1)
                                                <form action="{{ route('items.updateStatus', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="is_active" value="0">

                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Apakah anda yakin ingin menonaktifkan item ini?')">
                                                        <i class="bi bi-x"></i> Nonaktifkan
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('items.updateStatus', $item->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="is_active" value="1">

                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Aktifkan kembali item ini?')">
                                                        <i class="bi bi-check"></i> Aktifkan
                                                    </button>
                                                </form>
                                            @endif
                                            {{-- <form action="{{ route('items.destroy', $item->id) }}" method="POST"
                                                class="d-inline ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin hapus?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form> --}}
                                        </div>
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
