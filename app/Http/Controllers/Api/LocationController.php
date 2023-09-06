<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class LocationController extends Controller
{
    public function getLocationUser(Request $request){
        //        $ip = '172.16.31.10';
        $userIP = '104.244.79.255';
        $location = Location::get($userIP);
        return response([
            'message' => 'your user current location ',
            'location' => $location
        ],200);
    }
}
