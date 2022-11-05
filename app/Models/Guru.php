<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $table = 'guru';

    protected $guarded = [];

    public function jadwal()
    {
        return $this->hasMany('App\Models\Jadwal', 'id_guru');
    }

}
