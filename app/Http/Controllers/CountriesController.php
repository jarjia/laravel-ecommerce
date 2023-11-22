<?php

namespace App\Http\Controllers;

use App\Models\Countries;

class CountriesController extends Controller
{
    public function index()
    {
        $countries = Countries::select('country', 'id')->get();

        return response()->json($countries);
    }
}
