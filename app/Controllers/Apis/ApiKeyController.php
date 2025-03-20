<?php
namespace App\Controllers\Apis;
use App\Controllers\Apis\CommonController;
use App\Services\ApiKeyService;
class ApiKeyController extends CommonController
{
    protected ApiKeyService $apiKeyService;
    public function __construct()
    {
        $this->apiKeyService = new ApiKeyService();
    }
    public function login()
    {
        $json_data = $this->request->getJSON();
        // print_r($json_data);
        $userLogin = $this->apiKeyService->api_login($json_data);
        return $userLogin;
    }


}