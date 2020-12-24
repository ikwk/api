<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = "tb_prodi";

    protected $fillable = [
        'id_prodi', 'nama_prodi',
    ];
}
