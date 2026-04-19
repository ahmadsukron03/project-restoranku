@extends('admin.layouts.master')
@section('title', 'Role')
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
                    <h3>Manajemen Role</h3>
                    <p class="text-subtitle text-muted">Informasi Daftar Roles.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary float-start float-lg-end">Tambah
                        Roles</a>
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
                                <th>Nama Role</th>
                                <th>Deskripsi</th>
                                <th class="text-center" data-sortable="false" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->role_name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="{{ route('roles.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('roles.destroy', $item->id) }}" method="POST"
                                                class="d-inline ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin hapus?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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
