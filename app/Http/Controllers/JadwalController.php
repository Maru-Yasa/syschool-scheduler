<?php

namespace App\Http\Controllers;

use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\SettingJP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{

    public function view(Request $request)
    {
        return view('jadwal');
    }

    public function addView(Request $request)
    {
        $hari = Hari::orderBy('urut', 'ASC')->get();
        $setting_jp = SettingJP::all()->first();
        return view('tambah-jadwal', $data=[
            'master_hari' => $hari,
            'master_setting_jp' => $setting_jp
        ]);
    }

    public function add(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id_semester' => 'required',
            'id_jurusan' => 'required',
            'id_kelas' => 'required',
            'id_ruang_kelas' => 'required',
            'id_guru' => 'required',
            'id_mapel' => 'required',
            'id_hari' => 'required',
            'jam_awal' => 'required',
            'jam_akhir' => 'required'
        ]);

        if($validator->fails()){
            return response([
                "status" => false,
                "messages" => $validator->errors(),
                "message" => "Terjadi galat, mohon cek lagi"
            ], 200);        
        }

        $jadwal = Jadwal::create($req->except('id_jurusan'));
        return response([
            'status' => true,
            'message' => "Sukses menambah data",
            'data' => $jadwal
        ], 200); 
    }

    public function getJadwalByKelas(Request $req)
    {
     
        $id_kelas = $req->query('id_kelas');
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_kelas', $id_kelas)->get();
        $jadwal_group = [];
        foreach ($jadwal_raw as $key => $jadwal) {
            $jadwal_group[$jadwal->id_kelas][] = $jadwal;
        }

        return response($jadwal_group);

    }

    public function edit(Request $request)
    {
        
    }

    public function delete(Request $request)
    {
        
    }

}
