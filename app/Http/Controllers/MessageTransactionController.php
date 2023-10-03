<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\MessageProvider;
use App\Models\MessageTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class MessageTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MessageTransaction $messageTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MessageTransaction $messageTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MessageTransaction $messageTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MessageTransaction $messageTransaction)
    {
        //
    }

    public function getActiveProvider($type){
        $provider = MessageProvider::where(['type'=>$type, 'status'=>'active', 'company_id' => $this->sessionDetails('company_id')])->first();
        return $provider;
    }

    public function logEmailTransaction($data){
        $data['medium'] = 'email';
        $data['provider_id'] = $this->getActiveProvider('email')->id;
        $data['from'] = $this->getActiveProvider('email')->credentials['MAIL_FROM_ADDRESS'] ?? null;
        $data['name'] = $data['name'] ?? Auth::user()->first_name;
        $data['recipient'] = $data['email'] ?? Auth::user()->email;
        $data['message'] = $this->getMessageContent($data)['content'];
        $data['subject'] = $this->getMessageContent($data)['subject'];
        $data['schedule_date'] = Carbon::now();
        $data['company_id'] = $this->sessionDetails('company_id');
        $data = Arr::except($data, ['name']);

        $mes = MessageTransaction::create($data);
        return $mes;
    }

    public function logSmsTransaction($data)
    {
        dd($data);
    }

    public function getMessageContent($data){
        $all = [];
        $content = "";
        $subject = "";
        if ($data['type'] == 'password-reset') {
            if(isset($data['medium']) && $data['medium'] == 'sms'){
                $content .= 'Hello, your password reset link is: '. url('/').'/reset-password?token='.$data['token'];
            }else{
                $subject = 'Password Reset Link';
                $content .= '<strong>Dear ' . $data['name'] . '</strong><br><br>
                <div>
                <p style="text-align:justify !important">You asked for a password RESET mail on '.$this->sessionDetails('company_name'). '. Please click the button below to reset your password. Thank you <br><br><a href="' . url('/') . '/reset-password?token=' . $data['token'] . '"><button style="background: green;text-decoration: none;padding: 10px;color: white;">RESET PASSWORD</button></a></p>
                </div>';
            }
        } 
        return [
            'subject' => $subject,
            'content' => $content,
        ];
    }

    public function sendEmail($data){
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

    }
}
