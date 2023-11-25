<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Измените, если требуется логика авторизации
    }

    public function rules()
    {
        return [
            'valuteID' => 'required|string',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ];
    }
}
