<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['cinema_id', 'movie_id', 'hours', 'price'];

    //schedule pegang posisi kedua, panggul relasi dengan belongsTo
    // cinema pegang posisi pertaam dan jenis (one) jd gunakan tunggal
    public function cinema() {
        return $this->belongsTo(Cinema::class);
    }
}
