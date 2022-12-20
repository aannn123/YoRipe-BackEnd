<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|confirmed',
                    'role_id' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return $this->responseValidate(400, false, 'validation error', $validateUser->errors());
            }

            $checkRole = Role::find($request->role_id);
            if (!$checkRole) {
                return $this->responseJson(404, false, "Role not found");
            } else {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role_id' => $request->role_id
                ]);

                return $this->responseJson(201, true, "User created", $user);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
