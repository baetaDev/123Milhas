<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    const API = 'http://prova.123milhas.net/api/flights';

    public function consultApi(){
        $consultApi = Http::get(self::API);

        return $consultApi->json();
    }

}
