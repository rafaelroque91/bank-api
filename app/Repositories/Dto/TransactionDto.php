<?php

namespace App\Repositories\Dto;

use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use Carbon\Carbon;

class TransactionDto extends AbstractDto
{

    private ?int $id = null;
    private ?Account $sender = null;
    private ?Account $receiver = null;
    private ?int $amount = null;
    private ?Carbon $scheduledTo = null;
    private ?string $error = null;
    private ?int $status = null;
    private ?Carbon $chargedTo = null;


    public static function createFromRequest(array $array) : self
    {
        $accountRepo = new AccountRepository();
        $dto = new self();
        $dto->sender = $accountRepo->getById($array['sender']?? null);
        $dto->receiver = $accountRepo->getById($array['receiver'] ?? null);
        $dto->amount = self::formatCurrencyToDB($array['amount'] ?? 0);
        $dto->scheduledTo = Carbon::make($array['scheduled_to'] ?? null);
        $dto->error = ($array['error'] ?? '');

        return $dto;
    }

    public static function createFromModel(Transaction $transaction) : self
    {
        $dto = new self();
        $dto->id = $transaction->id;
        $dto->sender = $transaction->sender;
        $dto->receiver = $transaction->receiver;
        $dto->amount = self::formatCurrencyToDB($transaction->amount);
        $dto->scheduledTo = Carbon::make($transaction->scheduled_to);
        $dto->chargedTo = Carbon::make($transaction->charged_to);
        $dto->status = $transaction->status;
        $dto->error = $transaction->error;
        return $dto;
    }

    public function getSender() : Account
    {
        return $this->sender;
    }

    public function getReceiver() : Account
    {
        return $this->receiver;
    }

    public function getAmount() : int
    {
        return $this->amount;
    }

    public function getScheduledTo() : ?Carbon
    {
        return $this->scheduledTo;
    }

    public function getAuthorized() : ?bool
    {
        return $this->authorized;
    }

    public function getError() : ?string
    {
        return $this->error;
    }
}
