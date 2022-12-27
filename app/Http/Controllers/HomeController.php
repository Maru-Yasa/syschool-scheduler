<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Hari;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\Semester;
use App\Models\SettingJeda;
use App\Models\SettingJP;
use App\Models\SettingUmum;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $viewPath = view('home');
        if(auth()->user()->role === 'guru'){

            $dt = Carbon::parse(Carbon::now());
            $hari = Hari::orderBy('urut', 'ASC')->get();
            $setting_jp = SettingJP::all()->first();
            $jeda = SettingJeda::all();
            $jurusan = Jurusan::all();

            $_jadwal = Jadwal::all()->where('id_guru', auth()->user()->id_guru);
            $totalJamMengajar = 0;
            $totalJP = 0;
            foreach ($_jadwal as $jadwal) {
                $durasi = (int)$jadwal->jam_akhir - (int)$jadwal->jam_awal + 1;
                $totalJamMengajar += $durasi * $setting_jp->durasi_jp / 60;
                $totalJP += $durasi;
            }


            $viewPath = view('guru.home', [
                'greeting'=> $dt->greet(),
                'dt' => $dt,
                'user' => auth()->user(),
                'master_hari' => $hari,
                'master_setting_jp' => $setting_jp,
                'master_jeda' => $jeda,
                'master_jurusan' => $jurusan,
                'total_jam_mengajar' => $totalJamMengajar,
                'total_JP' => $totalJP,
                'semester_sekarang' => Semester::all()->where('id', SettingUmum::all()->first()->id_semester)->first()
            ]);
        } 
        return $viewPath;
    }
}
