<?php

namespace App\Http\Requests\Order;

use App\Http\Requests\BaseFormRequest;

class OrderPostRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => [
                'required',
                'string',
                'max:255'
            ],
        ];
    }
}
