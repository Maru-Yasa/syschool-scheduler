<?php

namespace App\Console\Commands;

use App\Models\Guru;
use Illuminate\Console\Command;
use Faker\Factory as Faker;

class DummyGuru extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guru:faker {jumlah}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create dummy data guru';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $faker = Faker::create('id_ID');
        $jumlah = $this->argument('jumlah');
        for ($i=0; $i < $jumlah; $i++) { 
            Guru::create([
                'nama' => $faker->name,
            ]);
        }
    }
}
