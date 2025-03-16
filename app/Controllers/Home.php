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
        helper('new');
        echo sabadha('Hello World');

    }
}
