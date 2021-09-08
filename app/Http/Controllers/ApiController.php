<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    const API_123_milhas = 'http://prova.123milhas.net/api/flights';

    public function flightsGrp()
    {
        $flights = $this->consultApi();
        $flightsGrpInOut = $this->separateFlights($flights);
        $data = $this->dataFlights($flightsGrpInOut->grpInbound, $flightsGrpInOut->grpOutbound, $flights);

        return $data;
    }

    //Consulta a api externa
    public function consultApi(){
        $consultApi = Http::get(self::API_123_milhas);

        return $consultApi->json();
    }

    //separa os voos por tarifa e tambemm por ida e volta
    private function separateFlights($flights){

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

        return (object)[
            'grpInbound' => $grpInbound,
            'grpOutbound' => $grpOutbound
        ];

    }
    //Monta os grupos
    private function dataFlights($grpInbound, $grpOutbound, $flights){
        $groups = [];
        $group = [];
        $id = 0;
        
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
        
        try {
            return response()->json([
                'message' => '',
                'data' => $modeloResult,
                'result' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'data' => '',
                'result' => false,
            ], 401);
        }
    }

}
