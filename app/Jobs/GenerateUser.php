<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class GenerateUser implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $username, $name, $password, $role, $id_guru;

    public function __construct($username, $name, $id_guru, $password, $role = 'guru')
    {
        $this->username = $username;
        $this->name = $name;
        $this->password = $password;
        $this->role = $role;
        $this->id_guru = $id_guru;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        User::create([
            'username' => $this->username,
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'id_guru' => $this->id_guru
        ]);
        return;
    }
}
