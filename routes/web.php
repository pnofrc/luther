<?php

use Illuminate\Support\Facades\Route;
use App\Models\Place;
use App\Models\Keyword;

Route::get('/', function (Place $places, Keyword $keywords) {
    $places = Place::with('keyword')->get();
    $keywords = Keyword::get();
    return view('welcome', compact('places', 'keywords'));
});
