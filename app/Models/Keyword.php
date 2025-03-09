<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    
    protected $fillable = [
        'title_it', 'title_de', 'title_en'
    ];

    public function places()
    {
        return $this->hasMany(Place::class);
    }
}
