<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class ProjectsController extends BaseController
{
    public function index()
    {
        return view('admin/projects/index');
    }
}
