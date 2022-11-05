<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwal';

    protected $guarded = [];

    public function guru(){
        return $this->belongsTo('App\Models\Guru', 'id_guru');
    }

    public function mapel()
    {
        return $this->belongsTo('App\Models\Mapel', 'id_mapel');
    }

    public function kelas()
    {
        return $this->belongsTo('App\Models\Kelas', 'id_kelas');
    }

    public function ruang_kelas()   
    {
        return $this->belongsTo('App\Models\RuangKelas', 'id_ruang_kelas');
    }

    public function hari()
    {
        return $this->belongsTo('App\Models\Hari', 'id_hari');
    }

}
