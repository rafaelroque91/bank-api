<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'sender'   => ['required' , 'int', 'exists:accounts,id'],
            'receiver' => ['required' , 'int', 'exists:accounts,id'],
            'amount'   => ['required' , 'int', 'gt:0'],
            'scheduled_to' => ['sometimes', 'date', 'date_format:Y-m-d','after:today','before:"2050-12-31"']
        ];
    }
}
