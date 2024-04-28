<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inani\Larapoll\Poll;

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
        return view('polls.index', ['polls' => Poll::orderBy('id', 'DESC')->paginate(10)]);
    }
}
