<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword_id',
        'title_it', 'title_de', 'title_en',
        'content_it', 'content_de', 'content_en',
        'latitude', 'longitude'
    ];

    public function keyword()
    {
        return $this->belongsTo(Keyword::class);
    }
}
