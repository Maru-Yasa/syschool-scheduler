<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\TingkatKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TingkatKelasController extends Controller
{

    public function view(Request $req)
    { 
        return view('tingkat');
    }

    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'tingkat' => 'required',
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();

            $tingkat = TingkatKelas::all()->where('id', $req->id)->first();

            $tingkat->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $tingkat
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
                'tingkat' => 'required',
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->except('_token');
            $newData = TingkatKelas::create($data);
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
            $data = TingkatKelas::all()->where('id', $id)->first();
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
        $allTingkatKelas = [];
        $allTingkatKelas = TingkatKelas::all();
        return DataTables::of($allTingkatKelas)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editTingkatKelas(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteTingkatKelas('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->rawColumns(['aksi'])->make(true);
    }

    public function getById(Request $req)
    {
        $tingkat = TingkatKelas::all()->where('id', $req->id_tingkat)->first();
        return response($tingkat);
    }



}
