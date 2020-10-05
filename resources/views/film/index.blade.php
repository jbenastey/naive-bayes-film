@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h1 class="mt-0 header-title">Daftar Film</h1>
                            <p class="text-muted">
                                <a href="{{route('film.create')}}" class="btn btn-sm btn-primary">Tambah Film</a>
                            </p>

                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Tanggal Input</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($film as $value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->film_judul}}</td>
                                    <td>{{$value->film_date_created}}</td>
                                    <td>
                                        <a href="" class="btn btn-sm btn-outline-primary"><i class="ti-eye"></i></a>
                                        <a href="" class="btn btn-sm btn-outline-success"><i class="ti-pencil"></i></a>
                                        <a href="" class="btn btn-sm btn-outline-danger"><i class="ti-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
@endsection
