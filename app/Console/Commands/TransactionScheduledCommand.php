<?php


namespace App\Console\Commands;

use App\Models\Transaction;
use App\Repositories\Dto\TransactionDto;
use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Console\Command;

class TransactionScheduledCommand extends Command
{
    protected $signature = 'transactions:scheduled';

    protected $description = 'Get all scheduled transaction for today and send it to queue';

    public function handle(TransactionService $transactionService) : int
    {
        $transactionService->sendScheduledTransactionsToQueue();
        return Command::SUCCESS;
    }
}
