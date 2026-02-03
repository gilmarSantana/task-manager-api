<?php

namespace App\Helpers;

class Response
{

    private static function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');

        http_response_code($statusCode);

        echo json_encode($data);

        exit;
    }


    public static function success($message, $data = null)
    {
        $response = [
            'success' => true,
            'message' => $message
        ];


        // Se houver dados, adiciona ao array
        if ($data !== null) {
            $response['data'] = $data;
        }


         self::json($response, 200);
    }


    public static function created($message, $data = null)
    {
        $response = [
            'success' => true,
            'message' => $message
        ];

        // Se houver dados, adiciona ao array
        if ($data !== null) {
            $response['data'] = $data;
        }

        self::json($response, 201);
    }


    public static function unauthorized($message = 'Acesso não autorizado')
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

         self::json($response, 401);
    }


    public static function notFound($message = 'Recurso não encontrado')
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

         self::json($response, 404);
    }


    public static function error($message, $statusCode = 400)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];

        self::json($response, $statusCode);
    }
}
