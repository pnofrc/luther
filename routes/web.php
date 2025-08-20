<?php

use Illuminate\Support\Facades\Route;
use App\Models\Place;
use App\Models\About;
use App\Models\Keyword;

Route::get('/', function (Place $places, Keyword $keywords) {
    $places = Place::with('keyword')->orderBy('title_it', 'asc')->get();

    // naming titles files
    foreach ($places as $place) {
        if (isset($place->file)) {
            $files_corrected = [];
            foreach ($place->file as $file) {
                $originalFile = $file;
                $file = str_replace("_", " ", $file);
                $file = str_replace(".pdf", "", $file);
                $file = str_replace("files/", "", $file);
                $files_corrected[] = ["title" => $file, "path" => $originalFile];
            }
            $place->file = $files_corrected;
            // $places->save();
        }
    }

    $keywords = Keyword::orderBy('title_de', 'asc')->get();

    // adding isbn pic
    $about = About::first();

    $isbn = '<img id="isbn" src="isbn.png" >';

    $about->about_it = $isbn.$about->about_it;
    $about->about_de = $isbn.$about->about_de;
    $about->about_en = $isbn.$about->about_en;

    return view('welcome', compact('places', 'keywords', 'about'));
});

