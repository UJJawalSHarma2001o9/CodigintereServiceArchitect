<?php

use CodeIgniter\HTTP\ResponseInterface;

if (!function_exists('sendResponse')) {
    /**
     * Send a formatted JSON response
     *
     * @param bool $status True for success, false for failure
     * @param int $httpStatusCode HTTP response code (e.g., 200, 400, 500)
     * @param string $message Response message
     * @param string $dataKey Key for data payload (default: 'data')
     * @param array|object|null $data Payload data (default: empty array)
     * @param array|null $errors Error messages (default: empty array)
     * @return ResponseInterface
     */
    function sendResponse(
        bool $status,
        int $httpStatusCode,
        string $message,
        string $dataKey = 'data',
        $data = [],
        $errors = []
    ) {
        // Ensure data and errors are always formatted correctly
        $formattedData = empty($data) ? [] : (is_array($data) || is_object($data) ? $data : [$data]);
        $formattedErrors = empty($errors) ? [] : (is_array($errors) ? $errors : [$errors]);

        // Create response array
        $response = [
            'success' => $status, // Boolean instead of string
            'statusCode' => $httpStatusCode,
            'message' => $message,
            $dataKey => $formattedData,
            'errors' => $formattedErrors,
        ];

        // Return structured JSON response
        return service('response')
            ->setStatusCode($httpStatusCode)
            ->setContentType('application/json')
            ->setJSON($response);
    }
}
