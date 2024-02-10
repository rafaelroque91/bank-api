<?php

namespace App\Repositories;

use App\Exceptions\NoFundsException;
use App\Models\Account;
use App\Repositories\Contracts\RepositoryContract;
use App\Repositories\Dto\AbstractDto;

class AccountRepository
{
    /**
     * @var AbstractDto $dto
     */
    public function create(AbstractDto $dto) : Account {
        return Account::create($dto->toArray());
    }

    public function getById(?int $id) : ?Account
    {
        if ($id !== null) {
            return Account::find($id);
        }

        return null;
    }

    public function addFounds(Account $account, int $amount) : void
    {
        Account::findOrFail($account->id)->increment('balance',$amount);
    }

    public function decreaseFounds(Account $account, int $amount) : void
    {
        $account = Account::findOrFail($account->id);

        if ($account->balance < $amount) {
            throw new NoFundsException('There is no funds');
        }

        $account->decrement('balance',$amount);
    }

    public function checkAvailiableFounds(Account $account, int $amount) : bool
    {
        $account->refresh();
        return $account->balance >= $amount;
    }

}
