<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function view(Request $req)
    {
        return view('jurusan');
    }
}
