<?php

namespace App\Controllers\Apis;
use App\Controllers\Apis\CommonController;

use App\Services\UserService;
class ClinicController extends CommonController
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function get_users()
    {
        $users = $this->userService->getAllUsers();
        if (!$users) {
            return $this->sendResponse(
                false,
                404,
                'Users not found',
                'users',
                [],
                []
            );
        }
        return $this->sendResponse(
            true,
            200,
            'Get users list successfully',
            'users',
            $users,
            []
        );
    }
}