<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client;

class DashboardController extends Controller
{
    //dashboard
    public function dashboard()
    {
        return view("admin.dashboard");
    }
}
