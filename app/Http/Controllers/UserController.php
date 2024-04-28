<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {

        // $data = $request->all();
        // $credentials = [
        //     'username' => $data['username'],
        //     'password'  => $data['password']
        // ];

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = request(['username', 'password']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user()->load(["employee"]);
            //$employee = Employee::where("id", $user->employee_id)->first();
            if ($user->employee->status == 'active') {
                $request->session()->regenerate();
                // $user['token'] =  $user->createToken('token')->plainTextToken;
                return response()->json(compact('user'));
            } else {
                return response()->json(['error' => 'Inactive user! Please contact the administrator.'], 403);
            }
        } else {
            return response()->json(['error' => 'Invalid Username or Password.'], 404);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard("web")->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (\Throwable $th) {
            return AppResponse::badRequest($th->getMessage());
        }
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $user = User::create([
            'username' => $data['username'],
            'password'  => bcrypt($data['password']),
            'status'  => 'active',
        ]);
        return response()->json(compact('user'));
    }
}
