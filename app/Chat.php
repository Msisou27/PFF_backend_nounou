<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'message', 'author', 'user_recep', 'id_product'
    ];

    public function chat()
    {
    return $this->belongsTo('App\Chat');
    }
    
}
