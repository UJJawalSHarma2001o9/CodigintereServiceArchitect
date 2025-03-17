<?php
namespace App\Services;
use App\Models\UsersModel;
use CodeIgniter\Cookie\Cookie;
class JwtAuthService
{
    protected UsersModel $userModel;
    protected $validation;

    public function __construct()
    {
        helper('response');
        helper('jwtauth');
        helper('checkvalidation');
        $this->userModel = new UsersModel();

    }

    public function jwt_token_login($userLoginData)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $validationResult = isCheckValidation($rules, $userLoginData);
        if ($validationResult !== true) {
            return $validationResult; 
        }

        $user = $this->userModel->where('email', $userLoginData['email'])->first();

        if (!$user || !password_verify($userLoginData['password'], $user['password'])) {
            return sendResponse(
                false,
                401,
                "Invalid email or password",
                "user",
                [],
                []
            );
        }
        $token = generateJWT($user['id']);
        $response = service('response');
        $response->setCookie(new Cookie("jwt_token", $token, [
            // 'expires' => time() + 3600,
            'expires' => time() + 30,
            'path' => "/",
            'domain' => "",
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ]));

        return sendResponse(
            true,
            200,
            "Login successful",
            "user",
            [
                'id' => $user['id'],
                'user_id' => $user['user_id'],
                'full_name' => $user['name'],
                'email' => $user['email'],
                'jwt_token' => $token
            ],
            []
        );
    }
}

