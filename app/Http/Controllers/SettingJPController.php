<?php

namespace App\Http\Controllers;

use App\Models\SettingJP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingJPController extends Controller
{

    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'jumlah_jp' => 'required|numeric',
                'durasi_jp' => "required|numeric"
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();

            $jurusan = SettingJP::all()->where('id', $req->id)->first();

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

    public function getAll(Request $req)
    {
        $setting_jp = SettingJP::all()->first();
        return response([
            'data' => $setting_jp
        ]);
    }



}
