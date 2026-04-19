@extends('admin.layouts.master')
@section('title', 'Tambah Role')

@section('css')
@endsection

@section('content')
    <section class="section">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tambah Data Role</h3>
                    <p class="text-subtitle text-muted">Silahkan masukkan Role baru.</p>
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
                <form action="{{ route('roles.store') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="role_name">Nama Role <span class="text-danger">*</span></label>
                                    <input name="role_name" type="text"
                                        class="form-control @error('role_name') is-invalid @enderror" id="role_name"
                                        placeholder="Masukkan Nama Role" value="{{ old('role_name') }}">
                                    {{-- @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Deskripsi Role <span class="text-danger">*</span></label>
                                    <input name="description" type="text"
                                        class="form-control @error('description') is-invalid @enderror" id="description"
                                        placeholder="Masukkan Deskripsi" value="{{ old('description') }}">
                                    {{-- @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror --}}
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                <button type="submit" class="btn btn-primary me-1 mb-1">
                                    <i class="bi bi-save"></i> Simpan Data
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
