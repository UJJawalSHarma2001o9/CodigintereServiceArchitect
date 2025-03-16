<?php

use CodeIgniter\HTTP\ResponseInterface;

if (!function_exists('send_response')) {
    /**
     * Send a formatted JSON response
     *
     * @param bool $status
     * @param int $httpStatusCode
     * @param string $message
     * @param string $dataKey
     * @param array $data
     * @param array $errors
     * @return ResponseInterface
     */
    function sendResponse(
        bool $status,
        int $httpStatusCode,
        string $message,
        string $dataKey = 'data',
        array $data = [],
        array $errors = []
    ) {
        $response = [
            'status' => $status ? 'success' : 'failed',
            'statusCode' => $httpStatusCode,
            'message' => $message,
            'errors' => $errors,
            $dataKey => $data,
        ];

        return service('response')->setStatusCode($httpStatusCode)->setJSON($response);
    }
}
