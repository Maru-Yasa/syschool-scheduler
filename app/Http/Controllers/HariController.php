<?php

namespace App\Http\Controllers;

use App\Models\Hari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HariController extends Controller
{
    public function view(Request $req)
    {
        return view('hari');
    }

    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama_hari' => 'required|min:3|max:50',
                'urut' => 'required|numeric|unique:hari,urut,'.$req->id,
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();

            $hari = Hari::all()->where('id', $req->id)->first();

            $hari->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $hari
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
                'nama_hari' => 'required|min:3|max:50',
                'urut' => 'required|numeric|unique:hari,urut',
            ]);
            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->except('_token');
            $newData = Hari::create($data);
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

    public function delete(Request $req, $id)
    {
        try {
            $data = Hari::all()->where('id', $id)->first();
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
        $allJurusan = DB::table('hari')->select()->orderBy('urut')->get();
        return DataTables::of($allJurusan)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editJurusan(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteJurusan('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

}
