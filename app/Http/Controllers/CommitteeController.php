<?php

namespace App\Http\Controllers;

use App\Committee;
use Illuminate\Http\Request;

use App\Http\Requests;

class CommitteeController extends Controller
{
    /**
     *
     */
    public function index(){
        $committees = Committee::all();
        return view('committee.index',compact('committees'));
   }
}
