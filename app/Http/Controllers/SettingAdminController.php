<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingAdminController extends Controller
{
    public function view()
    {
        $user = auth()->user();
        if($user->id_guru){
            return view('settingAdmin', [
                'user' => $user,
                'guru' => Guru::all()->where('id', $user->id_guru)->first(),
            ]);
        }else{
            return view('settingAdmin', [
                'user' => $user
            ]);
        }
    }


    public function changeProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|regex:/^[A-Za-z0-9_]+$/|unique:users,username,'. auth()->user()->id,
            'email' => 'nullable|email|unique:users,username,'.auth()->user()->id,
        ]);

        if($validator->fails()){
            return response([
                "status" => false,
                "messages" => $validator->errors(),
                "message" => "Terjadi galat, mohon cek lagi",
            ], 200);
        }

        $user = User::all()->where('id', auth()->user()->id)->first();
        $data = $request->all();
        if($request->file('profile')){
            $oldFilename = $user->profile;
            $file = $request->file('profile');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('image/user'), $filename);
            $data['profile'] = $filename;

            if($oldFilename !== 'default.png' && file_exists(public_path('image/user/'.$oldFilename))){
                unlink(public_path('image/user/'.$oldFilename));
            }
        }
        $user->update($data);
        return response([
            'status' => true,
            'message' => "Data berhasil di edit",
            'data' => $user,
        ], 200);  

    }

    public function changeGuruProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ]); 

        if($validator->fails()){
            return response([
                "status" => false,
                "messages" => $validator->errors(),
                "message" => "Terjadi galat, mohon cek lagi",
            ], 200);
        }

        $guru = Guru::all()->where('id', auth()->user()->id_guru)->first();
        $data = $request->all();
        if($request->file('profile')){
            $oldFilename = $guru->profile;
            $file = $request->file('profile');
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
            'data' => $guru,
        ], 200); 
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8'
        ]);

        if($validator->fails()){
            return response([
                'status' => false,
                'message' => 'Terjadi galat, mohon cek lagi',
                'data' => [],
                'messages' => $validator->errors()
            ]);  
        }

        if(!Hash::check($request->old_password,Auth::user()->password))
        {
            return response([
                'status' => false,
                'message' => 'Terjadi galat, mohon cek lagi',
                'data' => [],
                'messages' => [
                    'old_password' => 'Password tidak sama dengan password lama'
                ],
            ]);
        }

        if(Hash::check($request->new_password,Auth::user()->password))
        {
            return response([
                'status' => false,
                'message' => 'Terjadi galat, mohon cek lagi',
                'data' => [],
                'messages' => [
                    'new_password' => 'Password tidak boleh sama dengan password lama'
                ],
            ]);
        }

        $user = User::where('id',Auth::user()->id)->first();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response([
            'status' => true,
            'message' => 'Sukses mengubah password',
            'data' => [],
        ]);

    }

    public function logout(Request $request) 
    {
        Auth::logout();
        return redirect('/login');
    }
    

}
