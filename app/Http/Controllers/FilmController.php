<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function submit($id){
        $komentar = DB::table('komentar')
            ->where('komentar_film_id',$id)
            ->get();
        foreach ($komentar as $key => $value){
            $clean = trim(preg_replace("/[^a-zA-Z0-9]/", " ", $value->komentar_isi));
            $caseFold = strtolower($clean);
            $token = explode(' ',$caseFold);

            echo "<pre>";
            foreach ($token as $key2 => $value2) {
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

                var_dump($stem);
            }

            echo "</pre>";
        }

    }

    //------------------------------------------------------------------------------------------

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
