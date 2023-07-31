<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){
        $title = 'Dashboard';
        return view('admin.dashboard', compact('title'));
    }

    public function Logout()
    {
        Auth::logout();
        return \Redirect::to("login")
            ->with('message', array('type' => 'success', 'text' => 'You have successfully logged out'));
    }
}
