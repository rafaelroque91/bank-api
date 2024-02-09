<?php

namespace App\Services;

use App\Exceptions\NoFundsException;
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

    /**
     * @throws NoFundsException
     */
    public function chargeTransaction(TransactionDto $dto, Transaction $transaction): void
    {
        try {
            DB::beginTransaction();
            if (!$this->accountRepository->checkAvailiableFounds($dto->getSender(), $dto->getAmount())) {
                throw new NoFundsException('There are no funds');
            }

            /** @var ExternalAuthResponseDto $res*/
            $resAuth = $this->externalAuthClient->authorizeTransaction($dto);

            if (!$resAuth->getSuccess() || !$resAuth->getAuthorized()) {
                throw new UnauthorizedException('Unauthorized Transaction');
            }

            $this->accountRepository->decreaseFounds($dto->getSender(), $dto->getAmount());
            $this->accountRepository->addFounds($dto->getReceiver(), $dto->getAmount());
            $this->transactionRepository->setTransactionStatusSuccess($transaction);

            DB::commit();
        } catch (NoFundsException $e) {
            $this->transactionRepository->setTransactionStatusNoFunds($transaction, $e->getMessage());
            DB::commit();
            throw $e;
        } catch (UnauthorizedException $e) {
            $this->transactionRepository->setTransactionStatusUnauthorized($transaction, $e->getMessage());
            DB::commit();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('error to charge transaction', ['message' => $e]);
            $this->transactionRepository->setTransactionStatusError($transaction, $e->getMessage());
            throw $e;
        }
    }

    private function newTransaction(TransactionDto $dto) : Transaction
    {
        $newTransaction = $this->transactionRepository->create($dto);

        if (!$newTransaction->isScheduled()) {
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

    public function sendScheduledTransactionsToQueue()
    {
       foreach($this->transactionRepository->getScheduledTransactionsToSend() as $transaction){
           ChargeScheduledTransactionJob::dispatch($transaction);
       }
    }
}
