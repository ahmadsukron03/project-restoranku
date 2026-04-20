@extends('admin.layouts.master')
@section('title', 'Tambah Karyawan')

@section('css')
@endsection

@section('content')
    <section class="section">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Tambah Data Karyawan</h3>
                    <p class="text-subtitle text-muted">Silahkan Tambahkan karyawan baru.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary float-start float-lg-end">
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
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Username Karyawan <span class="text-danger">*</span></label>
                                    <input name="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" id="username"
                                        placeholder="Masukkan Username" value="{{ old('username') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap Karyawan <span class="text-danger">*</span></label>
                                    <input name="fullname" type="text"
                                        class="form-control @error('fullname') is-invalid @enderror" id="fullname"
                                        placeholder="Masukkan Nama lengkap" value="{{ old('fullname') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Password <span class="text-danger">*</span></label>
                                    <input name="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" id="password"
                                        placeholder="Masukkan password">
                                    <small><a href="#" class="toggle-password" data-target="password">Lihat
                                            Password</a></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input name="password_confirmation" type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" placeholder="Masukkan Konfirmasi Password">
                                    <small><a href="#" class="toggle-password"
                                            data-target="password_confirmation">Lihat
                                            Password</a></small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Email <span class="text-danger">*</span></label>
                                    <input name="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" id="email"
                                        placeholder="Masukkan Email" value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">No. Telepon <span class="text-danger">*</span></label>
                                    <input name="phone" type="text"
                                        class="form-control @error('phone') is-invalid @enderror" id="phone"
                                        placeholder="Masukkan Nomor Telepon" value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="role_id">Nama Role<span class="text-danger">*</span></label>
                                        <select name="role_id" id="role_id"
                                            class="form-select @error('role_id') is-invalid @enderror">
                                            <option value="" disabled selected>-- Pilih Role --</option>
                                            {{-- Looping data kategori dari Controller --}}
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->role_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="row mt-4">
                                <div class="col-12 text-end">
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

@section('scripts')
    <script>
        document.querySelectorAll('.toggle-password').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                let input = document.getElementById(this.dataset.target);
                let isHidden = input.getAttribute('type') === 'password';
                input.type = isHidden ? 'text' : 'password';
                document.querySelector(`a[data-target="${this.dataset.target}"]`).textContent = isHidden ?
                    'Sembunyikan Password' : 'Lihat Password';
            });
        });
    </script>
@endsection
