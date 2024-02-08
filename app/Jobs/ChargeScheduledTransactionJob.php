<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Repositories\Dto\TransactionDto;
use App\Services\TransactionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChargeScheduledTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(

        private readonly Transaction $transaction)
    {
    }

    public function handle(TransactionService $transactionService)
    {
        $transactionDto = TransactionDto::createFromModel($this->transaction);
        $transactionService->chargeTransaction($transactionDto, $this->transaction);
    }
}
