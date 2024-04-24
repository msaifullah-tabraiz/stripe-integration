<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderCompletedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // return $request->all();
        return view('orders.completed');
    }
}
