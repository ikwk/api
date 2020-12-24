<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Post extends Model
{

    protected $table = "tb_posts";

    public function post()
    {
        return $this->belongTo(User::class);
    }
}
