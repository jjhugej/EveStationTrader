<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Logistics;
use App\Character;
use App\User;
use App\MarketOrders;
use App\EveItem;
use App\StructureName;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class InventoryBaseController extends EveBaseController
{
    public function resolveLogisticsGroupIDToName($logisticObjects){

        //current issue is detecting whether or not $logisticObjects is a collection or just a single instance of an object
        dd(is_array($logisticObjects));
        if(is_array($logisticObjects)){
            foreach($logisticObjects as $logisticObject){
                if($logisticObject->logistics_group_id !== null){
                    $logisticObject->logistics_group_name = Logistics::where('id', $logisticObject->logistics_group_id)->first()->name; 
                }else{
                    $logisticObject->logistics_group_name = 'n/a';
                }
                return $logisticObjects;
            }
        }else{
             if($logisticObjects->logistics_group_id !== null){
                    $logisticObjects->logistics_group_name = Logistics::where('id', $logisticObjects->logistics_group_id)->first()->name; 
                }else{
                    $logisticObjects->logistics_group_name = 'n/a';
                }
                return $logisticObjects;
        }
        
    }   
}
