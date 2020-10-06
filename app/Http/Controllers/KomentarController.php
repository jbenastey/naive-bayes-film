<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class KomentarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = [
            'komentar_film_id' => $request->input('film_id'),
            'komentar_akun' => $request->input('akun'),
            'komentar_isi' => $request->input('komentar'),
        ];

        DB::table('komentar')->insert($data);
        return redirect('film/'.$data['komentar_film_id']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function upload(Request $request) {
        $film_id = $request->input('film_id');
        $file = $request->file('komentar');
        $nama_file = $file->getClientOriginalName();

        $file->move('excel/upload',$nama_file);

        $reader = new Xlsx();
        $reader->setLoadSheetsOnly('Sheet1');
        $spreadsheet = $reader->load('excel/upload/'.$nama_file);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $numrow = 1;
        foreach ($sheet as $key => $value) {
            if ($numrow > 1) {
                $simpan = [
                    'komentar_film_id' => $film_id,
                    'komentar_akun' => $value['A'],
                    'komentar_isi' => $value['B'],
                ];
                DB::table('komentar')->insert($simpan);
            }
            $numrow++;
        }
        return redirect('film/'.$film_id);
    }
}
