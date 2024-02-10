<?php

namespace App\Exceptions;

use App\Enums\TransactionStatus;
use Exception;

class TransactionException extends Exception
{
    public function __construct(
        private readonly TransactionStatus $status,
        protected $message,
    ) {
        parent::__construct($this->message);
    }

    public function getStatus() : TransactionStatus
    {
        return $this->status;
    }
}
