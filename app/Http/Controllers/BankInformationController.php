<?php

namespace App\Http\Controllers;

use App\Models\BankInformation;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Helpers\DataResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BankInformationController extends Controller
{

    private $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index()
    {
        $tbl = BankInformation::all();
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
            $temp = $request->primary;
            $emp_id = $request->employee_id;
            if ($temp)
                BankInformation::where('employee_id', $emp_id)->update(["primary" => false]);
            $data = BankInformation::create($request->all());

            DB::table('logs')->insert([
                //'user_id' => $this->user->id,
                'user_id' => 1,
                'function_name' => 'store',
                'log_type' => 'Message',
                'action' => 'Create BankInformation',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $data,
            ]);
            //
            $dataResponse->data = $this->show($emp_id);
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => 1,
                //'user_id' => $this->user->id,
                'function_name' => 'store',
                'log_type' => 'Error',
                'action' => 'Create BankInformation',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 500);
            //return response()->json(['error' => $ex->getMessage()], 500);
        }
    }


    public function show($emp_id)
    {
        $tbl = BankInformation::where('employee_id', $emp_id)->orderBy('primary', 'Desc')->get();
        return $tbl;
    }

    public function edit(Employee $Employee)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $dataResponse = new DataResponse();

        DB::beginTransaction();
        try {

            $temp = $request->primary;
            $emp_id = $request->employee_id;
            if ($temp)
                BankInformation::where('employee_id', $emp_id)->update(["primary" => false]);

            $cmd  = BankInformation::findOrFail($id);
            $logFrom = $cmd->replicate();
            $input = $request->all();

            $cmd->fill($input)->save();
            $logTo = $cmd;
            DB::table('logs')->insert([
                'user_id' => 1,
                //'user_id' => $this->user->id,
                'function_name' => 'update',
                'log_type' => 'Message',
                'action' => 'Update BankInformation',
                'ip_address' => Helper::instance()->getIp(),
                'data' => "From: " . $logFrom . "\r\nTo: " . $logTo,
            ]);

            DB::commit();
            $dataResponse->data = $this->show($emp_id);;
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
            //return $this->index();
        } catch (Exception $ex) {
            DB::rollBack();
            DB::table('logs')->insert([
                'user_id' => 1,
                //'user_id' => $this->user->id,
                'function_name' => 'update',
                'log_type' => 'Error',
                'action' => 'Update BankInformation',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 500);
            //return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tbl = BankInformation::findOrFail($id);
            BankInformation::destroy($id);


            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'destroy',
                'log_type' => 'Message',
                'action' => 'Delete BankInformation',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $tbl,
            ]);
            return $this->index();
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'destroy',
                'log_type' => 'Error',
                'action' => 'Delete BankInformation',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            return response()->json(['error' => 'There was a problem deleting this Employee.'], 500);
        }
    }
}
