<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtAuthFilter implements FilterInterface
{
    use ResponseTrait;
    public function before(RequestInterface $request, $arguments = null)
    {
        $cookieToken = $request->getCookie('jwt_token');
        if (!$cookieToken) {
            return Services::response()
                ->setJSON([
                    'status'     => 'failed',
                    'statusCode' => ResponseInterface::HTTP_UNAUTHORIZED,
                    'message'    => 'Missing JWT Token',
                    'errors'     => [],
                    'data'       => []
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        try {
            $decodedToken = JWT::decode($cookieToken, new Key(getenv('JWT_SECRET'), 'HS256'));
            return;
        } catch (\Exception $e) {
            return Services::response()
                ->setJSON([
                    'status'     => 'failed',
                    'statusCode' => ResponseInterface::HTTP_UNAUTHORIZED,
                    'message'    => 'Invalid JWT Token',
                    'errors'     => [$e->getMessage()],
                    'data'       => []
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after request
    }
}
