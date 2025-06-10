<?php

namespace App\UI\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Retourne une réponse de succès standardisée
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    public static function success($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    /**
     * Retourne une réponse d'erreur standardisée
     *
     * @param string $message
     * @param int $status
     * @param mixed $errors
     * @return JsonResponse
     */
    public static function error(string $message = 'Error', int $status = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Retourne une réponse d'authentification standardisée
     *
     * @param string $token
     * @param string $type
     * @return JsonResponse
     */
    public static function auth(string $token, string $type = 'Bearer'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => $type,
        ]);
    }

    /**
     * Retourne une réponse de création standardisée
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    public static function created($data = null, string $message = 'Created successfully'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    /**
     * Retourne une réponse de suppression standardisée
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function deleted(string $message = 'Deleted successfully'): JsonResponse
    {
        return self::success(null, $message);
    }
}
