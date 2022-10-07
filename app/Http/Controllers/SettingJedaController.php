<?php

namespace App\Http\Controllers;

use App\Models\SettingJeda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SettingJedaController extends Controller
{
    public function view(Request $req)
    {
        return view('jeda'); 
    }

    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama_jeda' => 'required|min:3|max:50',
                'mulai_jeda' => 'required|numeric',
                'durasi_jeda' => 'required|numeric',
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();

            $jeda = SettingJeda::all()->where('id', $req->id)->first();

            $jeda->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $jeda
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
                'nama_jeda' => 'required|min:3|max:50',
                'mulai_jeda' => 'required|numeric',      
                'durasi_jeda' => 'required|numeric',      
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->except('_token');
            $newData = SettingJeda::create($data);
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
            $data = SettingJeda::all()->where('id', $id)->first();
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
        $allSettingJeda = [];

        if($req->query('search')){
            $search = $req->query('search');
            $allSettingJeda = SettingJeda::query()->where('nama_jeda', 'like', "%$search%")->get();
        }else{
            $allSettingJeda = SettingJeda::all();
        }
        return DataTables::of($allSettingJeda)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editSettingJeda(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteSettingJeda('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->addColumn('durasi_jeda', function($row){
            return $row->durasi_jeda." menit";
        })->addColumn('mulai_jeda',  function($row){
            return "JP ke ".$row->mulai_jeda;
        })->rawColumns(['aksi', 'durasi_jeda', 'mulai_jeda'])->make(true);
    }



}
