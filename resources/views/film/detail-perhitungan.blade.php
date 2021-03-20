@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h6 class="mt-0">Hasil Pengujian</h6>
                        <hr>
                        <table>
                            <tr>
                                <td>Rasio Perhitungan</td>
                                <td>: </td>
                                <td>{{$hasil->hasil_rasio}}</td>
                            </tr>
                            <tr>
                                <td>Perhitungan</td>
                                <td>: </td>
                                <td>
                                    @php($isi = json_decode($hasil->hasil_hitung))
                                    <p>p = {{$isi->p}}</p>
                                    <p>n = {{$isi->n}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>Hasil Akhir</td>
                                <td>: </td>
                                <td>{{$hasil->hasil_akhir}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h6 class="mt-0">Data Training</h6>
                        <hr>
                        <table class="table table-striped table-bordered zero-configuration">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kata</th>
                                <th>Perhitungan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($training as $key=>$value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->training_kata}}</td>
                                    <td>
                                        @php($isi = json_decode($value->training_isi))
                                        <p>p = {{$isi->p}}</p>
                                        <p>n = {{$isi->n}}</p>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h6 class="mt-0">Data Uji</h6>
                        <hr>
                        <table class="table table-striped table-bordered zero-configuration">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kata</th>
                                <th>Perhitungan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($uji as $key=>$value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->uji_kata}}</td>
                                    <td>
                                        @php($isi = json_decode($value->uji_isi))
                                        <p>p = {{$isi->p}}</p>
                                        <p>n = {{$isi->n}}</p></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
@endsection
