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

    public function __construct()
    {
        $this->middleware('role:AT',['only'=>['showCreateForm','create']]);
    }
    public function index()
    {
        $committees = Committee::all();
        return view('committee.index', compact('committees'));
    }

    public function showCreateForm(Request $request)
    {
        return view('committee/create-committee');
    }

    public function create(Request $request)
    {
        //仅限AT访问
    }
}
