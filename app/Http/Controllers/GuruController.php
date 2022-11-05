<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class GuruController extends Controller 
{

    public function view(Request $req)
    {
        return view('guru');
    }

    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama' => 'required|min:3|max:50',
                'profile' => 'max:10000|mimes:jpg,jepg,png'
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();

            $guru = Guru::all()->where('id', $req->id)->first();

            if($req->file('profile')){
                $oldFilename = $guru->profile;
                $file = $req->file('profile');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('image/guru'), $filename);
                $data['profile'] = $filename;

                if($oldFilename !== 'default.png' && file_exists(public_path('image/guru/'.$oldFilename))){
                    unlink(public_path('image/guru/'.$oldFilename));
                }
            }

            $guru->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $guru
            ], 200);   

        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ], 200);         
        }
    }

    public function tambah(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama' => 'required|min:3|max:20',
                'profile' => 'max:10000|mimes:jpg,jepg,png'
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->except('_token');
            if($req->file('profile')){
                $file = $req->file('profile');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('image/guru'), $filename);
                $data['profile'] = $filename;
            }

            $newGuru = Guru::create($data);
            return response([
                'status' => true,
                'message' => "Sukses menambah data",
                'data' => $newGuru
            ], 200);            
        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ], 200); 
        }


    }

    public function delete(Request $req, $id)
    {
        try {
            $data = Guru::all()->where('id', $id)->first();
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

    public function getAll(Request $req)
    {
        // htmlspecialchars(json_encode($arr), ENT_QUOTES, 'UTF-8')
        $allGuru = Guru::all();
        return DataTables::of($allGuru)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editGuru(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteGuru('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->addColumn('nama', function($row){
            $img = '<div class="d-flex justify-content-start gap-3 align-items-center">
            <img src="'.url('image/guru/'.$row->profile).'" class="img-fluid rounded-circle mr-3" style="object-fit: cover;width:64px;height:64px;" alt=""> 
            <span class="">'.$row->nama.'</span>
            </div>';
            return $img;
        })->addColumn('nama_raw', function($row){
            return $row->nama;
        })->rawColumns(['aksi', 'nama'])->make(true);
    }

    public function api(Request $req)
    {
        $guru = Guru::all();
        return response([
            "status" => 'success',
            'message' => 'berhasil mengambil data guru',
            'data' => $guru
        ], 200);
    }

    public function getJadwalGuru(Request $req)
    {
        $id_guru = $req->query('id_guru');
        $jadwal_raw = Jadwal::with('guru', 'mapel', 'kelas', 'ruang_kelas', 'hari')->where('id_guru', $id_guru)->get();
        $jadwal_group = [];
        foreach ($jadwal_raw as $key => $jadwal) {
            $jadwal_group[$jadwal->id_kelas][] = $jadwal;
        }

        return response($jadwal_group);

    }

}
