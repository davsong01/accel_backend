<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\CompanySetting;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageTransactionMail extends Mailable
{
    use Queueable, SerializesModels;
    private $data;
    private $provider;
    /**
     * Create a new message instance.
     */
    public function __construct($data, $provider)
    {
        $this->data = $data;
        $this->provider = $provider;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $companySettings = CompanySetting::where('company_id', $this->data['company_id'])->first();
        session()->now('companySetting', $companySettings);

        dispatch(function () {
            session()->forget(['companySetting']);
        })->afterResponse();
        
        return new Envelope(
            from: new Address($this->provider['mail.from']['address'], $this->provider['mail.from']['name']),
            replyTo: [
                new Address($this->provider['mail.replyToAddress'], $this->provider['mail.replyToName']),
            ],
            
            subject: $this->data['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
       
        return new Content(
            markdown: 'emails.messages_transaction',
            with: [
                'data' => $this->data,
            ],
            
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $files = [];

        if(!empty($this->data['attachments'])){
            foreach($this->data['attachments'] as $key=>$attachment){
                
                if (file_exists(base_path() . '/' . $attachment)) {
                    $files[] = Attachment::fromPath(base_path() . '/' . $attachment);
                }
            }
        }
     
        return $files;
    }
}
