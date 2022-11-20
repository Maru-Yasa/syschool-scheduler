<?php

namespace App\Http\Controllers;

use App\Models\Hari;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\SettingJeda;
use App\Models\SettingJP;
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
        $kelas = Kelas::all()->where('id', $id)->first();
        if ($kelas) {
            $settingUmum = SettingUmum::all()->first();
            $master_hari = Hari::all();
            $master_setting_jp = SettingJP::all()->first();
            $master_jeda = SettingJeda::all();
            return view('jadwalPage', [
                'id_jurusan' => $kelas->id_jurusan, 
                'setting_umum' => $settingUmum,
                'master_hari' => $master_hari,
                'master_setting_jp' => $master_setting_jp,
                'master_jeda' => $master_jeda,
                'id_kelas' => $id
            ]);
        }else{
            return response(null,404);
        }
    }
}
