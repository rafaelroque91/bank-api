<?php

namespace App\Enums;

enum TransactionStatus: int
{
    case PENDING = 0;
    case SUCCESSFUL = 1;
    case SCHEDULED = 2;
    case NO_FUNDS = 3;
    case UNAUTHORIZED = 4;

    case ERROR = 5;
}
