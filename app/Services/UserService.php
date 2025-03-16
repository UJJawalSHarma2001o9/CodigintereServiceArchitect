<?php

namespace App\Services;

use App\Models\UsersModel;
use CodeIgniter\Validation\Validation;
use config\Services;
class UserService
{
    protected UsersModel $userModel;
    protected Validation $validation;

    public function __construct()
    {
        $this->userModel = new UsersModel();
        $this->validation = Services::validation();

    }

    public function getAllUsers()
    {
        $users = $this->userModel->orderBy('created_at', 'DESC')->findAll();
        $data = [];
        // foreach ($users as $user) {
        //     $data[] = [
        //         'user_id' => $user['id'],
        //         'name' => $user['name'],
        //         'email' => $user['email'],
        //         'created_at' => $user['created_at']
        //     ];
        // }
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
            return null; // Handle not found in controller
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
            "email" => "required|valid_email"
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

        if (!$this->validation->setRules($rules)->run(['id' => $id, 'status' => $status])) {
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
            print_r($e->getMessage()) ; 
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


    // public function changeUserStatus($id, $status)
    // {
    //     $rules = [
    //         'id' => 'required',
    //         'status' => 'required'
    //     ];

    //     if (!$this->validation->setRules($rules)->run($rules)) {
    //         return [
    //             "status" => "failed",
    //             "statusCode" => 400,
    //             "message" => "Validation Error",
    //             "errors" => $this->validation->getErrors(),
    //             "user" => []
    //         ];
    //     }

    //     try {
    //         $this->userModel->set($status)->where('id', $id)->update();
    //         return [
    //             "status" => "success",
    //             "statusCode" => 200,
    //             "message" => "User status updated successfully",
    //             "errors" => [],
    //             "user" => $status
    //         ];
    //     } catch (\Exception $e) {
    //         log_message('error', date('Y-m-d H:i:s') . ' - Database Error: ' . $e->getMessage());
    //     }
    // }
}
