<?php

namespace App\Services;
use App\Models\ApiKeyModel;
use App\Models\UsersModel;
use CodeIgniter\Validation\Validation;
use Config\Services;
class ApiKeyService
{
    protected ApiKeyModel $ApiKeyModel;
    protected UsersModel $usersModel;
    protected $validation;
    public function __construct()
    {
        $this->ApiKeyModel = new ApiKeyModel();
        $this->usersModel = new UsersModel();
        $this->validation = Services::validation();
        helper('response');
        helper('api_key');
        helper('checkvalidation');
    }

    public function api_login($loginData)
    {
        // $rules = [
        //     'email' => 'required|valid_email',
        //     'password' => 'required'
        // ];
        // $this->validation->setRules($rules);
        // if (!$this->validation->run($loginData)) {
        //     return sendResponse(
        //         false,
        //         400,
        //         "Validation failed",
        //         "user",
        //         [],
        //         $this->validation->getErrors()
        //     );
        // }
        $email = $loginData->email;
        // print $email ; die ;
        // $password = $loginData['password'];
        // Fetch user details
        $user = $this->usersModel->where('email', $email)->first();
        var_dump($user);
        die;
        if (!$user || !password_verify($password, $user->password)) {
            return sendResponse(
                false,
                401,
                "Invalid email or password",
                "user",
                [],
                []
            );
        }

        return sendResponse(
            true,
            200,
            "Login successful",
            "user",
            ["user" => $user],
            []
        );
    }

}