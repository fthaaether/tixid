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

    // KARENA CINEMA PEGANG POSISI PERTAMA (ONE TO MANY : cinema dan schedules)
    // mendaftar kan jenis relasinya
    // nama relasi tunggal/jamak tergantung jenisnya. schedules (many) jamak

    public function schedules() {
        // one to one : hasOne
        // one to many : hasMany
        return $this->hasMany(Schedule::class);
    }
}
