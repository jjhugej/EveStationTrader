<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestItController extends Controller
{
    public function index(){
        dd('test controller index');
    }
}
