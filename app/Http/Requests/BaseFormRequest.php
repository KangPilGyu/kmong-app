<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      description="422 Exception",
 *      title="422 Exception",
 *      schema="Exception422"
 * )
 * @OA\Property(property="errors", type="array", description="error_keys",
 *     @OA\Items(
 *      type="object",
 *          @OA\Property(property="key", type="string", description="error_key"),
 *          @OA\Property(property="value", type="string", description="error_message"),
 *     )
 * )
 * @OA\Property(property="message", type="string", description="error_message")
 *
 */
class BaseFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $errorMessages = [];
        foreach ($validator->errors()->messages() as $messages) {
            foreach ($messages as $message) {
                array_push($errorMessages, $message);
            }
        }
        $message = (method_exists($this, 'message'))
            ? $this->container->call([$this, 'message'])
            : join(",", $errorMessages);

        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'message' => $message,
        ], 422));
    }
}
