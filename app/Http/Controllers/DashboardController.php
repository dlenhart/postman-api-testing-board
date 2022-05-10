<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


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
