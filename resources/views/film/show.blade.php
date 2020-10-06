@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h1 class="mt-0 header-title">Komentar Daftar Film</h1>
                            <hr>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Judul Film</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="judul" value="{{$film->film_judul}}" readonly id="example-text-input">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Komentar</label>
                            </div>
                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <button type="button" data-toggle="modal" data-target="#uploadModal" class="btn btn-success waves-effect waves-light btn-sm float-right ml-2"><i class="fa fa-upload"></i></button>
                                <button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-primary waves-effect waves-light btn-sm float-right"><i class="fa fa-plus"></i></button>
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Akun</th>
                                    <th>Komentar</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($komentar as $value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->komentar_akun}}</td>
                                    <td>{{$value->komentar_isi}}</td>
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


    <div id="uploadModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Upload Komentar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{route('komentar.upload')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <a href="{{url('excel/format/komentar.xlsx')}}" class="btn btn-success waves-effect"><i class="fa fa-download"></i> Unduh Format</a>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Upload</label>
                            <div class="col-sm-12">
                                <input type="hidden" name="film_id" value="{{$film->film_id}}">
                                <input class="dropify" type="file" name="komentar" id="example-text-input" required
                                       data-allowed-file-extensions="xls xlsx" >
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Tambah Komentar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{route('komentar.store')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Akun</label>
                            <div class="col-sm-10">
                                <input type="hidden" name="film_id" value="{{$film->film_id}}">
                                <input class="form-control" type="text" name="akun" required autocomplete="off" id="example-text-input">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Komentar</label>
                            <div class="col-sm-10">
                                <textarea name="komentar" id="" cols="30" rows="10" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
