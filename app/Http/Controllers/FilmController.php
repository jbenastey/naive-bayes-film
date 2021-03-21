<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPInsight\Sentiment;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['film'] = DB::table('film')->get();
        return view('film.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('film.create');
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
            'film_judul' => $request->input('judul')
        ];

        DB::table('film')->insert($data);
        return redirect('film');
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
        $data['film'] = DB::table('film')
            ->where('film_id',$id)
            ->first();
        $data['komentar'] = DB::table('komentar')
            ->where('komentar_film_id',$id)
            ->get();
        return view('film.show',$data);
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

    public function perhitungan($id){
        $data['film'] = DB::table('film')
            ->where('film_id',$id)
            ->first();
        $data['hasil'] = DB::table('hasil')
            ->where('hasil_film_id',$id)
            ->get();
        return view('film.perhitungan',$data);
    }

    public function submit(Request $request,$id){
        $rasio = $request->input('rasio');
        if ($rasio != 0){
            $komentar = DB::table('komentar')
                ->where('komentar_film_id',$id)
                ->get();
            $komentarUji = DB::table('komentar')
                ->where('komentar_film_id',$id)
                ->orderBy('komentar_id','DESC')
                ->get();

            $jPositif = 0;
            $jNegatif = 0;

            $list_word = null;
            $positifK = 0;
            $negatifK = 0;

            for ($i = 0; $i <  round(($rasio * 0.01) * count($komentar)); $i++){
                if ($komentar[$i]->komentar_jenis == 'positif'){
                    $jPositif++;
                } else {
                    $jNegatif++;
                }

                $clean = trim(preg_replace("/[^a-zA-Z0-9]/", " ", $komentar[$i]->komentar_isi));
                $caseFold = strtolower($clean);
                $token = explode(' ',$caseFold);

                foreach ($token as $key2 => $value2) {
                    if ($value2!=null){
                        $normalize = $value2;
                        $ada = DB::table('kata_dasar')
                            ->where('kata_dasar','like',$value2)
                            ->first('kata_dasar');
                        if ($ada != null){
                            $normalize = $ada->kata_dasar;
                        } else {
                            $getNormalize = json_decode(GetTranslate('id','id',$value2),true);
                            if (empty($getNormalize['spell']) == false){
                                $normalize = $getNormalize['spell']['spell_res'];
                            }

                        }

                        $stem = $this->stemming($normalize);

                        if ($stem == null) {
                            $stem = $normalize;
                        }


                        /*
                         * Structure by AS
                         */
                        if (isset($list_word[$stem]) == false){
                            $list_word[$stem] = array(
                                "doc" => 1+$i,
                                "df"=>1,
                                "type"=>$komentar[$i]->komentar_jenis
                            );

    //                        if ($sentimen->categorise($stem) == 'negatif'){
    //                        if ($list_word[$stem]['type'] == 'negatif'){
    //                            $negatifK++;
    //                        } else {
    //                            $positifK++;
    //                        }
                        }
                        else{
                            $list_word[$stem] = array(
                                "doc"=>$list_word[$stem]["doc"].",".($i+1),
                                "df"=>$list_word[$stem]["df"]+1,
                                "type"=>$list_word[$stem]["type"].",".$komentar[$i]->komentar_jenis
                            );
                        }

                        if ($komentar[$i]->komentar_jenis == 'negatif'){
                            $negatifK++;
                        } else {
                            $positifK++;
                        }
    //                    var_dump($stem);
                    }

                }
    //
    //            var_dump($positifK);
    //            var_dump($negatifK);
            }

            $rasioTraining = $this->doTfIdf($list_word,round(($rasio * 0.01) * count($komentar)),$positifK,$negatifK);

            $kataUji = null;

            $ujiKomentar = [];
            $idUjiKomentar = [];

            for ($i = 0; $i <  round((1 - ($rasio * 0.01)) * count($komentarUji)); $i++){
                $clean = trim(preg_replace("/[^a-zA-Z0-9]/", " ", $komentarUji[$i]->komentar_isi));
                $caseFold = strtolower($clean);
                $token = explode(' ',$caseFold);

                $ujiKomentar[$i] = [];
                $idUjiKomentar[$i] = $komentarUji[$i]->komentar_id;

                foreach ($token as $key2 => $value2) {
                    if ($value2!=null){
                        $normalize = $value2;
                        $ada = DB::table('kata_dasar')
                            ->where('kata_dasar','like',$value2)
                            ->first('kata_dasar');
                        if ($ada != null){
                            $normalize = $ada->kata_dasar;
                        } else {
                            $getNormalize = json_decode(GetTranslate('id','id',$value2),true);
                            if (empty($getNormalize['spell']) == false){
                                $normalize = $getNormalize['spell']['spell_res'];
                            }

                        }

                        $stem = $this->stemming($normalize);

                        if ($stem == null) {
                            $stem = $normalize;
                        }

                        if (isset($stem))
                        array_push($ujiKomentar[$i],$stem);

                        foreach ($rasioTraining as $key=>$value) {
                            if ($stem == $key){
                                $kataUji[$stem] = [
                                    'p' => $value['p'],
                                    'n' => $value['n']
                                ];break;
                            } else {
                                $kataUji[$stem] = [
                                    'p' => 1/($positifK+($positifK+$negatifK)),
                                    'n' => 1/($negatifK+($positifK+$negatifK))
                                ];
                            }
                        }

                    }

                }
            }

            foreach ($rasioTraining as $key=>$value) {
                $simpanTraining = [
                    'training_film_id' => $id,
                    'training_rasio' => $rasio,
                    'training_kata' => $key,
                    'training_isi' => json_encode($value)
                ];
                DB::table('training')->insert($simpanTraining);
//                var_dump($simpanTraining);
            }
            $pPositif = $jPositif/round(($rasio * 0.01) * count($komentar));
            $pNegatif = $jNegatif/round(($rasio * 0.01) * count($komentar));

            $hasilP = $pPositif;
            $hasilN = $pNegatif;
            foreach ($kataUji as $key=>$value) {
                $hasilP *= $value['p'];
                $hasilN *= $value['n'];
                $simpanUji = [
                    'uji_film_id' => $id,
                    'uji_rasio' => $rasio,
                    'uji_kata' => $key,
                    'uji_isi' => json_encode($value)
                ];
                DB::table('uji')->insert($simpanUji);
//                var_dump($simpanUji);
            }
            $akhir = 'negatif';
            if ($hasilP > $hasilN) {
                $akhir = 'positif';
            }
            $simpanHasil = [
                'hasil_film_id' => $id,
                'hasil_rasio' => $rasio,
            ];
//            var_dump($simpanHasil);
            DB::table('hasil')->insert($simpanHasil);

            $hasilPKomentar = [];
            $hasilNKomentar = [];
            $akhirKomentar = [];

            foreach ($ujiKomentar as $key => $value) {
                $hasilPKomentar[$key] = $pPositif;
                $hasilNKomentar[$key] = $pNegatif;
                foreach (array_values(array_unique($value)) as $key2 => $value2) {
                    foreach ($kataUji as $key3=>$value3) {
                        if ($key3 == $value2) {
                            $hasilPKomentar[$key] *= $value3['p'];
                            $hasilNKomentar[$key] *= $value3['n'];
                        }
                    }
                }
                $akhirKomentar[$key] = 'negatif';
                if ($hasilPKomentar[$key] > $hasilNKomentar[$key]) {
                    $akhirKomentar[$key] = 'positif';
                }
                $simpanUjiKomentar = [
                    'kuji_film_id' => $id,
                    'kuji_komentar_id' => $idUjiKomentar[$key],
                    'kuji_hitung' => json_encode([
                        'p' => $hasilPKomentar[$key],
                        'n' => $hasilNKomentar[$key]
                    ]),
                    'kuji_rasio' => $rasio,
                    'kuji_akhir' => $akhirKomentar[$key]
                ];
                DB::table('komentar_uji')->insert($simpanUjiKomentar);
//                var_dump($simpanUjiKomentar);
            }
            return redirect('film/perhitungan/'.$id);
        }
        return redirect('film/perhitungan/'.$id);

    }

    public function detailPerhitungan($id){
        $data['hasil'] = DB::table('hasil')
            ->where('hasil_id',$id)
            ->first();
        $data['training'] = DB::table('training')
            ->where('training_film_id',$data['hasil']->hasil_film_id)
            ->where('training_rasio',$data['hasil']->hasil_rasio)
            ->get();
        $data['uji'] = DB::table('uji')
            ->where('uji_film_id',$data['hasil']->hasil_film_id)
            ->where('uji_rasio',$data['hasil']->hasil_rasio)
            ->get();
        $data['komentarUji'] = DB::table('komentar_uji')
            ->join('komentar','komentar.komentar_id','=','komentar_uji.kuji_komentar_id')
            ->where('kuji_film_id',$data['hasil']->hasil_film_id)
            ->where('kuji_rasio',$data['hasil']->hasil_rasio)
            ->get();
        return view('film.detail-perhitungan',$data);
    }

    //------------------------------------------------------------------------------------------

    function doTfIdf($list_word,$totaldoc,$total_positif,$total_negatif){
        foreach ($list_word as $key=>$value){
            $expldoc = explode(",",$value["doc"]);
            $ddf = $totaldoc/count($expldoc);
            $df = log10($ddf);
            $idf1 = 1+(log10($ddf));
            $w=null;
            foreach ($expldoc as $item){
                if ($w == null){
                    $w = $idf1;
                }
                else{
                    $w = $w.",".$idf1;
                }
            }
            $doccat = explode(",",$value['type']);

            $p = (0+1)/(($total_positif+$total_negatif)+$total_positif);
            $n = (0+1)/(($total_positif+$total_negatif)+$total_negatif);
            foreach ($doccat as $item){
                if ($item == "positif"){
                    $p = ($df+1)/(($total_positif+$total_negatif)+$total_positif);
                }
                else{
                    $n = ($df+1)/(($total_positif+$total_negatif)+$total_negatif);
                }
            }
            $list_word[$key] = array(
                "doc"=>$value['doc'],
                "df"=>$value['df'],
                "ddf"=>$ddf,
                "idf1"=>$idf1,
                "w"=> $w,
                "type"=>$value['type'],
                "p"=>$p,
                "n"=>$n,
            );
        }
        return $list_word;
//        echo "<pre>";
//        var_dump($list_word);
//        echo "</pre>";
    }

    function cekKamus($kata)
    {
        $ada = DB::table('kata_dasar')
            ->where('kata_dasar','like',$kata)
            ->first('kata_dasar');

        if ($ada != null) {
            return true; // True jika ada
        } else {
            return false; // jika tidak ada FALSE
        }
    }

//fungsi untuk menghapus suffix seperti -ku, -mu, -kah, dsb
    function Del_Inflection_Suffixes($kata)
    {
        $kataAsal = $kata;

        if (preg_match('/([km]u|nya|[kl]ah|pun)\z/i', $kata)) { // Cek Inflection Suffixes
            $__kata = preg_replace('/([km]u|nya|[kl]ah|pun)\z/i', '', $kata);

            return $__kata;
        }
        return $kataAsal;
    }

// Cek Prefix Disallowed Sufixes (Kombinasi Awalan dan Akhiran yang tidak diizinkan)
    function Cek_Prefix_Disallowed_Sufixes($kata)
    {

        if (preg_match('/^(be)[[:alpha:]]+/(i)\z/i', $kata)) { // be- dan -i
            return true;
        }

        if (preg_match('/^(se)[[:alpha:]]+/(i|kan)\z/i', $kata)) { // se- dan -i,-kan
            return true;
        }

        if (preg_match('/^(di)[[:alpha:]]+/(an)\z/i', $kata)) { // di- dan -an
            return true;
        }

        if (preg_match('/^(me)[[:alpha:]]+/(an)\z/i', $kata)) { // me- dan -an
            return true;
        }

        if (preg_match('/^(ke)[[:alpha:]]+/(i|kan)\z/i', $kata)) { // ke- dan -i,-kan
            return true;
        }
        return false;
    }

// Hapus Derivation Suffixes ("-i", "-an" atau "-kan")
    function Del_Derivation_Suffixes($kata)
    {
        $kataAsal = $kata;
        if (preg_match('/(i|an)\z/i', $kata)) { // Cek Suffixes
            $__kata = preg_replace('/(i|an)\z/i', '', $kata);
            if ($this->cekKamus($__kata)) { // Cek Kamus
                return $__kata;
            } else if (preg_match('/(kan)\z/i', $kata)) {
                $__kata = preg_replace('/(kan)\z/i', '', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata;
                }
            }
            /*– Jika Tidak ditemukan di kamus –*/
        }
        return $kataAsal;
    }

// Hapus Derivation Prefix ("di-", "ke-", "se-", "te-", "be-", "me-", atau "pe-")
    function Del_Derivation_Prefix($kata)
    {
        $kataAsal = $kata;

        /* —— Tentukan Tipe Awalan ————*/
        if (preg_match('/^(di|[ks]e)/', $kata)) { // Jika di-,ke-,se-
            $__kata = preg_replace('/^(di|[ks]e)/', '', $kata);

            if ($this->cekKamus($__kata)) {
                return $__kata;
            }

            $__kata__ = $this->Del_Derivation_Suffixes($__kata);

            if ($this->cekKamus($__kata__)) {
                return $__kata__;
            }

            if (preg_match('/^(diper)/', $kata)) { //diper-
                $__kata = preg_replace('/^(diper)/', '', $kata);
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);

                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }
            }

            if (preg_match('/^(ke[bt]er)/', $kata)) {  //keber- dan keter-
                $__kata = preg_replace('/^(ke[bt]er)/', '', $kata);
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);

                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }
            }
        }

        if (preg_match('/^([bt]e)/', $kata)) { //Jika awalannya adalah "te-","ter-", "be-","ber-"

            $__kata = preg_replace('/^([bt]e)/', '', $kata);
            if ($this->cekKamus($__kata)) {
                return $__kata; // Jika ada balik
            }

            $__kata = preg_replace('/^([bt]e[lr])/', '', $kata);
            if ($this->cekKamus($__kata)) {
                return $__kata; // Jika ada balik
            }

            $__kata__ = $this->Del_Derivation_Suffixes($__kata);
            if ($this->cekKamus($__kata__)) {
                return $__kata__;
            }
        }

        if (preg_match('/^([mp]e)/', $kata)) {
            $__kata = preg_replace('/^([mp]e)/', '', $kata);
            if ($this->cekKamus($__kata)) {
                return $__kata; // Jika ada balik
            }
            $__kata__ = $this->Del_Derivation_Suffixes($__kata);
            if ($this->cekKamus($__kata__)) {
                return $__kata__;
            }

            if (preg_match('/^(memper)/', $kata)) {
                $__kata = preg_replace('/^(memper)/', '', $kata);
                if ($this->cekKamus($kata)) {
                    return $__kata;
                }
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }
            }

            if (preg_match('/^([mp]eng)/', $kata)) {
                $__kata = preg_replace('/^([mp]eng)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }

                $__kata = preg_replace('/^([mp]eng)/', 'k', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }
            }

            if (preg_match('/^([mp]eny)/', $kata)) {
                $__kata = preg_replace('/^([mp]eny)/', 's', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }
            }

            if (preg_match('/^([mp]e[lr])/', $kata)) {
                $__kata = preg_replace('/^([mp]e[lr])/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }
            }

            if (preg_match('/^([mp]en)/', $kata)) {
                $__kata = preg_replace('/^([mp]en)/', 't', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }

                $__kata = preg_replace('/^([mp]en)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }
            }

            if (preg_match('/^([mp]em)/', $kata)) {
                $__kata = preg_replace('/^([mp]em)/', '', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata; // Jika ada balik
                }
                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }

                $__kata = preg_replace('/^([mp]em)/', 'p', $kata);
                if ($this->cekKamus($__kata)) {
                    return $__kata; // Jika ada balik
                }

                $__kata__ = $this->Del_Derivation_Suffixes($__kata);
                if ($this->cekKamus($__kata__)) {
                    return $__kata__;
                }
            }
        }
        return $kataAsal;
    }

//fungsi pencarian akar kata
    function stemming($kata)
    {

        $kataAsal = $kata;

        $cekKata = $this->cekKamus($kata);
        if ($cekKata == true) { // Cek Kamus
            return $kata; // Jika Ada maka kata tersebut adalah kata dasar
        } else { //jika tidak ada dalam kamus maka dilakukan stemming
            $kata = $this->Del_Inflection_Suffixes($kata);
            if ($this->cekKamus($kata)) {
                return $kata;
            }

            $kata = $this->Del_Derivation_Suffixes($kata);
            if ($this->cekKamus($kata)) {
                return $kata;
            }

            $kata = $this->Del_Derivation_Prefix($kata);
            if ($this->cekKamus($kata)) {
                return $kata;
            }
        }
    }
}
