<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

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

    public function photo(Request $request)
    { 
        $id = Auth::user()->employee_number;

        $this->client = new Client([
            'exceptions' => FALSE,
            'base_uri' => env('EMPLOYEES_API_URL')
        ]);

        $response = $this->client->get("employees/number/{$id}");
        $results = $response->getBody()->getContents();
        $employee_id = json_decode($results, true);
        $photo = $this->client->get("employees/{$employee_id['data']['id']}/photo");
        $photo = $photo->getBody()->getContents();

        return response($photo)->header('Content-Type', 'image/jpeg' );
    }

    public function healthz()
    {
        return response('OK', 200);
    }
}
