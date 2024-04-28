<?php

namespace App\Http\Controllers;

use App\Models\Employment;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Helpers\DataResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmploymentController extends Controller
{

    private $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index()
    {
        $tbl = Employment::all();
        return response()->json($tbl);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $dataResponse = new DataResponse();
        try {
            $data = Employment::create($request->all());

            DB::table('logs')->insert([
                //'user_id' => $this->user->id,
                'user_id' => 1,
                'function_name' => 'store',
                'log_type' => 'Message',
                'action' => 'Create employment',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $data,
            ]);
            //
            $dataResponse->data = $this->index();
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => 1,
                //'user_id' => $this->user->id,
                'function_name' => 'store',
                'log_type' => 'Error',
                'action' => 'Create employment',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 500);
            //return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function show(Employee $Employee)
    {
        //
    }

    public function edit(Employee $Employee)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            $cmd  = Employment::findOrFail($id);
            $logFrom = $cmd->replicate();
            $input = $request->all();

            $cmd->fill($input)->save();
            $logTo = $cmd;
            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'update',
                'log_type' => 'Message',
                'action' => 'Update employment',
                'ip_address' => Helper::instance()->getIp(),
                'data' => "From: " . $logFrom . "\r\nTo: " . $logTo,
            ]);
            return $this->index();
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'update',
                'log_type' => 'Error',
                'action' => 'Update employment',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tbl = Employment::findOrFail($id);
            Employment::destroy($id);


            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'destroy',
                'log_type' => 'Message',
                'action' => 'Delete employment',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $tbl,
            ]);
            return $this->index();
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'destroy',
                'log_type' => 'Error',
                'action' => 'Delete employment',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            return response()->json(['error' => 'There was a problem deleting this Employee.'], 500);
        }
    }
}
