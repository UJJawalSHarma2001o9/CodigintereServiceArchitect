<?php

namespace App\Controllers;

class Home extends BaseController
{
    // public function __construct(){
    //     helper('testHelper');
    // }
    public function index(): string
    {
        return view('welcome_message');
    }
    public function practice_helper()
    {

        helper('response');
        print_r(sendResponse(true, 200, 'Get users list successfully', 'users', [], []));

    }

    public function loginPage()
    {
        return view('login.php');
    }


    public function dashboard()
    {
        return view('dashboard.php');
    }
}
