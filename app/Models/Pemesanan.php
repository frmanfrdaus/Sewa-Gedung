<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    // Post -> table_name = pemesanan
    // custome table name :
    // protected $table='table_name'
    protected $table='pemesanan';
    // define coloumn name
    protected $fillable = array('gedung_id', 'user_id', 'tanggalpesan', 'totalbayar');
    public $timestamps = true;
}