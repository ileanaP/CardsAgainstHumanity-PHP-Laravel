<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Cardset;


class CardsetController extends ApiController
{

    public function index()
    {
        $rounds = Cardset::all(['id', 'name']);

        return $rounds;
    }
}
