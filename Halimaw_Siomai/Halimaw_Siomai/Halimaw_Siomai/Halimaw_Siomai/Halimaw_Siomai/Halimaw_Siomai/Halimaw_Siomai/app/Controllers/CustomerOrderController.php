<?php

namespace App\Controllers;

class CustomerOrderController extends BaseController
{
    public function index()
    {
        return view('customer/order');
    }
}
