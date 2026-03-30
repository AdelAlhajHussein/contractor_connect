<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;

class ProjectsController extends BaseController
{
    public function index()
    {
        $homeownerId = (int) session('user_id');

        $projects = db_connect()->table('projects')
            ->select('id, title, status, created_at')
            ->where('home_owner_id', $homeownerId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('homeowner/projects/index', [
            'projects' => $projects,
        ]);
    }

    public function details($id)
    {
        $userId = (int) session()->get('user_id');

        $projectModel = new \App\Models\ProjectModel();

        $project = $projectModel
            ->where('id', (int)$id)
            ->where('home_owner_id', $userId)
            ->first();

        if (!$project) {
            dd([
                'message' => 'PROJECT NOT FOUND for this homeowner',
                'id' => (int)$id,
                'session_user_id' => $userId,
                'sample_query_check' => 'SELECT * FROM projects WHERE id = ? AND home_owner_id = ?',
            ]);
        }

        return view('homeowner/projects/details', [
            'project' => $project
        ]);
    }

    public function new()
    {
        $categories = db_connect()->table('categories')
            ->select('id, name')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        return view('homeowner/projects/new', [
            'categories' => $categories,
        ]);
    }


    public function create()
    {
        $userId = (int) session()->get('user_id');

        $projectModel = new \App\Models\ProjectModel();

        $projectModel->insert([
            'home_owner_id' => $userId,
            'category_id'   => (int) $this->request->getPost('category_id'),
            'title'         => $this->request->getPost('title'),
            'description'   => $this->request->getPost('description'),
            'budget_min'    => $this->request->getPost('budget_min'),
            'budget_max'    => $this->request->getPost('budget_max'),
            'status'        => 'bidding_open',
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/index.php/homeowner/projects');
    }




}
