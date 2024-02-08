<?php

namespace App\Services;

use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Repositories\Dto\AccountDto;

class AccountService
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
    ) {
    }
    public function createAccount(string $name) : Account
    {
        $accountDto = new AccountDto($name,0);
        return $this->accountRepository->create($accountDto);
    }
}
