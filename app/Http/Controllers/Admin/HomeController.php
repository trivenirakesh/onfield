<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\V1\AdminDashboardService;

class HomeController extends Controller
{
    protected $dashboardService;

    public function __construct(AdminDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(){
        $dashboardDetails = $this->dashboardService->index()['data'];
        $title = 'Dashboard';
        return view('admin.dashboard', compact('title','dashboardDetails'));
    }

    public function Logout()
    {
        Auth::logout();
        return \Redirect::to("admin/login")
            ->with('message', array('type' => 'success', 'text' => 'You have successfully logged out'));
    }
}
