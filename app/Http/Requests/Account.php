<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Account extends FormRequest
{
    public function __construct(account $id)
    {
        $this->account = $id;

    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
