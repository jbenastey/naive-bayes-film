@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Lihat Perhitungan</label>
                            </div>
                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <button type="button" data-toggle="modal" data-target="#createModal" class="btn btn-primary waves-effect waves-light btn-sm float-right"><i class="fa fa-plus"></i></button>
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Rasio</th>
                                    <th><i class="mdi mdi-settings"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($hasil as $key => $value)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$value->hasil_rasio}}</td>
                                        <td>
                                            <a href="{{route('film.detail-perhitungan',$value->hasil_id)}}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Lihat Detail</a>
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


    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Hitung</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{route('film.submit',$film->film_id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="example-text-input" class="col-sm-2 col-form-label">Rasio</label>
                            <div class="col-sm-10">
                                <select name="rasio" id="" class="form-control" required>
                                    <option value="0" selected disabled>Pilih Rasio</option>
                                    @foreach($hasil as $key => $value)
                                        <option value="70" @if($value->hasil_rasio == '70') hidden @endif >70 : 30</option>
                                        <option value="80" @if($value->hasil_rasio == '80') hidden @endif >80 : 20</option>
                                        <option value="90" @if($value->hasil_rasio == '90') hidden @endif >90 : 10</option>
                                    @endforeach
                                </select>
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
