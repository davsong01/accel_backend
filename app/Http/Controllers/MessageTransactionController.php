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
use App\Models\CustomerContract;
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

    public function getActiveProvider($type, $company_id = null)
    {
        $company_id = $this->sessionDetails('company_id') ?? $company_id;
        $provider = MessageProvider::where(['type' => $type, 'status' => 'active', 'company_id' => $company_id])->first();
        return $provider;
    }

    public function logEmailTransaction($data)
    {
        $data['medium'] = 'email';
        $company_id = $data['company_id'] ?? null;
        $data['provider_id'] = $this->getActiveProvider('email', $company_id)->id;
        $data['from'] = $this->getActiveProvider('email',  $company_id)->credentials['MAIL_FROM_ADDRESS'] ?? null;
        $data['name'] = $data['name'] ?? Auth::user()->first_name;
        $data['recipient'] = $data['email'] ?? Auth::user()->email;
        $data['message'] = $this->getMessageContent($data)['content'];
        $data['subject'] = $this->getMessageContent($data)['subject'];
        $data['schedule_date'] = Carbon::now();
        $data['company_id'] = $this->sessionDetails('company_id') ??  $data['company_id'];
        $data['purpose'] = $data['type'];

        $data = Arr::except($data, ['name']);

        $mes = MessageTransaction::create($data);
        
        // Create QA Emaii;
        // $data['recipient'] = 'quality@consode.com';
        // $qa = MessageTransaction::create($data);
        // End QA Email

        return $mes;
    }

    public function logSmsTransaction($data)
    {
        dd($data);
    }

    public function getMessageContent($data)
    {
        $company_setting = CompanySetting::where('company_id', $this->sessionDetails('company_id') ??  $data['company_id'])->first();
        $url = $company_setting->website;
        $button_style = "background: " . $company_setting->primary_color . ";text-decoration: none; padding: 10px;color: white;border: none;color: white;border-radius: 5px;cursor: pointer;";
        $all = [];
        $content = "";
        $subject = "";

        if ($data['type'] == 'password-reset' || $data['type'] == 'forgot-password') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id') ??  $data['company_id'])->where('medium', 'sms')->where('type', 'password-reset')->first();
                $content = $email_template->content ?? null;

                if (!empty($content)) {
                    $content .= $content;
                } else {
                    // $content .= 'Hello, your password reset link is: ' . $url . '/reset-password?token=' . $data['token'];
                    $content .= 'Hello, your password reset token is: ' . $data['token'];
                }
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id') ??  $data['company_id'])->where('medium', 'email')->where('type', 'password-reset')->first();
                $subject = $email_template->subject ?? null;
                $content = $email_template->content ?? null;

                $subject = $subject ?? 'Password Token Link';
                $content .= $content ?? '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">You asked for a password RESET mail on ' . $this->sessionDetails('company_name') . ', your password reset token is: ' . $data['token'] . ' Thank you.</p>
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
                Please click the button below to login to your personalized dashboard and fill your application form:<br><br><a style="' . $button_style . '" href="' . $url . '/customer-login" target="_blank">LOGIN</a><br><br>
                <strong>Email:</strong> ' . $data['email'] . '<br>
                <strong>Customer ID:</strong> ' . $data['customerId'] . '<br>
                <br> If you encounter any challenges or have questions while filling out the form, please don\'t hesitate to contact us. Thank you for your prompt attention to this matter. We value your partnership and look forward to serving you.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
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
                <p style="text-align:justify !important">We are delighted to inform you that your payment for ' . $data['element'] . ' has been successfully processed. Attached to this email is your payment receipt<br>';

                $content .= $this->propertyDetails($data);
                $content .= '<br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
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
                <p style="text-align:justify !important">Could you please take a moment to look into ' . $data['form']->form_name . '<br><br>Your timely review and feedback are essential for us to proceed smoothly.<br><br>Thank you for your prompt attention to this matter.<br><br>Please click the button below to attend to this form<br><br><a target="_blank" href="' . $url . '/customer-form-response/' . $data['form']->id . '"><button style="' . $button_style . '">CHECK IT OUT</button></a><br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
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
                Password: ' . '123456789' . '<br><br> Please click the login button below to login to your personalized dashboard using your email/staff Id and password above. Thank you <br><br><a target="_blank" href="' . $url . '"><button style="' . $button_style . '">LOGIN</button></a></p>
                </div>';
            }
        }

        if ($data['type'] == 'user.store') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $subject = 'Welcome to ' . $this->sessionDetails('company_name');
                $content = '<strong>Dear ' . $data['name'] . ',</strong><br><br>
                <div>
                <p style="text-align:justify !important">We are pleased to inform you that your details have been successfully added to our system. We are thrilled to have you with us.<br>';

                $content .= $this->propertyDetails($data);
            
                $content .= '<br>Please review the contract details and let us know if there are any discrepancies or if you need further clarification.<br><br>Your Customer ID is<strong>: ' . $data['customer_id'] .
                '</strong><br><br>Thank you for choosing ' . $this->sessionDetails('company_name') . '. <br><br>We look forward to serving you.<br><br>Best Regards,<br>' . $this->sessionDetails('company_name') . '</div>';
            }
        }

        if ($data['type'] == 'add.contract.to.existing.customer') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'add.contract.to.existing.customer')->first();
                $content = $email_template->content ?? null;
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'add.contract.to.existing.customer')->first();
                $subject = $email_template->subject ?? 'New Contract Added to Your Account';
                $temp_content = '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">This is to inform you that a new contract has been successfully added to your account in our system.';

                $temp_content .= $this->propertyDetails($data);

                $temp_content .= '<br><br>
                Please review these details and reach out to us for any necessary follow-ups or clarifications.<br>Thank you for your continued trust in our services.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p></div>';

                $content = $email_template->content ?? $temp_content;
            }
        }

        if ($data['type'] == 'send.email.to.secondary.subscriber') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'send.email.to.secondary.subscriber')->first();
                $content = $email_template->content ?? null;
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'send.email.to.secondary.subscriber')->first();
                $subject = $email_template->subject ?? 'Welcome to Your Joint Property Ownership';
                $temp_content = '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">We are delighted to inform you that you have been added as a joint owner of a property with ' . $this->sessionDetails('company_name') . '. This is a significant step, and we are glad to have you on board.<br><br>As a joint owner, you now share rights and responsibilities related to the property. This includes the ability to make decisions regarding the use, maintenance, and sale of the property. <br><br>Here are some of the details of your property:<br>';

                $temp_content .= $this->propertyDetails($data);
                $temp_content .= 'If you have any questions or need further clarification, please do not hesitate to contact us. Our team is always ready to assist you.<br><br>Thank you for choosing us. We look forward to serving you.
                <br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '
                </p>
                </div>';

                $content = $email_template->content ?? $temp_content;
            }
        }

        if ($data['type'] == 'remaining.subscriber.remove.mail' || $data['type'] == 'notify.core.owner.of.subscriber.removal') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'remaining.subscriber.remove.mail')->orWhere('type', 'notify.core.owner.of.subscriber.removal')->first();
                $content = $email_template->content ?? null;
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'remaining.subscriber.remove.mail')->orWhere('type', 'notify.core.owner.of.subscriber.removal')->first();
                $subject = $email_template->subject ?? 'Joint Property Ownership Removal';
                $temp_content = '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">We wish to inform you that ' . $data['defunctSubscriberName'] . ' has been removed as a joint owner of a property with ' . $this->sessionDetails('company_name') . '.<br><br>As the subscriber has been removed from this contract, he/she will no longer share rights and responsibilities related to the property. This includes the ability to make decisions regarding the use, maintenance, and sale of the property.<br><br>';

                $temp_content .= $this->propertyDetails($data);
                $temp_content .= 'If you have any questions or need further clarification, please do not hesitate to contact us. Our team is always ready to assist you.<br><br>Thank you for choosing us. We look forward to serving you.
                <br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '
                </p>
                </div>';

                $content = $email_template->content ?? $temp_content;
            }
        }

        if ($data['type'] == 'primary.marketer.email.for.new.contract') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'primary.marketer.email.for.new.contract')->first();
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'primary.marketer.email.for.new.contract')->first();
                $subject = $email_template->subject ?? 'New Contract Added to Your Client\'s Account';
                $temp_content = '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">This is to inform you that a new contract has been successfully added to the account of your client, ' . $data['customer_name'] . ';
                . Here are the details of the customer:<br><br>
               Name: ' . $data['customer_details']['customer_name'] . '<br> 
               Phone Number: ' . $data['customer_details']['customer_phone'] . '<br> 
               Customer ID: ' . $data['customer_details']['customerId'] . '<br>';

                $temp_content .= $this->propertyDetails($data);

                $temp_content .= '<br>Please ensure to review these details and reach out to the customer for any necessary follow-ups or clarifications. <br><br>Thank you for your attention to this matter.<br><br>Best regards,
                <br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';

                $content = $email_template->content ?? $temp_content;
            }
        }

        if ($data['type'] == 'primary.marketer.email.for.new.customer') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'sms')->where('type', 'primary.marketer.email.for.new.customer')->first();
                $content = $email_template->content ?? null;
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'primary.marketer.email.for.new.customer')->first();
                $subject = $email_template->subject ?? 'New Customer Added Successfully';
                $temp_content = '<strong>Dear ' . $data['name'] . ', </strong><br><br>
                <div>
                <p style="text-align:justify !important">This is to inform you that a new contract has been successfully added to the account of your client, ' . $data['customer_name'] . '. Here are the details of the customer:<br><br>
                Name: ' . $data['customer_details']['customer_name'] . '<br> 
                Phone Number: ' . $data['customer_details']['customer_phone'] . '<br> 
                Customer ID: ' . $data['customer_details']['customerId'] . '<br> <br>';

                $temp_content .= $this->propertyDetails($data);

                $temp_content .= '<br><br>Please ensure to review these details and reach out to the customer for any necessary follow-ups or clarifications. <br><br>Thank you for your attention to this matter.<br><br>Best regards,
                <br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';

                $content = $email_template->content ?? $temp_content;

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
                $temp_content = '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">We appreciate your business with ' . $this->sessionDetails('company_name') . '. <br>Attached is the receipt for your records.<br><br>If you have any questions or concerns, feel free to reach out to our customer support.<br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>';

                $temp_content .= $this->propertyDetails($data);
                $temp_content .= '<br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
                
                $content = $email_template->content ?? $temp_content;

            }
        }

        if ($data['type'] == 'contract.payment') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'contract.payment')->first();
                $subject = $email_template->subject ?? 'Payment Confirmation';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">We are delighted to inform you that payment for your chosen property has been successfully processed. Congratulations on taking the initial step towards property ownership with ' . $this->sessionDetails('company_name') . '.<br><br>You will receive a separate email with an official receipt for your first and subsequent installment payments. <br><br>Please visit our office to pick up your Provisional Allocation Letter, and if you have any questions, do not hesitate to contact our support team/contact person. <br><br>We look forward to creating a rewarding experience for you as a property owner.<br>';
                
                $content .= $this->propertyDetails($data);

                $content .= 'Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }

        if ($data['type'] == 'contract.payment.part') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'contract.payment')->first();
                $subject = $email_template->subject ?? 'Payment Confirmation';
                $content = $email_template->content ?? '<strong>Dear ' . $data['name'] . ',</strong><br>
                <div>
                <p style="text-align:justify !important">We are delighted to inform you that installment payment for your chosen property has been successfully processed. Congratulations on taking the initial step towards property ownership with ' . $this->sessionDetails('company_name') . '.<br><br>You will receive a separate email with an official receipt for your first and subsequent installment payments. <br><br>Please visit our office to pick up your Provisional Allocation Letter, and if you have any questions, do not hesitate to contact our support team/contact person. <br><br>We look forward to creating a rewarding experience for you as a property owner.<br>';

                $content .= $this->propertyDetails($data);

                $content .= 'Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
                </div>';
            }
        }
        

        if ($data['type'] == 'create.leave.request') {
            if (isset($data['medium']) && $data['medium'] == 'sms') {
            } else {
                $user = $data['user'];
                $lam = $data['lam'];
                $email_template = MessageTemplate::where('company_id', $this->sessionDetails('company_id'))->where('medium', 'email')->where('type', 'create.leave.request')->first();
                $subject = $email_template->subject ?? 'Leave Application Acknowledgment';
                $content = $email_template->content ?? '<div>
                <p style="text-align:justify !important">Dear '.$user->firstname.', Your leave application dated '.$lam->created_at.' has been received. It is under review by HR. We will update you soon.<br>If you have any questions or concerns, please do not hesitate to contact me or our HR department.<br><br><br>Best regards,<br><br>' . $this->sessionDetails('company_name') . '</p>
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
                    \Log::info(['Message Transaction process Error: ' => $e->getMessage()]);
                    $message->errors = $e->getMessage();
                    $message->save();
                    continue;
                }
            }
        }

        return response()->json('All done');
    }

    public function propertyDetails($data){
        if (empty($data['contract_ids'])) return '';

        $masterContent = '<br>Here are the contract(s) details: <br><br>';

        foreach($data['contract_ids'] as $id){
            $contract = CustomerContract::with(['city:id,name','location:id,name','estate:id,name', 'property_type_details:id,type', 'plot_size:id,size', 'building_type_details:id,name'])->where('id', $id)->first();

            $content = '';
            $content .= '<strong>Contract: </strong>' . $contract->contract_name . '<br>';
            
            if (!empty($contract->property_type->type)) {
                $data .= '<strong>Property Type: </strong>' . $contract->property_type->type . '<br>';
            }
    
            if (!empty($contract->city->name)) {
                $content .= '<strong>City: </strong>' . $contract->city->name . '<br>';
            }
    
            if (!empty($contract->location->name)) {
                $content .= '<strong>Location: </strong>' . $contract->location->name . '<br>';
            }
            
            if (!empty($contract->estate->name)) {
                $content .= '<strong>Estate: </strong>' . $contract->estate->name . '<br>';
            }
    
            if (!empty($contract->no_of_units)) {
                $content .= '<strong>No. of Units: </strong>' . $contract->no_of_units . '<br>';
            }
    
            if (!empty($contract->contract_type)) {
                $content .= '<strong>Contract Type: </strong>' . ucfirst($contract->contract_type) . '<br>';
            }
            
            if(!empty($contract->plotsize->size)){
                $content .= '<strong>Plot Size: </strong>' . $contract->plotsize->size . '<br>';
            }
    
            if (!empty($contract->building_type_details->name)) {
                $content .= '<strong>Building Type: </strong>' . $contract->building_type_details->name . '<br>';
            }

            if(count($data['contract_ids']) > 1){
                $content .= '<hr><br>';
            }else{
                $content .= '<br>';
            }

            $masterContent .= $content;
        }
        
        return $masterContent;
    }
}
