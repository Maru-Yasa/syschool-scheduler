<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Guru;
use App\Models\Hari;
use App\Models\Semester;
use App\Models\SettingJP;
use App\Models\SettingUmum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Psy\Readline\Hoa\Console;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if (count(User::all()) == 0) {
            User::create([
                'username' => 'root',
                'name' => 'root',
                'email' => 'root@root.com',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]);

            $guru = Guru::create([
                'nama' => 'Budiyanto',
            ]);

            User::create([
                'username' => 'guru_budi',
                'name' => 'Budiyanto',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'id_guru' => $guru->id

            ]);

        }

        if (count(SettingUmum::all()) == 0) {
            SettingUmum::create([
                'nama_sekolah' => 'SMA 1 CONTOH',
                "tingkat" => "sma",
                "alamat" => ""
            ]);
        }

        if (count(SettingJP::all()) == 0) {
            SettingJP::create([
                'jumlah_jp' => 10,
                'durasi_jp' => 45,
                'mulai_jp' => '07:15'
            ]);
        }

        if (count(Hari::all()) == 0) {
            $hari = ['Senin', 'Selasa', "Rabu", 'Kamis', "Juma't"];
            foreach ($hari as $key => $value) {
                $urut = $key + 1;
                Hari::create([
                    'nama_hari' => $value,
                    'urut' => $urut
                ]);
            }
        }
    }
}
