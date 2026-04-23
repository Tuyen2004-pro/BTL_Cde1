<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('shipper.orders.index');
    }
}
