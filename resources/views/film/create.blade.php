@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h1 class="mt-0 header-title">Tambah Daftar Film</h1>
                            <hr>
                            <form action="{{route('film.store')}}" method="POST">
                                @csrf

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Judul Film</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="judul" placeholder="Masukkan Judul Film" required autocomplete="off" id="example-text-input">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                            </form>

                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
@endsection
