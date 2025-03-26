<?php

namespace App\Services;

use App\Models\UsersModel;
use CodeIgniter\Validation\Validation;
use config\Services;
use App\Services\CommonService;

use App\Libraries\LoggerService;
class UserService
{
    protected UsersModel $userModel;
    protected Validation $validation;
    protected CommonService $commonService;
    public function __construct()
    {
        helper('response');
        helper('file_upload');
        helper('checkvalidation');
        $this->userModel = new UsersModel();
        $this->validation = Services::validation();
        $this->commonService = new CommonService();

    }

    public function getAllUsers()
    {
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();
        $data = array_map(fn($user) => [
            'id' => $user['id'] ?? "",
            'user_id' => $user['user_id'] ?? '',
            'name' => $user['name'] ?? 'Unknown',
            'email' => $user['email'] ?? 'No Email',
            'created_at' => $user['created_at'] ?? null,
            'status' => $user['status']
        ], $users);
        return $data;
    }
    public function getUserById($id)
    {

        $user = $this->userModel->find($id);

        if (!$user) {
            return null;
        }

        return [
            'id' => $user['id'],
            'user_id' => $user['user_id'],
            'full_name' => $user['name'],
            'email' => $user['email'],
            'status' => 'active'
        ];

    }
    public function createUser($data)
    {
        $rules = [
            "name" => "required|min_length[3]|max_length[50]",
            "email" => "required|valid_email",
            "password" => "required",
            "address" => "required",
        ];

        if (!$this->validation->setRules($rules)->run($data)) {
            return [
                "status" => "failed",
                "statusCode" => 400,
                "message" => "Validation Error",
                "errors" => $this->validation->getErrors(),
                "user" => []
            ];
        }

        try {

            $data["user_id"] = $this->generateUserId();
            $data["password"] = password_hash($data['password'], PASSWORD_DEFAULT);
            $this->userModel->insert($data);
            return [
                "status" => "success",
                "statusCode" => 201,
                "message" => "User created successfully",
                "errors" => [],
                "user" => $data
            ];
        } catch (\Exception $e) {
            log_message('error', date('Y-m-d H:i:s') . ' - Database Error: ' . $e->getMessage());


            return [
                "status" => "failed",
                "statusCode" => 500,
                "message" => "Database Error",
                "errors" => [$e->getMessage()],
                "user" => []
            ];
        }
    }
    private function generateUserId(): string
    {
        return "USER-" . strtoupper(bin2hex(random_bytes(4))); // Example: USER-8F4A3C9D
    }
    public function updateUser($id, $user)
    {
        $rules = [
            "name" => "required|min_length[3]|max_length[50]",
            "email" => "required|valid_email"
        ];

        if (!$this->validation->setRules($rules)->run($user)) {
            return [
                "status" => "failed",
                "statusCode" => 400,
                "message" => "Validation Error",
                "errors" => $this->validation->getErrors(),
                "user" => []
            ];
        }
        try {
            $this->userModel->set($user)->where('id', $id)->update();
            return [
                "status" => "success",
                "statusCode" => 201,
                "message" => "User updated successfully",
                "errors" => [],
                "user" => $user
            ];
        } catch (\Exception $e) {
            log_message('error', date('Y-m-d H:i:s') . ' - Database Error: ' . $e->getMessage());


            return [
                "status" => "failed",
                "statusCode" => 500,
                "message" => "Database Error",
                "errors" => [$e->getMessage()],
                "user" => []
            ];
        }
    }

    public function deleteUser($id)
    {
        $deleteUser = $this->userModel->where('id', $id)->delete();
        return $deleteUser;
    }

    public function changeUserStatus($id, $status)
    {

        $rules = [
            'id' => 'required',
            'status' => 'required'
        ];

        if (
            !$this->validation->setRules($rules)->run(
                ['id' => $id, 'status' => $status]
            )
        ) {
            return [
                "status" => false,
                "statusCode" => 400,
                "message" => "Validation Error",
                "errors" => $this->validation->getErrors(),
                "user" => []
            ];
        }

        try {
            $this->userModel->set(['status' => $status])->where('id', $id)->update();
            return [
                "status" => true,
                "statusCode" => 200,
                "message" => "User status updated successfully",
                "errors" => [],
                "user" => ["id" => $id, "status" => $status]
            ];
        } catch (\Exception $e) {
            print_r($e->getMessage());
            log_message('error', date('Y-m-d H:i:s') . ' - Database Error: ' . $e->getMessage());
            return [
                "status" => false,
                "statusCode" => 500,
                "message" => "Internal Server Error",
                "errors" => ["exception" => $e->getMessage()],
                "user" => []
            ];
        }
    }

    public function deleteStatusUser($id)
    {
        $deleteUserStatus = ENUM_STATUS_DELETED;
        $deleteUser = $this->userModel->set('status', $deleteUserStatus)
            ->where('id', $id)->update();
        if (!empty($deleteUser)) {
            return sendResponse(
                true,
                200,
                "User deleted successfully",
                'user',
                [],
                []
            );
        }
    }

    public function loginCleanCode($jsonData)
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
        $this->validation->setRules($rules);

        if (!$this->validation->run($jsonData)) {
            return sendResponse(
                false,
                400,
                "Validation failed",
                "user",
                [],
                $this->validation->getErrors()
            );
        }

        // Capture user details
        $email = $jsonData['email'];
        $password = $jsonData['password'];
        $ip = Services::request()->getIPAddress();
        $userAgent = Services::request()->getUserAgent()->getAgentString();
        $file = "users";
        $logger = LoggerService::getLogger('login');


        // Log login attempt with user IP and device info
        $logger->info("Login attempt", [
            'email' => $email,
            'ip' => $ip,
            'device' => $userAgent
        ]);

        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            $logger->warning("Failed login - User not found", [
                'email' => $email,
                'ip' => $ip,
                'device' => $userAgent
            ]);
            return sendResponse(
                false,
                404,
                "User not found",
                "user",
                [],
                ['email' => 'No account found with this email']
            );
        }

        if ($password !== $user['password']) {
            $logger->warning("Failed login - Incorrect password", [
                'email' => $email,
                'ip' => $ip,
                'device' => $userAgent
            ]);
            return sendResponse(
                false,
                401,
                "Invalid credentials",
                "user",
                [],
                ['password' => 'Incorrect password']
            );
        }

        // Generate token
        $token = bin2hex(random_bytes(32));

        $afterLoginPayload = [
            'id' => $user['id'],
            'email' => $user['email'],
            'token' => $token,
            'apiKey' => $token
        ];

        $logger->info("Login successful", [
            'email' => $email,
            'ip' => $ip,
            'device' => $userAgent
        ]);

        return sendResponse(
            true,
            200,
            "Login successful",
            "user",
            $afterLoginPayload
        );
    }

    public function login($jsonData)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
        if (!$this->validation->setRules($rules)->run($jsonData)) {
            return [
                "status" => "failed",
                "statusCode" => 400,
                "message" => "Validation Error",
                "errors" => $this->validation->getErrors(),
                "user" => []
            ];
        }
        $emailConditions = [
            'email' => $jsonData['email'],
            'status' => ENUM_STATUS_ACTIVE
        ];
        $user = $this->userModel->where($emailConditions)->first();
        if (empty($user)) {
            sendResponse(
                false,
                404,
                "User not found",
                'user',
                [],
                []
            );
        }
        if (
            $user['password'] === $jsonData['password']
            &&
            $user['email'] === $jsonData['email']
        ) {
            $token = '1245';
            $apiKey = 'API-KEY-';
            return sendResponse(
                true,
                200,
                "User logged in successfully",
                'user',
                [
                    'id' => $user['id'],
                    'user_id' => $user['user_id'],
                    'full_name' => $user['name'],
                    'email' => $user['email'],
                    'jwtToken' => $token,
                    'apiKey' => $apiKey,
                ],
                []
            );
        } else {
            return sendResponse(
                false,
                401,
                "Invalid credentials",
                'user',
                [],
                []
            );
        }
    }


    public function handleUserFileUpload($file, $data)
    {
        $rules = [
            'email' => 'required',
            'name' => 'required',
            'phone' => 'required'
        ];
        $this->validation->setRules($rules);
        if (!$this->validation->run($data)) {
            return sendResponse(
                false,
                400,
                "Validation failed",
                "user",
                [],
                $this->validation->getErrors()
            );
        }
        $user_id = uniqid('user_');
        $filePath = uploadFile($file, 'users', $user_id);
        if ($filePath) {
            $userData = [
                'user_id' => $user_id,
                'name' => $data['name'] ?? '',
                'email' => $data['email'] ?? '',
                'image' => $filePath,
            ];


            $this->userModel->insert($userData);
            // $insertedId = $this->userModel->insertID();

            return [
                'status' => 'success',
                'message' => 'File uploaded successfully',
                'user' => $userData, // Return full user data
                'filePath' => base_url($filePath),
            ];
        }

        return [
            'status' => 'error',
            'message' => 'File upload failed',
        ];
    }
}
