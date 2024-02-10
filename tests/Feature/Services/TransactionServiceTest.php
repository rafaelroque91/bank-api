<?php

namespace Tests\Feature\Services;

use App\Enums\TransactionStatus;
use App\Exceptions\NoFundsException;
use App\Exceptions\TransactionException;
use App\ExternalAuthClient;
use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use App\Repositories\Dto\ExternalAuthResponseDto;
use App\Repositories\Dto\TransactionDto;
use App\Repositories\TransactionRepository;
use App\Services\TransactionService;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    private $accountRepository;
    private $transactionRepository;
    private $externalAuthClient;
    protected function setUp(): void
    {
        parent::setUp();
        $this->accountRepository =  new AccountRepository();
        $this->transactionRepository =   new TransactionRepository();
        $this->externalAuthClient =  \Mockery::mock(ExternalAuthClient::class);
    }

    /*
    public function testChargeTransactionDataProvider(): array
    {
        return [
            [
                "success" => "true",
                "authorized" => "false"
            ]
        ];
    }
    */

    public function dataProviderTestChargeTransaction()
    {
        return [
            'successful' => [
                'sender_balance' => 1000,
                'receiver_balance' => 100,
                'transaction_amount' => 500,
                'authorized' => true,
                'expected_status' => TransactionStatus::SUCCESSFUL,
            ],
        ];
    }

    /**
     * @throws \Throwable
     * @throws TransactionException
     * @throws NoFundsException
     * @dataProvider dataProviderTestChargeTransaction
     */
    public function testChargeTransaction($senderBalance, $receiverBalance, $transactionAmount, $authorized, $exceptedStatus): void
    {
        $sender =  Account::factory()->create(['balance' => $senderBalance]);
        $receiver =  Account::factory()->create(['balance' => $receiverBalance]);

        $transaction = Transaction::factory(1)->create(
            ['amount' => $transactionAmount,
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'status' => 0,
                'scheduled_to' => null,
            ])->first();

        $transactionDto = TransactionDto::createFromModel($transaction);

        $transactionService = $this->getMockBuilder(TransactionService::class)
            ->setConstructorArgs([$this->transactionRepository,
                $this->accountRepository,
                $this->externalAuthClient
            ])->onlyMethods(['validateTransaction'])->
            getMock();

        $transactionService->expects($this->any())
            ->method('validateTransaction');

        $transactionService->chargeTransaction($transactionDto, $transaction);

        $this->assertDatabaseHas('transactions',[
            'id' => $transaction->id,
            'status' => $exceptedStatus,
        ]);
    }
}
