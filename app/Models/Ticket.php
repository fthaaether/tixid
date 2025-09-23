<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'schedule_id', 'promo_id',
    'rows_of_sets', 'quantity', 'total_price', 'date', 'actived'];
}
