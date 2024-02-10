<?php

namespace App;

use App\Models\Account;
use App\Models\Transaction;
use App\Repositories\Dto\ExternalAuthResponseDto;
use App\Repositories\Dto\TransactionDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ExternalAuthClient
{
    private function generateAuthTransactionBody(TransactionDto $transaction) : array
    {
        return [
            'sender' => $transaction->getSender()->id,
            'receiver' => $transaction->getReceiver()->id,
            'amount' => TransactionDto::formatToCurrency($transaction->getAmount()),
        ];
    }

    private function parseResponse(string $response) : ExternalAuthResponseDto
    {
        return  ExternalAuthResponseDto::createFromResponse(
            json_decode($response, true)
        );
    }

    public function authorizeTransaction(TransactionDto $transaction) : ExternalAuthResponseDto
    {
        try {
            $request = new Request(
                'POST',
                config('services.external_auth.url'),
                [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . base64_encode(config('services.external_auth.key'))
                ],
                json_encode($this->generateAuthTransactionBody($transaction))
            );

            $client = new Client();

            $response = $client->send($request);

            return $this->parseResponse($response->getBody());

        } catch (GuzzleException $e) {
            throw new BadRequestException("Error: " . $e->getMessage());
        }
    }
}
