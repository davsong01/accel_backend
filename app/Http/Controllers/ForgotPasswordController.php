<?php

namespace App\Http\Controllers;

use App\Models\ResetCodePassword;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{

    /**
     * Handle an incoming password forget request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->all(),
                'data' => null,
            ], 412);
        }

        $message = 'If this is a registered email address, a password reset link has been sent to the email';

        if (!($user = User::firstWhere('email', $request->email))) {
            return response()->json(['message' => $message, 'data' => null], 200);
        }


        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();
        // Generate random code
        $rand = mt_rand(100000000000, 999999999999);
        $data['token'] = base64_encode($rand);
        $data['email'] = $request->email;
        $data['name'] = $user->first_name;

        // Create a new code
        $codeData = ResetCodePassword::create($data);
        $data['type'] = 'forget-password';
        $data['token'] = $rand;

        // Log email only

        LogService::admin($user->id, null, "Forgot password for $request->email", 'UPDATE');
        return response()->json(['message' => $message, 'data' => null], 200);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['token'] = base64_encode($request->input('token', ''));

        $validator = Validator::make($data, [
            'token' => ['required', 'exists:password_reset_tokens,token'],
            'password' => ['required', 'confirmed', 'min:6', Rules\Password::defaults()],
        ], [
            'token.exists' => 'Invalid Token',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }


        $passwordReset = ResetCodePassword::firstWhere('token', $data['token']);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['error' => 'Token has expired', 'data' => null], 422);
        }

        $user = User::firstWhere('email', $passwordReset->email);

        if ($user) {
            $user->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])->save();
        }

        LogService::admin($user->id, null, "$user->email reset their password ($user->id)", 'UPDATE');
        $passwordReset->delete();

        return response(['message' => 'Password successfully reset', 'data' => null], 200);
    }
}
