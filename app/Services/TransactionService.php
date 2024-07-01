<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\Builder;

class TransactionService
{
    /**
     * Query builder for transaction filtering
     *
     * @param integer $companyId
     * @param Request $request
     * @return Builder
     */
    public function filterTransactionQuery(int $companyId, Request $request) : Builder
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Transaction::query();

        $query
            ->where('company_id', $companyId)
            ->when($from && $to, function($query) use ($from, $to){
                $from = Carbon::parse($from);
                $to = Carbon::parse($to);

                if ($to->format('H:i:s') == '00:00:00')
                    $to->endOfDay();

                $query->whereBetween('created_at', [$from, $to]);
            })
            ->when($request->input('staff_name', null), function($query, $name) use ($companyId) {
                $query->where(function ($query) use ($name, $companyId) {
                    $query->whereExists(function ($subQuery) use ($name, $companyId) {
                        $subQuery->select(DB::raw(1))
                            ->from('staff')
                            ->join('users', 'users.id', '=', 'staff.user_id')
                            ->where('staff.company_id', $companyId)
                            ->where(function ($query) use ($name) {
                                $query->where('users.first_name', 'like', '%' . $name . '%')
                                    ->orWhere('users.last_name', 'like', '%' . $name . '%')
                                    ->orWhere('users.middle_name', 'like', '%' . $name . '%');
                            })
                            ->whereColumn('staff.id', 'transactions.staff_id');
                    });
                });
            });

        return $query;
    }

    /**
     * fetchAccountTransactionQuery
     *
     * @param ChartOfAccount $account
     * @param integer $companyId
     * @param Request $request
     * @return Builder
     */
    public function fetchAccountTransactionQuery(ChartOfAccount $account, int $companyId, Request $request) : Builder
    {
        $query = $this->filterTransactionQuery(companyId: $companyId, request: $request);
        $query->where('account_id', $account->id);
        return $query;
    }

    /**
     * fetchGLAccountTransactionQuery
     *
     * @param integer $companyId
     * @param Request $request
     * @return Builder
     */
    public function fetchGLAccountTransactionQuery(int $companyId, Request $request) : Builder
    {
        $query = $this->filterTransactionQuery(companyId: $companyId, request: $request);
        $query
            ->with([
                'account', 'staff:id,user_id', 'staff.user:id,status,type,first_name,middle_name,last_name,phone_number', 'customer:id'
            ]);

        return $query;
    }

    /**
     * resolve transaction info returned
     *
     * @param Transaction $txn
     * @return array
     */
    public function resolveTransactionInfo(Transaction $txn) : array
    {
        return [
            'id' => $txn->id,
            'transaction_id' => $txn->transaction_id,
            'customer_id' => $txn->customer_id,
            'staff_id' => $txn->staff_id,
            'account_id' => $txn->account_id,
            'staff_id' => $txn->staff_id,
            'customer_id' => $txn->customer_id,
            'estate_id' => $txn->estate_id,
            'type' => $txn->type,
            'purpose' => $txn->purpose,
            'amount' => $txn->amount,
            'description' => $txn->description ?? 'N/A',
            'txn_purpose' => [
                'id' => $txn->transaction_purpose_id == 0 ? null : $txn->txn_purpose?->id,
                'name' => $txn->transaction_purpose_id == 0 ? null : $txn->txn_purpose?->name,
            ],
            'txn_type' => [
                'id' => $txn->txn_type_id == 0 ? null : $txn->txn_type?->id,
                'name' => $txn->txn_type_id == 0 ? null : $txn->txn_type?->name,
            ],
            'staff' => [
                'id' => $txn->staff?->id,
                'user_id' => $txn->staff?->user_id,
                'account_name' => $txn->staff?->account_name,
                'account_number' => $txn->staff->account_number,
                'name' => $txn->staff?->user->fullname,
            ],
            'customer' => [
                'id' => $txn->customer?->id,
                'name' => $txn->customer?->user->fullname,
            ],
            'created_at' => $txn->created_at,
            'updated_at' => $txn->updated_at,
        ];
    }
}
