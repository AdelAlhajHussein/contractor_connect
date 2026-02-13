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


}
