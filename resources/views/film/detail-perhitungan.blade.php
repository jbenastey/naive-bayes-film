@extends('layouts.app')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="page-title-box">
                <div class="row align-items-center">

                    <div class="col-sm-6">
                        <h4 class="page-title">Hasil Pengujian Rasio {{$hasil->hasil_rasio}}</h4>
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
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h6 class="mt-0">Kalimat Data Uji</h6>
                        <hr>
                        <table class="table table-striped table-bordered zero-configuration">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Kalimat</th>
                                <th>Perhitungan</th>
                                <th>Kelas</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($komentarUji as $key=>$value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->komentar_isi}}</td>
                                    <td>
                                        @php($isi = json_decode($value->kuji_hitung))
                                        <p>p = {{$isi->p}}</p>
                                        <p>n = {{$isi->n}}</p>
                                    </td>
                                    <td>{{$value->kuji_akhir}}</td>
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
