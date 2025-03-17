<?php

namespace App\Controllers\Apis;
use App\Controllers\Apis\CommonController;

use App\Services\JwtAuthService;
class JwtAuthController extends CommonController
{
    protected JwtAuthService $jwtAuthService;
    public function __construct()
    {
        $this->jwtAuthService = new JwtAuthService();
    }
    public function index()
    {
        //
    }
    public function login()
    {
        $data = $this->request->getJSON(true);
        return $this->jwtAuthService->jwt_token_login($data);
    }
}
