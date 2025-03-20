<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use App\Models\ApiKeyModel;
use App\Services\ApiKeyService;
class ApiKeyAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $apiKeyHeader = $request->getHeaderLine('x-api-key');

        if (!$apiKeyHeader || !$this->isValidApiKey($apiKeyHeader)) {
            return Services::response()
                ->setJSON([
                    'status' => 'failed',
                    'statusCode' => ResponseInterface::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid or missing API Key',
                    'errors' => [],
                    'data' => []
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing required
    }

    private function isValidApiKey($apiKey)
    {
        $apiKeyModel = new ApiKeyModel();
        $storedApiKeys = $apiKeyModel->findAll();
        foreach ($storedApiKeys as $storedKey) {
            if (password_verify($apiKey, $storedKey['api_key'])) {
                return true;
            }
        }
        return false;
    }
}
