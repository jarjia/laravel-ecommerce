<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\JsonResponse;

class CountriesController extends Controller
{
    public function index(): JsonResponse
    {
        $countries = Countries::all();

        return response()->json($countries);
    }
}
