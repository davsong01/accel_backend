<?php

namespace App\Listeners\Transaction;

use App\Constants\AppErrorMessage;
use App\Constants\TransactionType;
use App\Models\ChartOfAccount;
use App\Notifications\InAppTransactionNotification;
use Exception;
use Illuminate\Support\Facades\DB;

class AccountBalanceUpdateListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        DB::beginTransaction();

        try {
            $user = $event->user;
            $transaction = $event->transaction;
            $txnType = $transaction->type; // CREDIT | DEBIT

            $chartOfAccount = ChartOfAccount::where('id', $transaction?->account_id)->lockForUpdate()->first();
            $currentBalance = $chartOfAccount->balance ?? 0;

            switch ($txnType) {
                case TransactionType::DEBIT->value:
                    if($currentBalance < $transaction->amount){
                        throw new Exception(AppErrorMessage::INSUFFICIENT_BALANCE);
                    }
                    $balanceAfter = $currentBalance - $transaction->amount;
                    break;
                case TransactionType::CREDIT->value:
                    $balanceAfter = $currentBalance + $transaction->amount;
                    break;
            }

            // attach txn balance ledger entry
            $transaction->balance_before =  $currentBalance;
            $transaction->balance_after =  $balanceAfter;
            $transaction->save();

            $chartOfAccount->balance = $transaction->balance_after;
            $chartOfAccount->save();

            $user->notify(new InAppTransactionNotification($transaction));

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            // if error occur, delete the transaction from database
            $event->transaction->delete();
            return response()->json([
                'error' => $th->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
