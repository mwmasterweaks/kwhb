<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DivisionController extends Controller
{

    private $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index()
    {
        $tbl = Division::orderBy('name', 'asc')->get();
        return response()->json($tbl);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        try {
            $data = Division::create($request->all());

            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'store',
                'log_type' => 'Message',
                'action' => 'Create division',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $data,
            ]);
            return $this->index();
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'store',
                'log_type' => 'Error',
                'action' => 'Create division',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            return response()->json(['error' => $ex->getMessage()], 500);
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
            $cmd  = Division::findOrFail($id);
            $logFrom = $cmd->replicate();
            $input = $request->all();

            $cmd->fill($input)->save();
            $logTo = $cmd;
            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'update',
                'log_type' => 'Message',
                'action' => 'Update division',
                'ip_address' => Helper::instance()->getIp(),
                'data' => "From: " . $logFrom . "\r\nTo: " . $logTo,
            ]);
            return $this->index();
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'update',
                'log_type' => 'Error',
                'action' => 'Update division',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tbl = Division::findOrFail($id);
            Division::destroy($id);


            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'destroy',
                'log_type' => 'Message',
                'action' => 'Delete division',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $tbl,
            ]);
            return $this->index();
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $this->user->id,
                'function_name' => 'destroy',
                'log_type' => 'Error',
                'action' => 'Delete division',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
            ]);
            return response()->json(['error' => 'There was a problem deleting this Employee.'], 500);
        }
    }
}
