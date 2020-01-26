<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembukuan extends Model
{
    // Post -> table_name = Pembukuan
    // custome table name :
    // protected $table='table_name'
    protected $table = 'pembukuan';
    // define coloumn name
    protected $fillable = array('pemesanan_id', 'user_id', 'tanggalpembukuan');
    public $timestamps = true;
}