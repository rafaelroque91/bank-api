<?php

namespace App\Repositories;

use App\Enums\TransactionStatus;
use App\Models\Transaction;
use App\Repositories\Dto\TransactionDto;
use Carbon\Carbon;
use Illuminate\Support\LazyCollection;

class TransactionRepository
{
    /**
     * param TransactionDto $dto
     * */
    public function create(TransactionDto $dto) : Transaction
    {
        $transaction = new Transaction();
        $transaction->sender()->associate($dto->getSender());
        $transaction->receiver()->associate($dto->getReceiver());
        $transaction->amount = $dto->getAmount();
        $transaction->scheduled_to = $dto->getScheduledTo();
        $transaction->status = ($transaction->isScheduled() ? TransactionStatus::SCHEDULED :TransactionStatus::PENDING);
        $transaction->save();

        return $transaction;
    }

    public function setTransactionStatusSuccess(Transaction $transaction) : void
    {
        $transaction->status = TransactionStatus::SUCCESSFUL;
        $transaction->charged_at = Carbon::now();
        $transaction->save();
    }


    public function setTransactionStatus(Transaction $transaction, TransactionStatus $status, string $error) : void
    {
        $transaction->status = $status;
        $transaction->error = $error;
        $transaction->save();
    }

    public function setTransactionStatusError(Transaction $transaction, string $error) : void
    {
        $transaction->status = TransactionStatus::ERROR;
        $transaction->error = $error;
        $transaction->save();
    }

    public function getScheduledTransactionsToSend() : LazyCollection
    {
        return Transaction::where('status',TransactionStatus::SCHEDULED)
            ->where('scheduled_to',Carbon::now()->toDateString())->cursor();
    }
}
