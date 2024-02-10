<?php


namespace App\Console\Commands;

use App\Services\AccountService;
use Illuminate\Console\Command;

class CreateAccountCommand extends Command
{
    protected $signature = 'account:create {name}';

    protected $description = 'Create a new account';

    public function handle(AccountService $accountService) : int
    {
        try {
            $accountService->createAccount($this->argument('name'));
            return Command::SUCCESS;

        } catch (\Throwable $e) {
            return Command::FAILURE;
        }
    }
}
