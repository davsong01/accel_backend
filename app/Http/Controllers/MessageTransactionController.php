<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanySetting;
use Illuminate\Support\Carbon;
use App\Models\MessageProvider;
use App\Models\MessageTransaction;
use App\Mail\MessageTransactionMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
        $company_setting = CompanySetting::where('company_id', $this->sessionDetails('company_id'))->first();

        $button_style = "background: ".$company_setting->primary_color. ";text-decoration: none; padding: 10px;color: white;border: none;color: white;border-radius: 5px;";
        $all = [];
        $content = "";
        $subject = "";
        if ($data['type'] == 'password-reset') {
            if(isset($data['medium']) && $data['medium'] == 'sms'){
                $content .= 'Hello, your password reset link is: '. url('/').'/reset-password?token='.$data['token'];
            }else{
                $subject = 'Password Reset Link';
                $content .= '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">You asked for a password RESET mail on '.$this->sessionDetails('company_name'). '. Please click the button below to reset your password. Thank you <br><br><a href="' . url('/') . '/reset-password?token=' . $data['token'] . '"><button style="background: green;text-decoration: none;padding: 10px;color: white;">RESET PASSWORD</button></a></p>
                </div>';
            }
        }

        if ($data['type'] == 'staff.store') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
               
            } else {
                $subject = 'Welcome to '.$this->sessionDetails('company_name');
                $content .= '<strong>Dear ' . $data['name'] . ',</strong><br><br>
                <div>
                <p style="text-align:justify !important">Your new staff details have been created. Please find details below: <br><br>
                First Name: '.$data['user']['first_name']. '<br>
                Last Name: ' . $data['user']['last_name'] . '<br>
                Email: ' . $data['user']['email'] . '<br>
                Staff Id: ' . $data['user']['staff_id'] . '<br>
                Password: ' . '123456789' . '<br><br> Please click the login button below to login to your personalized dashboard using your email/staff Id and password above. Thank you <br><br><a href="' . url('/') . '"><button style="'.$button_style.'">LOGIN</button></a></p>
                </div>';
            }
        }

        if ($data['type'] == 'formrequest.approve') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $subject = 'Subscriber Application Approved - Welcome to ' . $this->sessionDetails('company_name');
                $content .= '<strong>Dear ' . $data['name'] . ',</strong><br><br>
                <div>
                <p style="text-align:justify !important">We are pleased to inform you that your recent application to ' . $this->sessionDetails('company_name'). ' has been carefully reviewed and approved.<br><br>We are excited to welcome you as a customer and look forward to a fruitful relationship.<br><br>
                Please visit our office to pick up your Offer Letter, and if you have any questions, do not hesitate to contact our support team/contact person.
                <br><br> Once again, congratulations, and we anticipate a fruitful association. <br><br>Best regards,<br>'. $this->sessionDetails('company_name').'</p>
                </div>';
            }
        }

        if ($data['type'] == 'formrequest.decline') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $subject = 'Subscriber Application Declined - Missing/Incorrect Information';
                $content .= '<strong>Dear ' . $data['name'] . ',</strong><br><br>
                <div>
                <p style="text-align:justify !important">Thank you for submitting your application to ' . $this->sessionDetails('company_name') . '. Unfortunately, we are unable to proceed with the processing of your application as certain crucial information is missing/incorrect.<br><br>Please review the provided feedback below and make the necessary corrections before resubmitting your application.<br><br>
                We appreciate your understanding and encourage you to reach out if you have any questions or need further clarification.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }
       
        return [
            'subject' => $subject,
            'content' => $content,
        ];
    }

    public function processMessagesTransaction($pick = 100){
        $messages = MessageTransaction::where(['status'=>'pending'])->get()->take($pick);
        if(!$messages){
            return response()->json('No pending messages');

        }
        foreach($messages as $message){
            $provider = MessageProvider::where('id', $message->provider_id)->first();
            if($message->medium == 'sms'){

            }
         
            if ($message->medium == 'email') {
                $providerX = [
                    'mail.driver' => $provider->credentials['MAIL_DRIVER'],
                    'mail.host' => $provider->credentials['MAIL_HOST'],
                    'mail.port' => $provider->credentials['MAIL_PORT'],
                    'mail.encryption' => $provider->credentials['MAIL_ENCRYPTION'],
                    'mail.username' => $provider->credentials['MAIL_USERNAME'],
                    'mail.password' => $provider->credentials['MAIL_PASSWORD'],
                    'mail.replyToName' => $provider->credentials['MAIL_REPLY_TO_NAME'] ?? $this->sessionDetails('company_name'),
                    'mail.replyToAddress' => $provider->credentials['MAIL_REPLY_TO_ADDRESS'] ?? 'no-reply@mail.com',
                    'mail.from' => [
                        'address' => $provider->credentials['MAIL_REPLY_FROM_ADDRESS'],
                        'name' => $provider->credentials['MAIL_REPLY_FROM_NAME'] ?? $message->from,
                    ],
                ];
                
                config($providerX);
                try {
                    Mail::to($message->recipient)->send(new MessageTransactionMail($message->toArray(), $providerX));

                    $message->update(['status' => 'sent']);
                }catch(\Exception $e) {
                    $message->update(['errors' => $e->getMessage()]);
                }
            }
        }
        
        return response()->json('All done');
    }
}
