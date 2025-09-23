<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    //mendaftarkan softDeletes
    use SoftDeletes;

    //mendaftarkan column yabg akan diisi oleh pengguna (column selain id dan timestamp)
    protected $fillable = ['name', 'location'];
}
