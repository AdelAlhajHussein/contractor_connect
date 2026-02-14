<?php

namespace App\Controllers;

class Home extends BaseController
{
   /* public function index(): string
    {
        ## return view('welcome_message');
        ##return view('pages/home');
        return "HOME CONTROLLER WORKING";
    }*/
    public function index()
    {
        return view('pages/home');
    }

    public function about()
    {
        return "about is working";
        #return view('pages/about');
    }

}
