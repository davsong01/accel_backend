<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Exists;

class AuthController extends Controller
{
    // register a new user method
    public function register(RegisterRequest $request)
    {

        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie('token', $token, 60 * 24); // 1 day

        return response()->json([
            'user' => new UserResource($user),
        ])->withCookie($cookie);
    }

    // login a user method
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'sometimes',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                // 'error' => $validator->messages()->first(),
                'error' => 'Invalid Credentials',
                'data' => null,
            ], 401);
        }

        

        if (\Request::route()->getPrefix() == 'api/customer') {
            if (!$request['email']) {
                return response()->json([
                    'error' => 'Email or Customer ID is required!'
                ], 412);
            }
            $user = User::where('type','customer');
            $user = $user->where('email', $request['email'])->orWhere('customerId', $request['email'])->first();

            // Check if customer has made payment for response form
            $payments = $user->customer->payments;

            if($payments->count() < 1){
                return response()->json([
                    'error' => 'You do have not made any form payment!'
                ], 412);
            }
        }else{
            if (!$request['email']) {
                return response()->json([
                    'error' => 'Email or staff ID is required!'
                ], 412);
            }

            $user = User::where('type', 'staff');
            $user = $user->where('email', $request['email'])->orWhere('staff_id', $request['email'])->first();
        }
        
        if (!$user || !Hash::check($request['password'], $user->password)) {
            return response()->json([
                'error' => 'Invalid Credentials!'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $company = Company::where('id', $user->company_id)->first();

        if (!$company) {
            return response()->json([
                'error' => 'Invalid Company details!'
            ], 403);
        }

        // $user->token = $token;
        $cookie = cookie('token', $token, 60 * 24); // 1 day
        $datax = [
            'token' => $token,
            'userInfo' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'avatar' => $user->avatar,
                'job_role' => $user->staff->job_role->name ?? '',
            ],
        ];

        if($user->type == 'staff'){
            $datax['userInfo']['staff_id'] = $user->staff_id;
        }

        if ($user->type == 'customer') {
            $datax['userInfo']['customer_id'] = $user->customerId;
        }
        
        return response()->json(['data' => $datax, 'message' => Session::all()], 200);
    }

    // logout a user method
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $cookie = cookie()->forget('token');
        Session::flush();
        return response()->json([
            'message' => 'Logged out successfully!'
        ])->withCookie($cookie);
    }

    // get the authenticated user method
    public function user(Request $request)
    {
        return new UserResource($request->user());
    }

}
