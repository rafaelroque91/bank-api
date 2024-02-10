<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {
    }

    /**
     * @throws \Exception
     */
    public function transaction(NewTransactionRequest $request)
    {
        try {
            $newTransaction = $this->transactionService->newTransactionFromArray($request->validated());
            return TransactionResource::make($newTransaction);
        } catch (\Throwable $e) {
            Log::warning('error to charge transaction', ['message' => $e]);
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
