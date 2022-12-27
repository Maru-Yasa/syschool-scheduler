<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\RuangKelas;
use App\Models\Semester;
use App\Models\SettingJeda;
use App\Models\SettingJP;
use App\Models\SettingUmum;
use Barryvdh\DomPDF\PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDO;

class JadwalController extends Controller
{
    protected $id_semester;

    public function __construct()
    {
        $this->id_semester  = SettingUmum::all()->first()->id_semester;
    }

    public function view(Request $request)
    {
        $hari = Hari::orderBy('urut', 'ASC')->get();
        $setting_jp = SettingJP::all()->first();
        $jeda = SettingJeda::all();
        $jurusan = Jurusan::all();
        $settingUmum = SettingUmum::all()->first();
        return view('jadwal', $data=[
            'master_hari' => $hari,
            'master_setting_jp' => $setting_jp,
            'master_jeda' => $jeda,
            'master_jurusan' => $jurusan,
            'master_setting_umum' => $settingUmum
        ]);
    }

    public function addView(Request $request)
    {
        $hari = Hari::orderBy('urut', 'ASC')->get();
        $setting_jp = SettingJP::all()->first();
        $settingUmum = SettingUmum::all()->first();

        return view('tambah-jadwal', $data=[
            'master_hari' => $hari,
            'master_setting_jp' => $setting_jp,
            'master_setting_umum' => $settingUmum

        ]);
    }

    public function add(Request $req)
    {
        try {
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
    
    
            // validate guru, apakah sudah mengajar atau belum
            $between_number = [$req->jam_awal, (int)$req->jam_akhir];
            $check_jadwal_guru = true;
            $_jadwal = Jadwal::
                where('id_semester', $this->id_semester)
                ->whereNot('id', $req->id)
                ->where('id_guru', $req->id_guru)
                ->where('id_hari', $req->id_hari)->get();
            if(count($_jadwal) > 0){
                for ($i=0; $i < count($_jadwal); $i++) { 
                    $jadwal = $_jadwal[$i];
                    $between_jadwal = range($jadwal->jam_awal, $jadwal->jam_akhir);
                    $between_input = $between_number;
                    if(count(array_intersect($between_jadwal, $between_input)) > 0){
                        $check_jadwal_guru = false;
                        break;
                    }
                }
            }
    
            if(!$check_jadwal_guru){
                return response([
                    'status' => false,
                    'message' => 'Guru sudah ada jam',
                    'data' => '',
                    'debug' => [
                        'count' => $check_jadwal_guru,
                        'between' => $between_number
                    ],
                ]);
            }
    
            // validate ruang kelas, apakah sudah ditempati atau belum
            $check_jadwal_ruang = true;
            $_jadwal = Jadwal::
                where('id_semester', $this->id_semester)
                ->whereNot('id', $req->id)
                ->where('id_ruang_kelas', $req->id_ruang_kelas)
                ->where('id_hari', $req->id_hari)->get();
            if(count($_jadwal) > 0){
                for ($i=0; $i < count($_jadwal); $i++) { 
                    $jadwal = $_jadwal[$i];
                    $between_jadwal = range($jadwal->jam_awal, $jadwal->jam_akhir);
                    $between_input = $between_number;
                    if(count(array_intersect($between_jadwal, $between_input)) > 0){
                        $check_jadwal_ruang = false;
                        break;
                    }
                }
            }
                
            // if($req->jam_awal !== $req->jam_akhir){
            //     $check_jadwal_ruang = Jadwal::all()->where('id_semester', $this->id_semester)->whereNotIn('id', $req->id)->where('id_ruang_kelas', $req->id_ruang_kelas)->where('id_hari', $req->id_hari)->whereBetween('jam_awal', $between_number)->whereBetween('jam_akhir', $between_number);
            // }else{
            //     $check_jadwal_ruang = Jadwal::all()->where('id_semester', $this->id_semester)->where('id_ruang_kelas', $req->id_ruang_kelas)->where('id_hari', $req->id_hari)->whereBetween('jam_awal', $between_number);
            // }
    
            if(!$check_jadwal_ruang){
                return response([
                    'status' => false,
                    'message' => 'Ruang kelas sudah dipakai',
                    'data' => '',
                    'debug' => [
                        'count' => $check_jadwal_ruang,
                        'between' => $between_number
                    ],
                ]);
            }
    
    
            $data_to_submit = $req->except(['id_jurusan']);
            $data_to_submit['id_semester'] = SettingUmum::all()[0]->id_semester;
            $jadwal = Jadwal::create($data_to_submit);
            return response([
                'status' => true,
                'message' => "Sukses menambah data",
                'data' => $jadwal,
                'debug' => [
                    'count' => $check_jadwal_guru,
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

    public function getJadwalByKelas(Request $req)
    {
        $id_kelas = $req->query('id_kelas');
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_kelas', $id_kelas)->where('id_semester', $this->id_semester)->get();
        $jadwal_group = [];
        foreach ($jadwal_raw as $key => $jadwal) {
            $jadwal_group[$jadwal->id_kelas][] = $jadwal;
        }

        return response($jadwal_group);

    }

    public function getAllJadwal(Request $req)
    {
        $kelas_id_only = Kelas::all(['id']);
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_semester', $this->id_semester)->get();
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

    public function getJadwalByIdJurusan(Request $req)
    {
        if(!$req->id_jurusan){
            return response([
                
            ]);
        }
        $kelas_id_only = Kelas::where('id_jurusan', (int)$req->id_jurusan)->get(['id']);
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_semester', $this->id_semester)->get();
        $jadwal_group = [];
        foreach ($kelas_id_only as $key => $value) {
            $jadwal_group[$value->id] = [];
        }

        foreach ($jadwal_raw as $key => $jadwal) {
            if($jadwal->kelas->id_jurusan == $req->id_jurusan){
                $jadwal_group[$jadwal->id_kelas][] = $jadwal;
            }
        }

        return response($jadwal_group);
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

            // return response($req->all());

            $jadwal = Jadwal::all()->where('id', $req->id)->first();
            
            // validate guru, apakah sudah mengajar atau belum
            $between_number = [$req->jam_awal, (int)$req->jam_akhir];
            $check_jadwal_guru = true;
            $_jadwal = Jadwal::
                where('id_semester', $this->id_semester)
                ->whereNot('id', $req->id)
                ->where('id_guru', $req->id_guru)
                ->where('id_hari', $req->id_hari)->get();
            if(count($_jadwal) > 0){
                for ($i=0; $i < count($_jadwal); $i++) { 
                    $obj = $_jadwal[$i];
                    $between_jadwal = range($obj->jam_awal, $obj->jam_akhir);
                    $between_input = $between_number;
                    if(count(array_intersect($between_jadwal, $between_input)) > 0){
                        $check_jadwal_guru = false;
                        break;
                    }
                }
            }

            if(!$check_jadwal_guru){
                return response([
                    'status' => false,
                    'message' => 'Guru sudah ada jam',
                    'data' => '',
                    'debug' => [
                        'count' => $check_jadwal_guru,
                        'between' => $between_number
                    ],
                ]);
            }

            // validate ruang kelas, apakah sudah ditempati atau belum
            $check_jadwal_ruang = true;
            $_jadwal = Jadwal::
                where('id_semester', $this->id_semester)
                ->whereNot('id', $req->id)
                ->where('id_ruang_kelas', $req->id_ruang_kelas)
                ->where('id_hari', $req->id_hari)->get();
            if(count($_jadwal) > 0){
                for ($i=0; $i < count($_jadwal); $i++) { 
                    $obj = $_jadwal[$i];
                    $between_jadwal = range($obj->jam_awal, $obj->jam_akhir);
                    $between_input = $between_number;
                    if(count(array_intersect($between_jadwal, $between_input)) > 0){
                        $check_jadwal_ruang = false;
                        break;
                    }
                }
            }

            if(!$check_jadwal_ruang){
                return response([
                    'status' => false,
                    'message' => 'Ruang kelas sudah dipakai',
                    'data' => '',
                    'debug' => [
                        'count' => $check_jadwal_ruang,
                        'between' => $between_number
                    ],
                ]);
            }

            $jadwal->update($req->all());

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $jadwal,
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

    public function cetak(Request $req)
    {

        $kelas_id_only = Kelas::all(['id']);
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_semester', $this->id_semester)->get();
        $jadwal_group = [];
        foreach ($kelas_id_only as $key => $value) {
            $jadwal_group[$value->id] = [];
        }

        foreach ($jadwal_raw as $key => $jadwal) {
            $jadwal_group[$jadwal->id_kelas][] = $jadwal;
        }

        $hari = Hari::orderBy('urut', 'ASC')->get();
        $setting_jp = SettingJP::all()->first();
        $jeda = SettingJeda::all();
        $kelas_raw = Kelas::all();
        $kelas = [];
        foreach ($kelas_raw as $key => $value) {
            $kelas[$value->id] = $value;
        }
        $data=[
            'master_hari' => $hari,
            'master_setting_jp' => $setting_jp,
            'master_jeda' => $jeda,
            'master_jadwal' => $jadwal_group,
            'master_kelas' => $kelas
        ];
        $view = view('cetak.jadwal', $data)->render();
        return view('cetak.jadwal', $data);
    }

    public function cetakBerdasarkanGuru(Request $req)
    {
        $list_id_guru = Guru::all(['id']);
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_semester', $this->id_semester)->get();
        $jadwal_group = [];
        foreach ($list_id_guru as $key => $guru) {
            $jadwal_group[$guru->id] = [];
        }

        foreach ($jadwal_raw as $key => $jadwal) {
            $jadwal_group[$jadwal->id_guru][] = $jadwal;
        }

        $hari = Hari::orderBy('urut', 'ASC')->get();
        $setting_jp = SettingJP::all()->first();
        $jeda = SettingJeda::all();
        $guru_raw = Guru::all();
        $guru = [];
        foreach ($guru_raw as $key => $value) {
            $guru[$value->id] = $value;
        }
        $data=[
            'master_hari' => $hari,
            'master_setting_jp' => $setting_jp,
            'master_jeda' => $jeda,
            'master_jadwal' => $jadwal_group,
            'master_guru' => $guru
        ];
        $view = view('cetak.jadwal_guru', $data);
        return $view;
    }

    public function cetakBerdasarkanIdGuru(Request $req, $id_guru)
    {
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_semester', $this->id_semester)->where('id_guru', $id_guru)->get();
        $jadwal_group = [];
        $jadwal_group[$id_guru] = [];
        foreach ($jadwal_raw as $key => $jadwal) {
            $jadwal_group[$jadwal->id_guru][] = $jadwal;
        }
        $hari = Hari::orderBy('urut', 'ASC')->get();
        $setting_jp = SettingJP::all()->first();
        $jeda = SettingJeda::all();
        $guru_raw = Guru::all()->where('id', $id_guru);
        $guru = [];
        foreach ($guru_raw as $key => $value) {
            $guru[$value->id] = $value;
        }
        $data=[
            'master_hari' => $hari,
            'master_setting_jp' => $setting_jp,
            'master_jeda' => $jeda,
            'master_jadwal' => $jadwal_group,
            'master_guru' => $guru
        ];
        $view = view('cetak.jadwal_guru', $data);
        return $view;
    }

    public function getJadwalBerdasarkanIdGuru(Request $req, $id_guru)
    {
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_semester', $this->id_semester)->where('id_guru', $id_guru)->get();
        $jadwal_group = [];
        $jadwal_group[$id_guru] = [];
        foreach ($jadwal_raw as $key => $jadwal) {
            $jadwal_group[$jadwal->id_guru][] = $jadwal;
        }

        $guru_raw = Guru::all();
        $guru = [];
        foreach ($guru_raw as $key => $value) {
            $guru[$value->id] = $value;
        }

        return response($jadwal_group);
    }

    // public function previewJadwalForGuru(Request $req)
    // {
    //     $jadwal = Jadwal::all()->where('id_guru', $req->id_guru);
    //     $jadwal   
    // }

}
