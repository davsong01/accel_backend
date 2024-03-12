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
use App\Models\MessageTemplate;

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

    public function getActiveProvider($type)
    {
        $provider = MessageProvider::where(['type' => $type, 'status' => 'active', 'company_id' => $this->sessionDetails('company_id')])->first();
        return $provider;
    }

    public function logEmailTransaction($data)
    {
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

        // Create QA Emaii;
        $data['recipient'] = 'quality@consode.com';
        $qa = MessageTransaction::create($data);
        // End QA Email

        return $mes;
    }

    public function logSmsTransaction($data)
    {
        dd($data);
    }

    public function getMessageContent($data)
    {
        $company_setting = CompanySetting::where('company_id', $this->sessionDetails('company_id'))->first();
        $url = $company_setting->website;
        $button_style = "background: " . $company_setting->primary_color . ";text-decoration: none; padding: 10px;color: white;border: none;color: white;border-radius: 5px;";
        $all = [];
        $content = "";
        $subject = "";

        if ($data['type'] == 'password-reset') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'password-reset')->first();
                $content = $email_template->content ?? null;

                if (!empty($content)) {
                    $content .= $content;
                } else {
                    $content .= 'Hello, your password reset link is: ' . $url . '/reset-password?token=' . $data['token'];
                }
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'password-reset')->first();
                $subject = $email_template->subject ?? null;
                $content = $email_template->content ?? null;

                $subject = $subject ?? 'Password Reset Link';
                $content .= $content ?? '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">You asked for a password RESET mail on ' . $this->sessionDetails('company_name') . '. Please click the button below to reset your password. Thank you <br><br><a href="' . $url . '/reset-password?token=' . $data['token'] . '"><button style="background: green;text-decoration: none;padding: 10px;color: white;">RESET PASSWORD</button></a></p>
                </div>';
            }
        }

        if ($data['type'] == 'nudge.customer') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'nudge.customer')->first();
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'nudge.customer')->first();
                $subject = $email_template->subject ?? 'Complete Your Application Form';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ',</strong><br><br>
                <div><p style="text-align:justify !important">I hope this email finds you well. We appreciate your interest in ' . $this->sessionDetails('company_name') . ' and are excited to have you on board.<br><br>There are some sections of the application form we want you to fill out to ensure that we have all the necessary information to provide you with the best possible experience. <br><br>To expedite the process, here are the details to access your application form:<br><br>

                Please <a style="' . $button_style . '" href="' . $url . '/customer-login">LOGIN</a> to fill your application form:<br><br>
                <strong>Email:</strong> ' . $data['email'] . '<br>
                <strong>Customer ID:</strong> ' . $data['customerId'] . '<br>
                <strong>Password:</strong> ' . 'Your set password' . '<br><br> If you encounter any challenges or have questions while filling out the form, please don\'t hesitate to contact us. Thank you for your prompt attention to this matter. We value your partnership and look forward to serving you.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'se.escavation.payment') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'se.escavation.payment')->first();
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'se.escavation.payment')->first();

                $subject = $email_template->subject ?? 'Payment Confirmation';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">We are delighted to inform you that your payment for ' . $data['element'] . ' has been successfully processed. Attached to this email is your payment receipt<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'nudge.admin') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'nudge.admin')->first();
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'nudge.admin')->first();

                $subject = $email_template->subject ?? 'Your Attention is Needed';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">Could you please take a moment to look into ' . $data['form']->form_name . '<br><br>Your timely review and feedback are essential for us to proceed smoothly.<br><br>Thank you for your prompt attention to this matter.<br><br>Please click the button below to attend to this form<br><br><a href="' . $url . '/customer-form-response/' . $data['form']->id . '"><button style="' . $button_style . '">CHECK IT OUT</button></a><br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'allocation.email') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'allocation.email')->first();
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'allocation.email')->first();

                $subject = $email_template->subject ?? 'Congratulations on Completing Your Property Payment';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">Warmest congratulations! <br><br>We are thrilled to inform you that your final payment for your property has been successfully received, marking an important milestone and officially becoming a property owner with ' . $this->sessionDetails('company_name') . '<br><br>Find attached to this email a copy of your payment receipt. Please visit our office to pick up your Allocation Letter, other documents, timelines, and requirements needed will be communicated on your visit. If you have any questions, please contact our support team/contact person.<br><br>Once again, congratulations on this remarkable achievement.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }


        if ($data['type'] == 'staff.store') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $subject = 'Welcome to ' . $this->sessionDetails('company_name');
                $content .= '<strong>Dear ' . $data['name'] . ',</strong><br><br>
                <div>
                <p style="text-align:justify !important">Your new staff details have been created. Please find details below: <br><br>
                First Name: ' . $data['user']['first_name'] . '<br>
                Last Name: ' . $data['user']['last_name'] . '<br>
                Email: ' . $data['user']['email'] . '<br>
                Staff Id: ' . $data['user']['staff_id'] . '<br>
                Password: ' . '123456789' . '<br><br> Please click the login button below to login to your personalized dashboard using your email/staff Id and password above. Thank you <br><br><a href="' . $url . '"><button style="' . $button_style . '">LOGIN</button></a></p>
                </div>';
            }
        }

        if ($data['type'] == 'user.store') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $subject = 'Welcome to ' . $this->sessionDetails('company_name');
                $content .= '<strong>Dear ' . $data['name'] . ',</strong><br><br>
                <div>
                <p style="text-align:justify !important">We are pleased to inform you that your details have been successfully added to our system. We are thrilled to have you with us.<br><br>Here are the details of your contract: <br>
                 <strong>Property Type: </strong>' . $data['property_type'] . '<br>
                <strong>City: </strong>' . $data['city'] . '<br>
                <strong>Location: </strong>' . $data['location'] . '<br>
                <strong>Estate: </strong>' . $data['estate'] . '<br>
                <strong>No. of Units: </strong>' . $data['no_of_units'] . '<br>
                <strong>Contract Type: </strong>' . $data['contract_type'] . '
                <strong>Plot Size: </strong>' . $data['plot_size'] . '
                <strong>Building Type: </strong>' . $data['building_type'] . '
               <br><br>Please review the contract details and let us know if there are any discrepancies or if you need further clarification.<br><br>Your Customer ID is: ' . $data['customer_id'] .
                    '<br><br>Thank you for choosing ' . $this->sessionDetails('company_name') . 'We look forward to serving you.<br><br>Best Regards,<br>' . $this->sessionDetails('company_name') . '</div>';
            }
        }

        if ($data['type'] == 'add.contract.to.existing.customer') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'add.contract.to.existing.customer')->first();
                $content = $email_template->content ?? null;
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'add.contract.to.existing.customer')->first();
                $subject = $email_template->subject ?? 'New Contract Added to Your Account';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">This is to inform you that a new contract has been successfully added to your account in our system.
                <br>Here are the details of the newly added contract:<br><br></p>
                <p style="text-align:justify !important">
                <strong>Property Type: </strong>' . $data['property_type'] . '<br>
                <strong>City: </strong>' . $data['city'] . '<br>
                <strong>Location: </strong>' . $data['location'] . '<br>
                <strong>Estate: </strong>' . $data['estate'] . '<br>
                <strong>No. of Units: </strong>' . $data['no_of_units'] . '<br>
                <strong>Contract Type: </strong>' . $data['contract_type'] . '<br>
                <strong>Plot Size: </strong>' . $data['plot_size'] . '
                <strong>Building Type: </strong>' . $data['building_type'] . '<br><br>
                Please review these details and reach out to us for any necessary follow-ups or clarifications.<br>Thank you for your continued trust in our services.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '

                </p>
                </div>';
            }
        }

        if ($data['type'] == 'send.email.to.secondary.subscriber') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'send.email.to.secondary.subscriber')->first();
                $content = $email_template->content ?? null;
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'send.email.to.secondary.subscriber')->first();
                $subject = $email_template->subject ?? 'Welcome to Your Joint Property Ownership';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">We are delighted to inform you that you have been added as a joint owner of a property with ' . $this->sessionDetails('company_name') . '. This is a significant step, and we are glad to have you on board.<br><br>As a joint owner, you now share rights and responsibilities related to the property. This includes the ability to make decisions regarding the use, maintenance, and sale of the property. <br><br>Here are some of the details of your property:<br>
                <strong>Property Type: </strong>' . $data['property_type'] . '<br>
                <strong>City: </strong>' . $data['city'] . '<br>
                <strong>Location: </strong>' . $data['location'] . '<br>
                <strong>Estate: </strong>' . $data['estate'] . '<br>
                <strong>No. of Units: </strong>' . $data['no_of_units'] . '<br>
                <strong>Plot Size: </strong>' . $data['plot_size'] . '
                <strong>Building Type: </strong>' . $data['building_type'] . '<br>
                <strong>Contract Type: </strong>' . $data['contract_type'] . '<br><br>
                If you have any questions or need further clarification, please do not hesitate to contact us. Our team is always ready to assist you.<br><br>Thank you for choosing us. We look forward to serving you.
                <br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '
                </p>
                </div>';
            }
        }

        if ($data['type'] == 'primary.marketer.email.for.new.contract') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'primary.marketer.email.for.new.contract')->first();
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'primary.marketer.email.for.new.contract')->first();
                $subject = $email_template->subject ?? 'New Contract Added to Your Client\'s Account';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">This is to inform you that a new contract has been successfully added to the account of your client, ' . $data['customer_name'] . '. Here are the details of the customer\'s contract:<br><br>
               Name: ' . $data['customer_details']['customer_name'] . '<br> 
               Phone Number: ' . $data['customer_details']['customer_phone'] . '<br> 
               Customer ID: ' . $data['customer_details']['customerId'] . '<br> <br>
                <strong>Property Type: </strong>' . $data['property_type'] . '<br>
                <strong>City: </strong>' . $data['city'] . '<br>
                <strong>Location: </strong>' . $data['location'] . '<br>
                <strong>Estate: </strong>' . $data['estate'] . '<br>
                <strong>No. of Units: </strong>' . $data['no_of_units'] . '<br>
                <strong>Contract Type: </strong>' . $data['contract_type'] . '<br>
                <strong>Plot Size: </strong>' . $data['plot_size'] . '
                <strong>Building Type: </strong>' . $data['building_type'] . '<br>
                <br>Please ensure to review these details and reach out to the customer for any necessary follow-ups or clarifications. <br><br>Thank you for your attention to this matter.<br><br>Best regards,
                <br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'primary.marketer.email.for.new.customer') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'primary.marketer.email.for.new.customer')->first();
                $content = $email_template->content ?? null;
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'primary.marketer.email.for.new.customer')->first();
                $subject = $email_template->subject ?? 'New Customer Added Successfully';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">This is to inform you that a new contract has been successfully added to the account of your client, ' . $data['customer_name'] . '. Here are the details of the customer\'s contract:<br><br>
               Name: ' . $data['customer_details']['customer_name'] . '<br> 
               Phone Number: ' . $data['customer_details']['customer_phone'] . '<br> 
               Customer ID: ' . $data['customer_details']['customerId'] . '<br> <br>
                <strong>Property Type: </strong>' . $data['property_type'] . '<br>
                <strong>City: </strong>' . $data['city'] . '<br>
                <strong>Location: </strong>' . $data['location'] . '<br>
                <strong>Estate: </strong>' . $data['estate'] . '<br>
                <strong>No. of Units: </strong>' . $data['no_of_units'] . '<br>
                 <strong>Plot Size: </strong>' . $data['plot_size'] . '<br>
                <strong>Building Type: </strong>' . $data['building_type'] . '<br>
                <strong>Contract Type: </strong>' . $data['contract_type'] . '<br><br>Please ensure to review these details and reach out to the customer for any necessary follow-ups or clarifications. <br><br>Thank you for your attention to this matter.<br><br>Best regards,
                <br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'formrequest.approve') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $subject = 'Subscriber Application Approved - Welcome to ' . $this->sessionDetails('company_name');
                $content .= '<strong>Dear ' . $data['name'] . ',</strong><br><br>
                <div>
                <p style="text-align:justify !important">We are pleased to inform you that your recent application to ' . $this->sessionDetails('company_name') . ' has been carefully reviewed and approved.<br><br>We are excited to welcome you as a customer and look forward to a fruitful relationship.<br><br>
                Please visit our office to pick up your Offer Letter, and if you have any questions, do not hesitate to contact our support team/contact person.
                <br><br> Once again, congratulations, and we anticipate a fruitful association. <br><br>Best regards,<br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'formrequest.decline') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $subject = 'Subscriber Application Declined - Missing/Incorrect Information';
                $content .= '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">Thank you for submitting your application to ' . $this->sessionDetails('company_name') . '. Unfortunately, we are unable to proceed with the processing of your application as certain crucial information is missing/incorrect.<br><br>Please review the provided feedback below and make the necessary corrections before resubmitting your application.<br><div style="margin:5px;padding:10px;background:#d8e6f1 "><strong>' . $data['reason'] . '</strong></div>
                We appreciate your understanding and encourage you to reach out if you have any questions or need further clarification.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'form.payment') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'form.payment')->first();
                $subject = $email_template->subject ?? 'Receipt for Form payment';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">We appreciate your business with ' . $this->sessionDetails('company_name') . '. <br>Attached is the receipt for your records.<br><br>If you have any questions or concerns, feel free to reach out to our customer support.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'contract.payment') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'contract.payment')->first();
                $subject = $email_template->subject ?? 'Payment Confirmation';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">We are delighted to inform you that your first installment payment for your chosen property has been successfully processed. Congratulations on taking the initial step towards property ownership with ' . $this->sessionDetails('company_name') . '.<br><br>You will receive a separate email with an official receipt for your first and subsequent installment payments. <br><br>Please visit our office to pick up your Provisional Allocation Letter, and if you have any questions, do not hesitate to contact our support team/contact person.<br><br>We look forward to creating a rewarding experience for you as a property owner.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'create.leave.request') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'create.leave.request')->first();
                $subject = $email_template->subject ?? 'Leave Request';
                $content = $email_template->content ?? '<div>
                <p style="text-align:justify !important">' . $data['content'] . '.<br><br><br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'approve.leave.request') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'approve.leave.request')->first();
                $subject = $email_template->subject ?? 'Leave Request';
                $content = $email_template->content ?? '<div>
                <p style="text-align:justify !important">' . $data['content'] . '.<br><br><br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }


        return [
            'subject' => $subject,
            'content' => $content,
        ];
    }

    public function processMessagesTransaction($pick = 100)
    {
        $messages = MessageTransaction::where(['status' => 'pending'])->get()->take($pick);

        if (!$messages) {
            return response()->json('No pending messages');
        }

        foreach ($messages as $message) {
            $provider = MessageProvider::where('id', $message->provider_id)->first();
            if ($message->medium == 'sms') {
            }

            $message->attachments = !empty($message->attachments) ? json_decode($message->attachments) : null;

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
                } catch (\Exception $e) {
                    // dd($e->getMessage());
                    $message->update(['errors' => $e->getMessage()]);
                }
            }
        }

        return response()->json('All done');
    }
}
