<?php

namespace App\Constants;

enum TransactionType: string
{
    case DEBIT = 'DEBIT';
    case CREDIT = 'CREDIT';
}
