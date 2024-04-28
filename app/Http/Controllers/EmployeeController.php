<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\EmergencyContact;
use App\Models\MedicalHistory;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Helpers\DataResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class EmployeeController extends Controller
{

    private $user;
    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function index()
    {
        return response()->json($this->getEmployees());
    }

    public function getEmployees()
    {
        $tbl = Employee::with([
            'user.roles', 'division', 'employment', 'bank_info', 'address',
            'location', 'emergency_contact', 'medical', 'profile_image'
        ])->get();
        return $tbl;
    }
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $dataResponse = new DataResponse();
        $user_cred = Auth::user();
        try {
            DB::beginTransaction();

            $dateHired = Carbon::createFromFormat('d/m/Y', $request->date_hired)->format('Y-m-d');

            $requestData = $request->all();
            $requestData['date_hired'] = $dateHired;

            $data = Employee::create($requestData);

            //Store User

            $user = new User;
            $user->name = $request->first_name . " " . $request->last_name;
            $user->username =  substr($request->first_name, 0, 1) . $request->last_name;
            $user->password = bcrypt('password');
            $user->employee_id = $data->id;
            $user->save();
            //Store Role
            $user_role = new UserRole;
            $user_role->user_id = $user->id;
            $user_role->role_id = $request->role_id;
            $user_role->save();

            //store profile pic
            $attachment = new stdClass;
            $attachment->attachment = $request->profile;
            $attachment->path = "profile_image";
            $attachment->name = "profile_image";
            $attachment->source = "employee";
            $attachment->source_id = $data->id;
            $AttachmentController = "App\Http\Controllers\AttachmentController";
            app($AttachmentController)->saveAttachment($attachment);

            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'store',
                'log_type' => 'Message',
                'action' => 'Create Employee',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $data,
                'created_at' => Carbon::now(),
            ]);
            $dataResponse->data = $this->getEmployees();
            $dataResponse->message = 'Success';
            DB::commit();
            return response()->json($dataResponse, 200);
        } catch (Exception $ex) {
            DB::rollBack();
            DB::table('logs')->insert([
                'user_id' =>  $user_cred->id,
                'function_name' => 'store',
                'log_type' => 'Error',
                'action' => 'Create Employee',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
                'created_at' => Carbon::now(),
            ]);
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);

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
        $user_cred = Auth::user();
        try {
            $cmd  = Employee::findOrFail($id);
            $logFrom = $cmd->replicate();
            $input = $request->all();

            $cmd->fill($input)->save();
            $logTo = $cmd;
            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'update',
                'log_type' => 'Message',
                'action' => 'Update Employee',
                'ip_address' => Helper::instance()->getIp(),
                'data' => "From: " . $logFrom . "\r\nTo: " . $logTo,
                'created_at' => Carbon::now(),
            ]);
            return $this->getEmployees();
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'update',
                'log_type' => 'Error',
                'action' => 'Update Employee',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
                'created_at' => Carbon::now(),
            ]);
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user_cred = Auth::user();
        try {
            $tbl = Employee::findOrFail($id);
            Employee::destroy($id);


            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'destroy',
                'log_type' => 'Message',
                'action' => 'Delete Employee',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $tbl,
                'created_at' => Carbon::now(),
            ]);
            return $this->index();
        } catch (Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'destroy',
                'log_type' => 'Error',
                'action' => 'Delete Employee',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
                'created_at' => Carbon::now(),
            ]);
            return response()->json(['error' => 'There was a problem deleting this Employee.'], 500);
        }
    }

    public function update_row(Request $request)
    {
        $user_cred = Auth::user();
        $dataResponse = new DataResponse();
        try {
            $tbl = Employee::where("id", $request->id);
            $oldData = $tbl->select($request->row)->get();
            $update = $tbl->update([$request->row => $request->data]);
            $newData = $tbl->select($request->row)->get();

            DB::table('logs')->insert([
                //'user_id' => 1,
                'user_id' => $user_cred->id,
                'function_name' => 'update_row',
                'log_type' => 'Message',
                'action' => 'Update Employee ' . $request->row,
                'ip_address' => Helper::instance()->getIp(),
                'data' => "update Employee id " . $request->id . "\nRow: " . $request->row .
                    "\nFrom: " . $oldData . "\nTo: " . $newData,
                'created_at' => Carbon::now(),
            ]);

            $dataResponse->data = $this->getEmployees();
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (\Exception $ex) {
            DB::table('logs')->insert([
                //'user_id' => 1,
                'user_id' => $user_cred->id,
                'function_name' => 'update_row',
                'log_type' => 'Error',
                'action' => 'Update Employee ' . $request->row,
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
                'created_at' => Carbon::now(),
            ]);
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);
        }
    }

    public function update_emergency_contact(Request $request)
    {
        $user_cred = Auth::user();
        $dataResponse = new DataResponse();
        try {
            $oldData = EmergencyContact::where("employee_id", $request->employee_id)->first();

            $newData = EmergencyContact::updateOrCreate(
                ["employee_id" => $request->employee_id],
                $request->all()
            );

            DB::table('logs')->insert([
                //'user_id' => 1,
                'user_id' => $user_cred->id,
                'function_name' => 'update_emergency_contact',
                'log_type' => 'Message',
                'action' => 'Update Employee Emergency Contact',
                'ip_address' => Helper::instance()->getIp(),
                'data' => "From: " . $oldData . "\r\nTo: " . $newData,
                'created_at' => Carbon::now(),
            ]);

            $dataResponse->data = $this->getEmployees();
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (\Exception $ex) {
            DB::table('logs')->insert([
                //'user_id' => 1,
                'user_id' => $user_cred->id,
                'function_name' => 'update_emergency_contact',
                'log_type' => 'Error',
                'action' => 'Update Employee Emergency Contact',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
                'created_at' => Carbon::now(),
            ]);
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);
        }
    }
    public function update_medical(Request $request)
    {
        $user_cred = Auth::user();
        $dataResponse = new DataResponse();
        try {
            $oldData = MedicalHistory::where("employee_id", $request->employee_id)->first();

            $newData = MedicalHistory::updateOrCreate(
                ["employee_id" => $request->employee_id],
                $request->all()
            );

            DB::table('logs')->insert([
                //'user_id' => 1,
                'user_id' => $user_cred->id,
                'function_name' => 'update_medical',
                'log_type' => 'Message',
                'action' => 'Update Employee Medical History ',
                'ip_address' => Helper::instance()->getIp(),
                'data' => "From: " . $oldData . "\r\nTo: " . $newData,
                'created_at' => Carbon::now(),
            ]);

            $dataResponse->data = $this->getEmployees();
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (\Exception $ex) {
            DB::table('logs')->insert([
                //'user_id' => 1,
                'user_id' => $user_cred->id,
                'function_name' => 'update_medical',
                'log_type' => 'Error',
                'action' => 'Update Employee Medical History ',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
                'created_at' => Carbon::now(),
            ]);
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);
        }
    }
    public function update_address(Request $request)
    {
        $user_cred = Auth::user();
        $dataResponse = new DataResponse();
        try {
            $oldData = Address::where("employee_id", $request->employee_id)->first();

            $newData = Address::updateOrCreate(
                ["employee_id" => $request->employee_id],
                $request->all()
            );

            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'update_address',
                'log_type' => 'Message',
                'action' => 'Update Employee Address ',
                'ip_address' => Helper::instance()->getIp(),
                'data' => "From: " . $oldData . "\r\nTo: " . $newData,
                'created_at' => Carbon::now(),
            ]);

            $dataResponse->data = $this->getEmployees();
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (\Exception $ex) {
            //asd
            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'update_address',
                'log_type' => 'Error',
                'action' => 'Update Employee Address',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
                'created_at' => Carbon::now(),
            ]);
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);
        }
    }
    public function fetch_approvers(Request $request)
    {
        $user_cred = Auth::user();
        $dataResponse = new DataResponse();
        try {
            $employee_id = $request->employee_id;
            $division_id = $request->division_id;


            $data = Employee::where("division_id", $division_id)
                ->whereHas('user.roles', function ($query) {
                    $query->where('id', 2); //2 = line manager
                })
                ->where('id', '!=', $employee_id)
                ->get();

            $dataResponse->data = $data;
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (\Exception $ex) {
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);
        }
    }

    public function fetch_employee_by_name(Request $request)
    {
        $dataResponse = new DataResponse();
        $user_cred = Auth::user();
        try {

            $data = Employee::with([
                'user.roles', 'division', 'employment', 'bank_info', 'address',
                'location', 'emergency_contact', 'medical', 'profile_image'
            ])
                ->where(function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->name . '%')
                        ->orWhere('last_name', 'like', '%' . $request->name . '%');
                })
                ->get();

            $dataResponse->data = $data;
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (\Exception $ex) {
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);
        }
    }
}
