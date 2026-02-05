<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function loginForm()
    {
        return view('auth/login');
    }
}
