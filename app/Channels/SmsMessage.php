<?php

namespace App\Channels;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsMessage
{

    protected string $providerUrl;
    protected string $username;
    protected string $password;
    protected string $to;
    protected string $from;
    protected array $lines;

    /**
     * SmsMessage constructor.
     * @param array $lines
     */
    public function __construct($lines = [])
    {
        $this->lines = $lines;

        $this->from = config('app.name');

        //TODO: fetch from company settings so client use their own SMS provider
        $this->providerUrl = config('services.sms.providerUrl');
        $this->username = config('services.sms.username');
        $this->password = config('services.sms.password');
     }

    public function line($line = ''): self
    {
        $this->lines[] = $line;

        return $this;
    }

    public function to($to): self
    {
        $this->to = $to;

        return $this;
    }

    public function from($from): self
    {
        $this->from = $from;

        return $this;
    }

    public function send(): mixed
    {
        if (!$this->from || !$this->to || !count($this->lines)) {
            throw new \Exception('SMS not correct.');
        }

        $content = join(" ", $this->lines);

        // current endpoint
        $response = Http::get("{$this->providerUrl}/sendtext", [
            'apikey' => $this->username,
            'secretkey' => $this->password,
            'toUser' => $this->to,
            'callerID' => $this->from,
            'messageContent' => $content,
        ]);

        // TODO: log as audit trail in DB for SMS Incase of payment subscription
        // Log::info('SMS response retrieved ', [
        //     'from' => $this->from,
        //     'to' => $this->to,
        //     'content' => $content,
        //     'method' => 'SmsMessage:21stCentury',
        //     'response' => $response->json()
        //     ]);

        return $response;
    }
}
