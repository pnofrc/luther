<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Keyword;
use Illuminate\Http\Request;

class LexiconController extends Controller
{
    public function indexPlaces()
    {
        $places = Place::all();
        return view('places.index', compact('places'));
    }

    // Mostra la lista di Keywords
    public function indexKeywords()
    {
        $keywords = Keyword::all();
        return view('keywords.index', compact('keywords'));
    }

}
