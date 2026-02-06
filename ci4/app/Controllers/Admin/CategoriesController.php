<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class CategoriesController extends BaseController
{
    public function index()
    {
        return view('admin/categories/index');
    }
}
