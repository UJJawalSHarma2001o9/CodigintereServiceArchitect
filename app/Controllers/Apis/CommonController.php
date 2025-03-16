<?php

namespace App\Controllers\Apis;


use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\RESTful\ResourceController;

class CommonController extends ResourceController
{
    protected $validation;
    protected $current_datetime;
    protected $db;
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->validation = \Config\Services::validation();
        $this->current_datetime = date('Y-m-d H:i:s');
        $this->db = \Config\Database::connect();
    }

    public function sendResponse($status,$httpStatusCode,$message, $dataKey = 'data', $data = [],$errors = []) {
        $response = [
            'status'        => $status ? 'success' : 'failed',
            'statusCode'    => $httpStatusCode,
            'message'       => $message,
            'errors'        => $errors,
            $dataKey        => $data,
        ];
        return $this->respond($response, $httpStatusCode);
    }




    public function GUID($prefix = "")
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        $unique_code = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

        if (!empty($prefix)) {
            return $prefix . "_" . $unique_code . "_" . time();
        } else {
            return $unique_code;
        }
    }


    public function initUsersModel()
    {
        return   new \App\Models\UsersModel();
    }


    function generateApiKey($prefixLength = 5, $keyLength = 32)
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $prefix = '';
        for ($i = 0; $i < $prefixLength; $i++) {
            $prefix .= $characters[random_int(0, strlen($characters) - 1)];
        }

        $randomKey = bin2hex(random_bytes($keyLength / 2));
        return $prefix . '.' . $randomKey;
    }

    function hashApiKeyBcrypt($apiKey)
    {
        return password_hash($apiKey, PASSWORD_BCRYPT);
    }
}
