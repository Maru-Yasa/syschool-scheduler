<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MapelController extends Controller
{
    public function view(Request $req)
    {
        return view('mapel');
    }
    public function getAll(Request $req)
    {
        // htmlspecialchars(json_encode($arr), ENT_QUOTES, 'UTF-8')
        $allMapel = Mapel::all();
        return DataTables::of($allMapel)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editMapel(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteMapel('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->addColumn('nama_mapel', function($row){
            return $row->nama_mapel;
            
        })->rawColumns(['aksi', 'nama_mapel'])->make(true);
    }
    public function tambah(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama_mapel' => 'required'
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->except('_token');
            $newData = Mapel::create($data);
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
                'nama_mapel' => 'required'
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();

            $mapel = Mapel::all()->where('id', $req->id)->first();

            $mapel->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $mapel
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
            $data = Mapel::all()->where('id', $id)->first();
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
