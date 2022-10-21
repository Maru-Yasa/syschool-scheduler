<?php

namespace App\Http\Controllers;

use App\Models\SettingUmum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingUmumController extends Controller
{


    public function get(Request $req)
    {
        $settingUmum = SettingUmum::all()->first();
        return response([
            "data" => $settingUmum
        ], 200);
    }

    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'nama_sekolah' => 'required|min:3|max:50',
                'tingkat' => "required",
                'logo' => 'max:10000|mimes:jpg,jepg,png'
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();
            $settingUmum = SettingUmum::all()->where('id', $req->id)->first();

            if($req->file('logo')){
                $oldFilename = $settingUmum->logo;
                $file = $req->file('logo');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('image/logo'), $filename);
                $data['logo'] = $filename;

                if($oldFilename != null && $oldFilename !== 'default.png' && file_exists(public_path('image/logo/'.$oldFilename))){
                    unlink(public_path('image/logo/'.$oldFilename));
                }
            }

            $settingUmum->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $settingUmum
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
