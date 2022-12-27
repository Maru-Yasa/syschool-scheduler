<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller 
{

    public function view(Request $req)
    {
        $guru = Guru::all();
        return view('user', [
            'master_guru' => $guru
        ]);
    }

    public function edit(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required|min:3|max:20',
                'profile' => 'max:10000|mimes:jpg,jepg,png',
                'username' => 'required|min:3|unique:users,username,'.$req->id,
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->all();
            $user = User::all()->where('id', $req->id)->first();

            if($req->role === 'admin'){
                $data['id_guru'] = null;
            }

            if($req->file('profile')){
                $oldFilename = $user->profile;
                $file = $req->file('profile');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('image/user'), $filename);
                $data['profile'] = $filename;

                if($oldFilename !== 'default.png' && file_exists(public_path('image/user/'.$oldFilename))){
                    unlink(public_path('image/user/'.$oldFilename));
                }
            }

            if(!$req->password){
                unset($data['password']);
            }else{
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return response([
                'status' => true,
                'message' => "Data berhasil di edit",
                'data' => $user
            ], 200);   

        } catch (\Throwable $th) {
            return response([
                'status' => false,
                'message' => $th->getMessage(),
                'data' => [],
            ], 200);         
        }
    }

    public function tambah(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required|min:3|max:20',
                'profile' => 'max:10000|mimes:jpg,jepg,png',
                'username' => 'required|min:3|unique:users,username',
                'password' => 'required|min:8',
            ]);

            if($validator->fails()){
                return response([
                    "status" => false,
                    "messages" => $validator->errors(),
                    "message" => "Terjadi galat, mohon cek lagi"
                ], 200);
            }

            $data = $req->except('_token');
            $data['password'] = Hash::make($data['password']);
            if($req->file('profile')){
                $file = $req->file('profile');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('image/user'), $filename);
                $data['profile'] = $filename;
            }

            $newUser = User::create($data);
            return response([
                'status' => true,
                'message' => "Sukses menambah data",
                'data' => $newUser
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
            $data = User::all()->where('id', $id)->first();
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
        $allUser = User::all();
        return DataTables::of($allUser)->addIndexColumn()->addColumn('aksi', function($row){
            $btn = '<div class="d-flex justify-content-center align-items-center">
            <button onclick="editUser(this)" data-json="'.htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8').'" type="button" class="btn mr-1 btn-warning btn-sm"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteUser('.$row->id.')" type="button" class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
        </div>';
            return $btn;
        })->addColumn('nama', function($row){
            $img = '<div class="d-flex justify-content-start gap-3 align-items-center">
            <img src="'.url('image/user/'.$row->profile).'" class="img-fluid rounded-circle mr-3" style="object-fit: cover;width:64px;height:64px;" alt=""> 
            <span class="">'.$row->name.'</span>
            </div>';
            return $img;
        })->addColumn('guru', function($row){
            $guru = Guru::all()->where('id', $row->id_guru)->first();
            if($guru){
                return "guru_".$guru->id;
            }else{
                return '-';
            }
        })->addColumn('nama_raw', function($row){
            return $row->nama;
        })->rawColumns(['aksi', 'nama'])->make(true);
    }

    public function api(Request $req)
    {
        $user = User::all();
        return response([
            "status" => 'success',
            'message' => 'berhasil mengambil data user',
            'data' => $user
        ], 200);
    }


}
