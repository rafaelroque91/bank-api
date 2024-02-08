<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService
    ) {
    }

    /**
     * @throws \Exception
     */
    public function create(CreateAccountRequest $request): AccountResource|JsonResponse
    {
        try {
            $newAccount = $this->accountService->createAccount($request->validated('name'));

            return AccountResource::make($newAccount);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
}
