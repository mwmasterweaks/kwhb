<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\EmergencyContact;
use App\Models\MedicalHistory;
use App\Models\Address;
use App\Models\Attachment;
use App\Models\EmployeeStatusHistory;
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
            'location', 'emergency_contact', 'medical', 'profile_image', 'cover_image', 'manager'
        ])->paginate(5);
        return $tbl;
    }
    public function create()
    {
        //
    }


    public function fetch_employees(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        $search = $request->search;
        $tbl = Employee::with([
            'user.roles', 'division', 'employment', 'bank_info', 'address',
            'location', 'emergency_contact', 'medical', 'profile_image', 'cover_image', 'manager'
        ]);
        if ($search != null || $search != "") {
            $tbl->where(function ($query1) use ($search) {
                $query1->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        }
        return response()->json($tbl->paginate($itemsPerPage));
    }

    public function store(Request $request)
    {
        $dataResponse = new DataResponse();
        $user_cred = Auth::user();
        try {
            DB::beginTransaction();

            //$dateHired = Carbon::createFromFormat('d/m/Y', $request->date_hired)->format('Y-m-d');

            $requestData = $request->all();
            //$requestData['date_hired'] = $dateHired;

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

        $dataResponse = new DataResponse();
        $user_cred = Auth::user();
        try {
            $cmd  = Employee::findOrFail($id);
            $logFrom = $cmd->replicate();
            $input = $request->all();

            $cmd->fill($input)->save();
            if ($request->role_id) {
                DB::table('user_role')->where("user_id", $request->user_id)->delete();
                $user_role = new UserRole;
                $user_role->user_id = $request->user_id;
                $user_role->role_id = $request->role_id;
                $user_role->save();
            }


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
            $dataResponse->data = $this->getEmployees();
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
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
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);
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
            DB::beginTransaction();
            $oldData = "";
            $newData = "";
            if ($request->row == 'status') {
                $dateSelected = Carbon::now();
                if (isset($request->dateSelected))
                    $dateSelected = $request->dateSelected;
                EmployeeStatusHistory::insert([
                    'status' => $request->data,
                    'employee_id' => $request->id,
                    'user_id' => $user_cred->id,
                    'date_change' => $dateSelected,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            if ($request->row == 'password') {
                $this->reset_password($request->id, $request->data);
            } else {
                $tbl = Employee::where("id", $request->id);
                $oldData = $tbl->select($request->row)->get();
                $update = $tbl->update([$request->row => $request->data]);
                $newData = $tbl->select($request->row)->get();
            }

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
            DB::commit();
            return response()->json($dataResponse, 200);
        } catch (\Exception $ex) {
            DB::rollBack();
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
                    $query->whereIn('id', [2, 3]);
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
    public function fetch_line_managers(Request $request)
    {
        $user_cred = Auth::user();
        $dataResponse = new DataResponse();
        try {
            $division_id = $request->division_id;


            $data = Employee::where("division_id", $division_id)
                ->whereHas('user.roles', function ($query) {
                    $query->whereIn('id', [2, 3]);
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

    public function fetch_employee_by_name(Request $request)
    {
        $dataResponse = new DataResponse();
        $user_cred = Auth::user();
        try {

            $data = Employee::with([
                'user.roles', 'division', 'employment', 'bank_info', 'address',
                'location', 'emergency_contact', 'medical', 'profile_image', 'cover_image', 'manager'
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

    public function fetch_widget_data()
    {
        $dataResponse = new DataResponse();
        try {

            $totalEmployees = Employee::count();
            $activeEmployees = Employee::where('status', 'active')->count();
            $offswingEmployees = Employee::where('status', 'offswing')->count();
            $pendingEmployees = Employee::where('status', 'pending')->count();
            $extendedLeaveEmployees = Employee::where('status', 'extended leave')->count();

            $activeEmployeesP = round(($activeEmployees / $totalEmployees) * 100, 2);
            $offswingEmployeesP = round(($offswingEmployees / $totalEmployees) * 100, 2);
            $pendingEmployeesP = round(($pendingEmployees / $totalEmployees) * 100, 2);
            $extendedLeaveEmployeesP = round(($extendedLeaveEmployees / $totalEmployees) * 100, 2);


            $result = [
                [
                    'title' => 'Active Employees',
                    'value' => $activeEmployees,
                    'change' => $activeEmployeesP,
                    'desc' => 'Active Total Employees',
                    'icon' => 'tabler-user',
                    'iconColor' => '#62c379',
                ],
                [
                    'title' => 'Offswing',
                    'value' => $offswingEmployees,
                    'change' => $offswingEmployeesP,
                    'desc' => 'Fixed Period Contractors',
                    'icon' => 'tabler-user-plus',
                    'iconColor' => '#9c60e5',
                ],
                [
                    'title' => 'Pending Employees',
                    'value' => $pendingEmployees,
                    'change' => $pendingEmployeesP,
                    'desc' => 'Year to Date',
                    'icon' => 'tabler-user-check',
                    'iconColor' => '#e35306',
                ],
                [
                    'title' => 'Extended Leave',
                    'value' => $extendedLeaveEmployees,
                    'change' => $extendedLeaveEmployeesP,
                    'desc' => 'Year to Date',
                    'icon' => 'tabler-user-exclamation',
                    'iconColor' => '#e63c49',
                ],
            ];

            $dataResponse->data = $result;
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (Exception $ex) {
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 200);
            //throw $th;
        }
    }

    public function multipleFilter(Request $request)
    {
        $dataResponse = new DataResponse();
        try {

            $filter = (object) $request->filter;
            $data = (object) $request->data;
            $tbl = Employee::with([
                'user.roles', 'division', 'employment', 'bank_info', 'address',
                'location', 'emergency_contact', 'medical', 'profile_image', 'cover_image', 'manager'
            ]);
            if ($filter->role)
                if ($data->role_id != "all")
                    if ($data->role_id != "Select Role")
                        $tbl->whereHas('user.roles', function ($query) use ($data) {
                            $query->where('id', $data->role_id);
                        });
            if ($filter->division)
                if ($data->division_id != "all")
                    if ($data->division_id != "Select Division")
                        $tbl->where("division_id", $data->division_id);
            if ($filter->status)
                if ($data->status != "all")
                    if ($data->status != "Select Status")
                        $tbl->where("status", $data->status);



            $dataResponse->data = $tbl->get();
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (Exception $ex) {
            $dataResponse->ErrorResponse($ex);
            return response()->json($dataResponse, 500);
        }
    }

    public function reset_password($employee_id, $password)
    {
        try {
            User::where('employee_id', $employee_id)
                ->update(['password' => bcrypt($password)]);

            return 'updated';
        } catch (Exception $ex) {
            return 'error';
        }
    }

    public function update_attachment(Request $request)
    {
        $user_cred = Auth::user();
        $dataResponse = new DataResponse();
        try {
            if ($request->attachment_id > 0) {
                $attachment = Attachment::find($request->attachment_id);
            } else {
                $attachment = new Attachment();
            }

            $attachment->id = $request->attachment_id;
            $attachment->attachment = $request->image_url;
            $attachment->path = $request->image;
            $attachment->name = $request->image;
            $attachment->source = "employee";
            $attachment->source_id = $request->emp_id;
            $AttachmentController = "App\Http\Controllers\AttachmentController";
            app($AttachmentController)->saveAttachment($attachment);

            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'update_attachment',
                'log_type' => 'Message',
                'action' => 'Update Attachment ID ' . $request->attachment_id,
                'ip_address' => Helper::instance()->getIp(),
                'data' => "Updated attachment ID " . $request->attachment_id,
                'created_at' => Carbon::now(),
            ]);

            $dataResponse->data = $this->getEmployees();
            $dataResponse->message = 'Success';
            return response()->json($dataResponse, 200);
        } catch (\Exception $ex) {
            DB::table('logs')->insert([
                'user_id' => $user_cred->id,
                'function_name' => 'update_attachment',
                'log_type' => 'Error',
                'action' => 'Update Attachment',
                'ip_address' => Helper::instance()->getIp(),
                'data' => $ex->getMessage(),
                'created_at' => Carbon::now(),
            ]);
            $dataResponse->errorResponse($ex->getMessage());
            return response()->json($dataResponse, 500);
        }
    }
}
