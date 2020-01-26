<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    // Post -> table_name = pembayaran
    // custome table name :
    // protected $table='table_name'
    protected $table = 'pembayaran';
    // define coloumn name
    protected $fillable = array('pemesanan_id', 'user_id', 'totalbayar');
    public $timestamps = true;
}