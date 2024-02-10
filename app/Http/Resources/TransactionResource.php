<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class TransactionResource extends AbstractResource
{
    public function toArray(Request $request) : array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
            'amount' => $this->amount,
            'status' => $this->status,
            'scheduled_to' => $this->formatDateToUser($this->scheduled_to),
            'charged_at' => $this->formatDateToUser($this->charged_at),
            'error' => $this->error,

        ];
    }
}
