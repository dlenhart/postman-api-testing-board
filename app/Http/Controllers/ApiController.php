<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Application;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function results()
    {        
        $results = Result::SelectResults()
            ->with('application')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        return response()->json($results, 200);
    }

    public function result(Request $request)
    {
        $result = Result::findOrFail($request->id)
            ->with('application')
            ->first();
        $result->makeHidden(['raw_data']);
        
        return responder()->success($result)->respond();
    }

    public function delete(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make( $data, [
            'id'                   => 'required|numeric',
        ] );

        if ( $validator->fails() ) {
            return responder()
                ->error('validation', 'Validation errors')
                ->respond();
        }

        $record = Result::findOrFail($request->id);        
        $record->delete();
            
        return responder()->success()->respond();
    }

    public function listApps()
    {
        $apps = Application::all();
        return responder()->success($apps)->respond();
    }

    public function application(Request $request)
    {
        $app_id = Application::where('name', $request->app)->first();

        if(!$app_id){
            return responder()
                ->error('not_found', 'Could not find application.')
                ->respond();
        }

        $app = Result::SelectResults()
            ->where('application_id', $app_id->id)
            ->with('application')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        if($app) {
            return response()->json($app, 200);
        }
    }
}
