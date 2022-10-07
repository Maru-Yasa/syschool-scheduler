<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Hari;
use App\Models\SettingJP;
use App\Models\SettingUmum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

            User::create([
                'name' => 'root',
                'email' => 'root@root.com',
                'password' => Hash::make('tempe12345'),
                'role' => 'root'
            ]);

        SettingUmum::create([
            'nama_sekolah' => 'SMA 1 CONTOH',
            "tingkat" => "sma",
            "alamat" => ""
        ]);

        SettingJP::create([
            'jumlah_jp' => 10,
            'durasi_jp' => 45
        ]);

        $hari = ['Senin', 'Selasa', "Rabu", 'Kamis', "Juma't"];
        foreach ($hari as $key => $value) {
            $urut = $key + 1;
            Hari::create([
                'nama_hari' => $value,
                'urut' => $urut
            ]);
        }



        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
