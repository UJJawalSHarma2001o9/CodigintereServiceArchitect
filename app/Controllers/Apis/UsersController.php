<?php

namespace App\Controllers\Apis;
use App\Controllers\Apis\CommonController;

use App\Services\UserService;
class UsersController extends CommonController
{
    protected UserService $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function index()
    {
        $users = $this->userService->getAllUsers();
        if (!$users) {
            return $this->sendResponse(false, 404, 'Users not found', 'users', []);
        }
        return $this->sendResponse(true, 200, 'Get users list successfully', 'users', $users);
    }
    public function show($id = null)
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return $this->sendResponse(
                false,
                404,
                'Users not found',
                'user',
                [],
                'Please Write a Valid User Id'
            );

        } else {
            return $this->sendResponse(
                true,
                200,
                'Get user list successfully',
                'user',
                $user,
                [],

            );

        }
    }

    public function create()
    {
        try {
            $json = $this->request->getJSON(true);

            if (!$json) {
                return $this->sendResponse(
                    false,
                    400,
                    "Invalid JSON input",
                    "user",
                    [],
                    "Invalid or missing request body"
                );
            }

            $response = $this->userService->createUser($json);
            return $this->sendResponse(
                $response["status"],
                $response["statusCode"],
                $response["message"],
                'user',
                $response["user"],
                $response["errors"],
            );
        } catch (\Exception $e) {
            log_message('error', 'Exception in create: ' . $e->getMessage()); // Log error
            return $this->sendResponse(
                false,
                500,
                "Internal Server Error",
                'user',
                [],
                [$e->getMessage()],
            );
        }
    }

    public function update($id = null)
    {
        try {
            $user = $this->request->getJSON(true);
            if (!$user) {
                return $this->sendResponse(
                    false,
                    400,
                    "Invalid JSON input",
                    "user",
                    [],
                    "Invalid or missing request body"
                );
            }
            $response = $this->userService->updateUser($id, $user);
            return $this->sendResponse(
                $response["status"],
                $response["statusCode"],
                $response["message"],
                'user',
                $response["user"],
                $response["errors"],
            );
        } catch (\Exception $e) {
            log_message('error', 'Exception in create: ' . $e->getMessage()); // Log error
            return $this->sendResponse(
                false,
                500,
                "Internal Server Error",
                'user',
                [],
                [$e->getMessage()],
            );
        }
    }
    public function delete($id = null)
    {
        $deleteUser = $this->userService->deleteUser($id);
        if (!empty($deleteUser)) {
            return $this->sendResponse(
                true,
                200,
                "User deleted successfully",
                'user',
                [],
                []
            );
        }

    }

    public function changeStatus($id = null)
    {
        // echo "hello" ; die;
        $jsonData = $this->request->getJSON();
        $status = $jsonData->status ?? null;

        if ($status === null) {
            return $this->sendResponse(
                false,
                400,
                "Invalid JSON or missing status field",
                'user',
                [],
                []
            );
        }

        $userIdCheck = $this->userService->getUserById($id);
        if (!empty($userIdCheck)) {
            $userStatus = $this->userService->changeUserStatus($id, $status);
            return $this->sendResponse(
                $userStatus["status"],
                $userStatus["statusCode"],
                $userStatus["message"],
                'user',
                [],
                []
            );
        } else {
            return $this->sendResponse(
                false,
                404,
                "User not found",
                'user',
                [],
                []
            );
        }
    }

    public function StatusDelete($id = null)
    {
        return $this->userService->deleteStatusUser($id);
    }
    public function login()
    {
        $jsonData = $this->request->getJSON(true);
        $userLoginService = $this->userService->loginCleanCode($jsonData);
        return $userLoginService;
    }
    public function userFileupload()
    {
        $file = $this->request->getFile('image');
        $data =  $this->request->getPost();
        if (!$file) {
            return $this->sendResponse(
                false,
                400,
                "No file uploaded",
                'user',
                [],
                "No file uploaded"
            );
        }

        $response = $this->userService->handleUserFileUpload($file, $data);
        // print_r($response) ; die ; 
        return sendResponse(
            $response['status'] === 'success',
            $response['status'] === 'success' ? 200 : 400,
            $response['message'],
            'user',
            $response['status'] === 'success' ? $response['user'] : [], // Return user data if success
            []
        );
    }
}