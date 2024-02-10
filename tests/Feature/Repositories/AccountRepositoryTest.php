<?php

namespace Tests\Feature\Services;

use App\Models\Account;
use App\Repositories\AccountRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AccountRepositoryTest extends TestCase
{
    private $accountRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountRepository = new AccountRepository();
    }

    public function dataCheckAvailiableFounds()
    {
        return [
            [
                'amount' => 100,
                'balance' => 200,
                'result' => true
            ],
            [
                'amount' => 200,
                'balance' => 50,
                'result' => false
            ]
        ];
    }

    /** @dataProvider dataCheckAvailiableFounds */
    public function testCheckAvailableFounds($amount, $balance, $result)
    {
        $account = Account::factory()->create(['balance' => $balance]);
         $this->assertEquals($this->accountRepository->checkAvailiableFounds( $account,$amount),
             $result);
    }
}
