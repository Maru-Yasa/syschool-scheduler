<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\SettingUmum;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function jurusan()
    {
        $jurusan = Jurusan::all();
       return view('jurusanPage', ['jurusan' => $jurusan]);
    }
    public function kelas($id)
    {
        $kelas = Kelas::all()->where('id_jurusan', $id);
       return view('kelasPage', ['kelas' => $kelas, 'id_jurusan' => $id]);
    }
    public function jadwal($id)
    {
       return view('jadwalPage', ['id_jurusan' => $id]);
    }
}
