<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
