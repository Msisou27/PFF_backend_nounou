<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description_short', 'description_long', 'category', 'used', 'location', 'town', 'price', 'image','author', 'slug','draft', 'published','status', 'id_users',
    ];

    public function user()
    {
         return $this->belongsTo('App\Product');    
    }

    public function chat()
    {
        return $this->hasMany('App\Chat');
    }

    
}
