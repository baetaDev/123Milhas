<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    const API_123_milhas = 'http://prova.123milhas.net/api/flights';

    public function consultApi(){
        $consultApi = Http::get(self::API_123_milhas);

        return $consultApi->json();
    }

    public function flightsGrp(){
        $flights = $this->consultApi();

        //adiciona todos os voos de ida (outbound)
        $outbound = array_filter($flights, function ($flights) {
            return $flights['outbound'];
        });

        //adiciona todos os voos de volta (inbound)
        $inbound = array_filter($flights, function ($flights) {
            return $flights['inbound'];
        });

        //separa por grupos de tarifas (inbound)
        $grpInbound = [];
        foreach($inbound as $key => $value)
        {
            $grpInbound[$value['fare']][$value['price']][] = $value;
        }

        //separa por grupos de tarifas (outbound)
        $grpOutbound = [];
        foreach($outbound as $key => $value)
        {
            $grpOutbound[$value['fare']][$value['price']][] = $value;
        }
        //dd($grpInbound, $grpOutbound);
        
        $groups = [];
        $group = [];
        $id = 0;
        //monta os dados
        foreach($grpOutbound as $key => $value){
            $grpInboundFare = $grpInbound[$key];
            foreach($value as $keyPriceOut => $grpOut){
                foreach($grpInboundFare as $keyPriceIN => $grpIN){
                    $group['id'] = $id++;
                    $group['price'] = $keyPriceIN + $keyPriceOut;
                    $group['outbounds'] = $grpOut;
                    $group['inbounds'] = $grpIN;
                    $groups[] = $group;
                }
            }
        }

        //ordena pelo preço
        usort($groups, function($a, $b) {
                return $a['price'] <=> $b['price'];
        });

        $modeloResult = [];

        $modeloResult['flights'] = $flights;  // retorne aqui os voos consultados na api em prova.123milhas.net
        $modeloResult['groups'] = $groups;  
        $modeloResult['totalGroups'] = count($modeloResult['groups']); // quantidade total de grupos
        $modeloResult['totalFlights'] = count($modeloResult['flights']); // quantidade total de voos únicos
        $modeloResult['cheapestPrice'] = $modeloResult['groups']['0']['price']; // preço do grupo mais barato
        $modeloResult['cheapestGroup'] = $modeloResult['groups']['0']['id']; // id único do grupo mais barato
        
        //retorna um json
        return json_encode($modeloResult);
    }

}
