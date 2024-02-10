<?php

namespace Tests\Feature\Services;

use App\Repositories\AccountRepository;
use App\Services\AccountService;
use Tests\TestCase;

class AccountServiceTest extends TestCase
{
    private AccountService $accountService;
    private AccountRepository $accountRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->accountRepository = new AccountRepository();
        $this->accountService = new AccountService($this->accountRepository);
    }

    public function dataCreateAccount()
    {
        return [
            [
                'name' => 'User123',
            ],
            [
                'name' => 'User456',
            ],
            [
                'name' => 'User789',
            ],

        ];
    }

    /** @dataProvider dataCreateAccount */
    public function testCreateAccount($name)
    {
        $resAccount = $this->accountService->createAccount($name);

        $this->assertEquals($resAccount->name, $name);
        $this->assertDatabaseHas('accounts',['name' => $name]);
    }
}
