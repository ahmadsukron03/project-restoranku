@extends('admin.layouts.master')
@section('title', 'Edit Karyawan')

@section('content')
    <section class="section">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Edit Data Karyawan</h3>
                    <p class="text-subtitle text-muted">Silahkan ubah data karyawan.</p>
                </div>
                <div class="col-12 col-md-6 text-end">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <h5>Error!</h5>
                @foreach ($errors->all() as $error)
                    <li class="list-group-item">{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <div class="card mt-3">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6">
                            <label>Username</label>
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username', $user->username) }}">
                        </div>

                        <div class="col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" name="fullname"
                                class="form-control @error('fullname') is-invalid @enderror"
                                value="{{ old('fullname', $user->fullname) }}">
                        </div>

                        <div class="col-md-6">
                            <label>Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                        </div>

                        <div class="col-md-6">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                        </div>

                        <div class="col-md-6">
                            <label>No. Telepon</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="col-md-12 mt-2">
                            <label>Role</label>
                            <select name="role_id" class="form-select @error('role_id') is-invalid @enderror">
                                <option disabled>-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->role_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Data
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
