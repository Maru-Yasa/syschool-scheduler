<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\RuangKelas;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class RuangKelasController extends Controller
{
    public function view(Request $req)
    {
        return view('ruang_kelas');
    }
    public function getAll(Request $req)
    {
        // htmlspecialchars(json_encode($arr), ENT_QUOTES, 'UTF-8')
        $allRuangKelas = RuangKelas::all();
        return DataTables::of($allRuangKelas)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editRuangKelas(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteRuangKelas('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->addColumn('ruang_kelas', function($row){
            return $row->nama;
        })->addColumn('owner', function($row){
            if($row->owner === '-'){
                return '-';
            }else{
                $owner = Jurusan::all()->where('id', $row->owner)->first();
                return $owner->nama_jurusan;
            }
        })->rawColumns(['aksi', 'ruang_kelas', 'owner'])->make(true);
    }
    public function tambah(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama' => 'required',
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            if(!$req->owner || !$req->owner === '-'){
                $data = $req->except('_token');
                $data['owner'] = '-';
                $newData = RuangKelas::create($data);
                return response([
                    'status' => true,
                    'message' => "Sukses menambah data",
                    'data' => $newData
                ], 200);                  
            }

            $data = $req->except('_token');
            $newData = RuangKelas::create($data);
            return response([
                'status' => true,
                'message' => "Sukses menambah data",
                'data' => $newData
            ], 200);            
        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ], 200); 
        }
    }
    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama' => 'required',
                'owner' => ''
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();

            $ruang_kelas = RuangKelas::all()->where('id', $req->id)->first();

            $ruang_kelas->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $ruang_kelas
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
            $data = RuangKelas::all()->where('id', $id)->first();
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
}
