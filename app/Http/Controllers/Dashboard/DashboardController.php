<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function show()
    {
        return view('result');
    }

    public function app(Request $request)
    {
        $app = $request->app;
        return view('application', [
            'app' => $app,
            'name' => $app
        ]);
    }
}
