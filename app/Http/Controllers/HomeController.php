<?php

namespace App\Http\Controllers;

use App\Models\MessageGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        $groups = MessageGroup::get();
        $this->data['users'] = $users;
        $this->data['groups'] = $groups;

        return view('home', $this->data);
    }
}
