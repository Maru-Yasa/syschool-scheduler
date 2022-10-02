<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hari extends Model
{
    use HasFactory;
    protected $table = 'hari';

    protected $guarded = [];
    protected $rules = [
        'nama_hari' => 'required|min:3|max:50',
        'urut' => 'required|numeric|unique:hari,urut',
    ];

}
