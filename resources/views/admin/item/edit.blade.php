@extends('admin.layouts.master')
@section('title', 'Edit Data Menu')

@section('css')
@endsection

@section('content')
    <section class="section">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Data Menu</h3>
                    <p class="text-subtitle text-muted">Silahkan ubah detail menu baru.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <a href="{{ route('items.index') }}" class="btn btn-secondary float-start float-lg-end">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h5 class="alert-heading">Update Error!</h5>
                @foreach ($errors->all() as $error)
                    <li class="list-group-item">
                        <i class="bi bi-flie-excel"></i>{{ $error }}
                    </li>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
            </div>
        @endif
        <div class="card mt-3">
            <div class="card-body">
                {{-- enctype="multipart/form-data" sangat penting untuk upload gambar --}}
                <form action="{{ route('items.update', $item->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Menu <span class="text-danger">*</span></label>
                                    <input name="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" id="name"
                                        placeholder="Masukkan Nama Menu" value="{{ old('name', $item->name) }}">
                                    {{-- @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Harga (Rp) <span class="text-danger">*</span></label>
                                    <input name="price" type="number"
                                        class="form-control @error('price') is-invalid @enderror" id="price"
                                        placeholder="Contoh: 25000" value="{{ old('price', $item->price) }}">
                                    {{-- @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Kategori Menu <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror">
                                        <option value="" disabled selected>-- Pilih Kategori --</option>
                                        {{-- Looping data kategori dari Controller --}}
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $category->id == $item->category_id ? 'selected' : '' }}>
                                                {{ $category->cat_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status Menu <span class="text-danger">*</span></label>

                                    <div class="form-check form-switch mt-2">

                                        {{-- Trik Laravel: Jika switch dimatikan, nilai 0 ini yang akan dikirim ke Controller --}}
                                        <input type="hidden" name="is_active" value="0">

                                        {{-- Jika switch dinyalakan, nilai 1 ini yang akan menimpa nilai 0 di atas --}}
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                            name="is_active" value="1"
                                            {{ old('is_active', $item->is_active) == '1' ? 'checked' : '' }}>

                                        <label class="form-check-label" for="is_active">Aktif (Tersedia)</label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div>
                                        <label for="img" class="form-label fw-bold">Gambar Menu <span
                                                class="text-muted fw-normal">(Opsional)</span></label>
                                    </div>

                                    {{-- Pratinjau Gambar Saat Ini --}}
                                    @if ($item->img)
                                        <div class="mb-3 p-2 border rounded bg-light d-inline-block">
                                            <p class="text-sm text-muted mb-2"><i class="bi bi-image"></i> Gambar saat ini:
                                            </p>
                                            <img src="{{ asset('img_item_upload/' . $item->img) }}"
                                                class="img-fluid rounded" width="60" height="60" alt=""
                                                style="height: 120px; width: 120px; object-fit: cover;"
                                                onerror="this.onerror=null;this.src='{{ $item->img }}'">
                                        </div>
                                    @endif

                                    {{-- Input File --}}
                                    <input name="img" type="file"
                                        class="form-control @error('img') is-invalid @enderror" id="img"
                                        accept="image/*">

                                    {{-- Pesan Bantuan Tambahan --}}
                                    @if ($item->img)
                                        <small class="form-text text-muted mt-2 d-block">
                                            <em>* Biarkan kosong jika tidak ingin mengubah gambar.</em>
                                        </small>
                                    @endif

                                    {{-- Pesan Error Validasi --}}
                                    {{-- @error('img')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Deskripsi Menu <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                        rows="3" placeholder="Jelaskan detail menu ini...">{{ old('description', $item->description) }}</textarea>
                                    {{-- @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">
                                    <i class="bi bi-pencil-fill me-2"></i>Ubah Data
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
