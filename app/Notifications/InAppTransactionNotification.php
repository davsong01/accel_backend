<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Channels\SmsMessage;
use App\Constants\TransactionType;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InAppTransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $actionType;
    public $dateOfTxn;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Transaction $transaction)
    {
        $this->actionType = $this->transaction->type;
        $this->dateOfTxn = $this->transaction->type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [SmsChannel::class, 'database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable)
    {
        return (new MailMessage)
                    ->subject("[{$this->actionType}] Transaction Notification")
                    ->greeting("Dear {$notifiable->first_name},")
                    ->line("A {$this->actionType} transaction has occured on {$this->transaction->account?->name}.")
                    ->line("Amount: N{$this->transaction?->amount} Balance:{$this->transaction->account?->balance}.")
                    ->line("Date: {$this->dateOfTxn}")
                    ->line("Thank You.");
    }

    /**
     * Get the SMS representation of the notification.
     * @param  mixed  $notifiable
     * @return void
     */
    public function toSms(object $notifiable)
    {
        return (new SmsMessage())
                ->from(config('app.name'))
                ->to($notifiable->phone)
                ->line("Dear {$notifiable->first_name},")
                ->line("A {$this->actionType} transaction has occured on {$this->transaction->account->name}.")
                ->line("Amount: N{$this->transaction->amount} Balance:{$this->transaction->account?->balance}.")
                ->line("Date: {$this->dateOfTxn}")
                ->line("Thank You.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'data' => $this->transaction,
            'staff' => "{$notifiable->first_name}",
            'action' => $this->actionType,
            'amount' => $this->transaction->amount,
            'balance' =>$this->transaction->account?->balance,
            'dateOfTxn' => $this->dateOfTxn
        ];
    }
}
