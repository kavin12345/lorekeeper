<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PollsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Polls Controller
    |--------------------------------------------------------------------------
    |
    | Displays polls that members can vote on.
    |
    */

    public function getIndex()
    {
        return view('polls.index');
    }
}
