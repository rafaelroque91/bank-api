<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Exceptions\NoFundsException;
use App\Exceptions\TransactionException;
use App\Exceptions\UnauthorizedException;
use App\ExternalAuthClient;
use App\Jobs\ChargeScheduledTransactionJob;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use App\Repositories\Dto\ExternalAuthResponseDto;
use App\Repositories\Dto\TransactionDto;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\LazyCollection;
use Throwable;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountRepository $accountRepository,
        private readonly ExternalAuthClient $externalAuthClient,
    ) {
    }

    public function externalAuthorizationTransaction(TransactionDto $dto) : ExternalAuthResponseDto
    {
        return $this->externalAuthClient->authorizeTransaction($dto);
    }

    public function validateTransaction(TransactionDto $dto, Transaction $transaction) :void
    {
        try {
            if (!$this->accountRepository->checkAvailiableFounds($dto->getSender(), $dto->getAmount())) {
                throw new TransactionException(TransactionStatus::NO_FUNDS, 'There are no funds');
            }

            $resAuth = $this->externalAuthorizationTransaction($dto);

            if (!$resAuth->getSuccess() || !$resAuth->getAuthorized()) {
                throw new TransactionException(TransactionStatus::UNAUTHORIZED, 'Unauthorized Transaction');
            }
        } catch (TransactionException $e) {
            $this->transactionRepository->setTransactionStatus($transaction, $e->getStatus(), $e->getMessage());
            throw $e;
        } catch (Throwable $e) {
            Log::error('error to validate transaction', ['message' => $e]);
            $this->transactionRepository->setTransactionStatusError($transaction, $e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws Throwable
     * @throws TransactionException
     * @throws NoFundsException
     */
    public function chargeTransaction(TransactionDto $dto, Transaction $transaction): void
    {
        $this->validateTransaction($dto, $transaction);

        try {
            DB::beginTransaction();
            $this->accountRepository->decreaseFounds($dto->getSender(), $dto->getAmount());
            $this->accountRepository->addFounds($dto->getReceiver(), $dto->getAmount());
            $this->transactionRepository->setTransactionStatusSuccess($transaction);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('error to charge transaction', ['message' => $e]);
            var_dump($e->getMessage());
            $this->transactionRepository->setTransactionStatusError($transaction, $e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws Throwable
     * @throws TransactionException
     * @throws NoFundsException
     */
    private function newTransaction(TransactionDto $dto) : Transaction
    {
        $newTransaction = $this->transactionRepository->create($dto);

        if (! $newTransaction->isScheduled()) {
            $this->chargeTransaction($dto, $newTransaction);
        }

        return $newTransaction;
    }

    /**
     * @throws Throwable
     */
    public function newTransactionFromArray(array $transactionArray) : Transaction
    {
        $transactionDto = TransactionDto::createFromRequest($transactionArray);

        return $this->newTransaction($transactionDto);
    }

    public function sendScheduledTransactionsToQueue(): void
    {
       foreach($this->transactionRepository->getScheduledTransactionsToSend() as $transaction){
           ChargeScheduledTransactionJob::dispatch($transaction);
       }
    }
}
