<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    //card
    public function card()
    {
        return view("admin.pages.card.index");
    }
}
