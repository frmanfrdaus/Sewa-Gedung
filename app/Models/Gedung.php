<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    // Post -> table_name = gedung
    // custome table name :
    // protected $table='table_name'
    protected $table = 'gedung';

    // define coloumn name
    
    protected $fillable = array('namagedung', 'alamat', 'harga', 'user_id');
    // untuk melakukan update field create_at dan update_at secara otomatis
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}