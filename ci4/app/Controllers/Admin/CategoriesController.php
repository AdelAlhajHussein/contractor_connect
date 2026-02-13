<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoriesController extends BaseController
{
    public function index()
    {
        $model = new CategoryModel();

        $q = trim($this->request->getGet('q') ?? '');
        $visibility = $this->request->getGet('visibility');

        $builder = $model;

        if ($q !== '') {
            $builder = $builder->like('name', $q);
        }

        if ($visibility !== null && $visibility !== '') {
            $builder = $builder->where('is_visible', (int)$visibility);
        }

        $data = [
            'categories' => $builder->orderBy('id', 'DESC')->findAll(),
            'q' => $q,
            'visibility' => $visibility,
        ];

        return view('admin/categories/index', $data);
    }

    public function create()
    {
        return view('admin/categories/create');
    }

    public function store()
    {
        $model = new CategoryModel();

        $model->insert([
            'name' => $this->request->getPost('name'),
            'is_visible' => 1
        ]);

        return redirect()->to(site_url('admin/categories'));
    }

    public function edit($id)
    {
        $model = new CategoryModel();

        $data['category'] = $model->find((int)$id);

        return view('admin/categories/edit', $data);
    }

    public function update($id)
    {
        $model = new CategoryModel();

        $model->update((int)$id, [
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to(site_url('admin/categories'));
    }

    public function delete($id)
    {
        $model = new CategoryModel();
        $model->delete((int)$id);

        return redirect()->to(site_url('admin/categories'));
    }

    public function toggle($id)
    {
        $model = new CategoryModel();

        $category = $model->find((int)$id);

        if ($category) {

            $model->update((int)$id, [
                'is_visible' => $category['is_visible'] ? 0 : 1
            ]);

            audit_log(
                'category_visibility_toggled',
                'category',
                (int)$id,
                'Admin toggled category visibility'
            );
        }

        return redirect()->to(site_url('admin/categories'));
    }

}
