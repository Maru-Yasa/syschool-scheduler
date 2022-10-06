<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class JurusanController extends Controller
{
    public function view(Request $req)
    {
        return view('jurusan'); 
    }

    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama_jurusan' => 'required|min:3|max:50',
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();

            $jurusan = Jurusan::all()->where('id', $req->id)->first();

            $jurusan->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $jurusan
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
                'nama_jurusan' => 'required|min:3|max:50',
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->except('_token');
            $newData = Jurusan::create($data);
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
            $data = Jurusan::all()->where('id', $id)->first();
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
        $allJurusan = [];

        if($req->query('search')){
            $search = $req->query('search');
            $allJurusan = Jurusan::query()->where('nama_jurusan', 'like', "%$search%")->get();
        }else{
            $allJurusan = Jurusan::all();
        }
        return DataTables::of($allJurusan)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editJurusan(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteJurusan('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->addColumn('icon', function($row){
            $element = '<i class="fs-1 '.$row->icon.'"></i>';
            return $element;
        })->rawColumns(['aksi', 'icon'])->make(true);
    }


}
