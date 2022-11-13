<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingAdminController extends Controller
{
    public function view()
    {

        return view('settingAdmin');
    }
    public function changepassword(Request $request)
    {
        if(!Hash::check($request->currentPassword,Auth::user()->password))
        {
            return redirect()->back()->with('error1','Your current password does not matches');
        }

        if(Hash::check($request->password,Auth::user()->password))
        {
            return redirect()->back()->with('error2','New Password cannot be same as your current password');
        }

        $data = $request->validate([
            'email'     => 'required|min:8',
            'password'  => 'required|min:8',
        ]);
        $validate["password"] = Hash::make($request->password);

        $ubah=User::where('id',Auth::user()->id)->first();
        $ubah->update($data);
        return redirect()->back()->with('success','Password changed successfully !');
    
    }
    public function logout(Request $request) 
    {
        Auth::logout();
        return redirect('/login');
    }
    

}
