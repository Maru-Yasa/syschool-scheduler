<?php

namespace App\Http\Controllers;

use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\SettingJeda;
use App\Models\SettingJP;
use App\Models\SettingUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDO;

class JadwalController extends Controller
{

    public function view(Request $request)
    {
        $hari = Hari::orderBy('urut', 'ASC')->get();
        $setting_jp = SettingJP::all()->first();
        $jeda = SettingJeda::all();
        return view('jadwal', $data=[
            'master_hari' => $hari,
            'master_setting_jp' => $setting_jp,
            'master_jeda' => $jeda
        ]);
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


        $between_number = [$req->jam_awal, (int)$req->jam_akhir];
        $check_jadwal = [];
        if($req->jam_awal !== $req->jam_akhir){
            $check_jadwal = Jadwal::all()->whereNotIn('id', $req->id)->where('id_guru', $req->id_guru)->where('id_hari', $req->id_hari)->whereBetween('jam_awal', $between_number)->whereBetween('jam_akhir', $between_number);
        }else{
            $check_jadwal = Jadwal::all()->where('id_guru', $req->id_guru)->where('id_hari', $req->id_hari)->whereBetween('jam_awal', $between_number);
        }

        if(count($check_jadwal) !== 0){
            return response([
                'status' => false,
                'message' => 'Guru sudah ada jadwal',
                'data' => '',
                'debug' => [
                    'count' => $check_jadwal,
                    'between' => $between_number
                ],
            ]);
        }
        $data_to_submit = $req->all();
        $data_to_submit['id_semester'] = SettingUmum::all()[0]->id_semester;
        $jadwal = Jadwal::create($data_to_submit);
        return response([
            'status' => true,
            'message' => "Sukses menambah data",
            'data' => $jadwal,
            'debug' => [
                'count' => $check_jadwal,
                'between' => $between_number
            ],
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

    public function getAllJadwal(Request $req)
    {
        $kelas_id_only = Kelas::all(['id']);
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->get();
        $jadwal_group = [];
        foreach ($kelas_id_only as $key => $value) {
            $jadwal_group[$value->id] = [];
        }

        foreach ($jadwal_raw as $key => $jadwal) {
            $jadwal_group[$jadwal->id_kelas][] = $jadwal;
        }

        return response($jadwal_group);
    }

    public function getJadwalById(Request $req, $id)
    {
        $id_jadwal = $id;
        $jadwal = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id', $id_jadwal)->first();
        return response($jadwal);
    
    }
    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                "id_guru" => 'required',
                "id_mapel" => 'required',
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

            $jadwal = Jadwal::all()->where('id', $req->id)->first();

            $between_number = [$req->jam_awal, $req->jam_akhir];
            $check_jadwal = [];
            if($req->jam_awal !== $req->jam_akhir){
                $check_jadwal = Jadwal::all()->whereNotIn('id', $req->id)->where('id_guru', $req->id_guru)->where('id_hari', $req->id_hari)->whereNotIn('id',$req->id)->whereBetween('jam_awal', $between_number)->whereBetween('jam_akhir', $between_number);
            }else{
                $check_jadwal = Jadwal::all()->whereNotIn('id', $req->id)->where('id_guru', $req->id_guru)->where('id_hari', $req->id_hari)->whereBetween('jam_awal', $between_number);
            }
    
            if(count($check_jadwal) !== 0){
                return response([
                    'status' => false,
                    'message' => 'Guru sudah ada jadwal',
                    'data' => '',
                    'debug' => [
                        'count' => $check_jadwal,
                        'between' => $between_number
                    ],
                ]);
            }

            $jadwal->update($req->all());

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $jadwal,
                'debug' => [
                    'count' => $check_jadwal,
                    'between' => $between_number
                ],
            ], 200);   

        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ], 200);         
        }
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = Jadwal::all()->where('id', $id)->first();
            $data->delete();
            return response([
                'status' => true,
                'message' => "Sukses menghapus data",
                'data' => []
            ], 200);  
        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ], 200);         
        }
    }

    // public function previewJadwalForGuru(Request $req)
    // {
    //     $jadwal = Jadwal::all()->where('id_guru', $req->id_guru);
    //     $jadwal   
    // }

}
