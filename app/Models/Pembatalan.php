<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembatalan extends Model
{
    // Post -> table_name = pembatalan
    // custome table name :
    // protected $table='table_name'
    protected $table = 'pembatalan';
    // define coloumn name
    protected $fillable = array('pemesanan_id', 'user_id');
    public $timestamps = true;
}