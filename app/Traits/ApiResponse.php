<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function success($data, string $message = '', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function error(string $message, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message
        ], $statusCode);
    }

    /**
     * Return a validation error JSON response.
     *
     * @param Validator $validator
     * @return JsonResponse
     */
    protected function validationError(Validator $validator): JsonResponse
    {
        return response()->json([
            'success' => false,
            'errors' => $this->formatValidationErrors($validator)
        ], 422);
    }

    /**
     * Format the validation errors to return the first error message per field.
     *
     * @param Validator $validator
     * @return array
     */
    private function formatValidationErrors(Validator $validator): array
    {
        $errors = $validator->errors();
        $formattedErrors = [];

        foreach ($errors->getMessages() as $field => $messages) {
            $formattedErrors[$field] = $messages[0];
        }

        return $formattedErrors;
    }
}
