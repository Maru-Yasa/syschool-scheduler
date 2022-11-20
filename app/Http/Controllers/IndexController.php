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
        $settingUmum = SettingUmum::all()->first();
        return view('jurusanPage', ['jurusan' => $jurusan, 'setting_umum' => $settingUmum]);
    }
    public function kelas($id)
    {
        $kelas = Kelas::all()->where('id_jurusan', $id);
        $settingUmum = SettingUmum::all()->first();
        return view('kelasPage', ['kelas' => $kelas, 'id_jurusan' => $id, 'setting_umum' => $settingUmum]);
    }
    public function jadwal($id)
    {
        $settingUmum = SettingUmum::all()->first();
        return view('jadwalPage', ['id_jurusan' => $id, 'setting_umum' => $settingUmum]);
    }
}
