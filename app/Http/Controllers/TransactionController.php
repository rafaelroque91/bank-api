<?php

namespace App\Http\Controllers;

use App\Exceptions\NoFundsException;
use App\Http\Requests\NewTransactionRequest;
use App\Http\Resources\AccountResource;
use App\Http\Resources\TransactionResource;
use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

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
     //   dd($request->validated());
/*
        no cron jo, pegar os cado do banco (criar um indica no campo data e no status da transação),
        e disparar para um fila no redis, ai no consumer da fila q eu processo a transferencia

    na rotina de transferencia fazer um backoff loci para falhas de comuinicação

        contrinuar aquiiii


criar indices para os cron jogs pelo status e data
*/
        try {
            $newTransaction = $this->transactionService->newTransactionFromArray($request->validated());
            return TransactionResource::make($newTransaction);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
