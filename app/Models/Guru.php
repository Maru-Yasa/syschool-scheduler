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

    static function isHaveUser($id_guru)
    {
        $guru_user = User::all()->where('id_guru', $id_guru)->count();
        if($guru_user < 1){
            return false;
        }else{
            return true;
        }
    }

}
