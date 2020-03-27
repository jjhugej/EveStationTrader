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
    public function resolveMultipleLogisticsGroupIDToName($inventoryItems){
        
        foreach($inventoryItems as $inventoryItem){
            if($inventoryItem->logistics_group_id){
                if(isset(Logistics::where('id', $inventoryItem->logistics_group_id)->first()->name)){
                    $inventoryItem->logistics_group_name = Logistics::where('id', $inventoryItem->logistics_group_id)->first()->name;
                }else{
                    $inventoryItem->logistics_group_name = null;
                }
            }
        }
        return $inventoryItems;
    }

    public function resolveSingleLogisticsGroupIDToName($inventoryItem){
        if($inventoryItem->logistics_group_id){
            if(isset(Logistics::where('id', $inventoryItem->logistics_group_id)->first()->name)){
                $inventoryItem->logistics_group_name = Logistics::where('id', $inventoryItem->logistics_group_id)->first()->name;
            }else{
                $inventoryItem->logistics_group_name = null;
            }
        }
        return $inventoryItem;
    }
}   