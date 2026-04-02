<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContractorRatingModel;

class RatingsController extends BaseController
{
    public function index()
    {
        $q = trim((string) $this->request->getGet('q'));
        $score = trim((string) $this->request->getGet('score')); // 1..5 (optional)

        $model = new ContractorRatingModel();

        $builder = $model->builder()
            ->select("
                contractor_ratings.*,
                projects.title AS project_title,
                c.email AS contractor_email,
                h.email AS homeowner_email,
                ROUND((quality + timeliness + communication + pricing) / 4, 2) AS avg_score
            ")
            ->join('projects', 'projects.id = contractor_ratings.project_id', 'left')
            ->join('users c', 'c.id = contractor_ratings.contractor_id', 'left')
            ->join('users h', 'h.id = contractor_ratings.home_owner_id', 'left')
            ->groupBy('contractor_ratings.id')
            ->orderBy('contractor_ratings.created_at', 'DESC');

        // text search
        if ($q !== '') {
            $builder->groupStart()
                ->like('projects.title', $q)
                ->orLike('c.email', $q)
                ->orLike('h.email', $q)
                ->groupEnd();
        }

        // average score filter (approx)
        if ($score !== '' && ctype_digit($score)) {
            $s = (int) $score;
            if ($s >= 1 && $s <= 5) {
                $builder->having('avg_score >=', $s)
                    ->having('avg_score <', $s + 1);
            }
        }

        $data = [
            'q' => $q,
            'score' => $score,
            'ratings' => $builder->get()->getResultArray(),
        ];

        return view('admin/ratings/index', $data);
    }

    public function view($id)
    {
        $model = new ContractorRatingModel();

        $rating = $model->builder()
            ->select("
                contractor_ratings.*,
                projects.title AS project_title,
                c.email AS contractor_email,
                h.email AS homeowner_email,
                ROUND((quality + timeliness + communication + pricing) / 4, 2) AS avg_score
            ")
            ->join('projects', 'projects.id = contractor_ratings.project_id', 'left')
            ->join('users c', 'c.id = contractor_ratings.contractor_id', 'left')
            ->join('users h', 'h.id = contractor_ratings.home_owner_id', 'left')
            ->where('contractor_ratings.id', (int)$id)
            ->get()
            ->getRowArray();

        if (!$rating) {
            return redirect()->to(site_url('admin/ratings'));
        }

        return view('admin/ratings/view', ['rating' => $rating]);
    }

    public function remove($id)
    {
        // No "review" table + no soft delete columns in contractor_ratings,
        // so moderation = hard delete for now.
        $model = new ContractorRatingModel();
        $model->delete((int)$id);

        return redirect()->to(site_url('admin/ratings'));
    }

    public function suspicious()
    {
        $db = db_connect();

        // 1) Same Homeowner rating same contractor multiple times
        $repeatPairs = $db->table('contractor_ratings cr')
            ->select('cr.contractor_id, c.email AS contractor_email, cr.home_owner_id, h.email AS homeowner_email, COUNT(*) AS rating_count')
            ->join('users c', 'c.id = cr.contractor_id', 'left')
            ->join('users h', 'h.id = cr.home_owner_id', 'left')
            ->groupBy('cr.contractor_id, cr.home_owner_id')
            ->having('rating_count >', 1)
            ->orderBy('rating_count', 'DESC')
            ->get()
            ->getResultArray();

        // 2) Many ratings in last 7 days per contractor
        $recentBurst = $db->table('contractor_ratings cr')
            ->select('cr.contractor_id, c.email AS contractor_email, COUNT(*) AS last_7_days')
            ->join('users c', 'c.id = cr.contractor_id', 'left')
            ->where('cr.created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->groupBy('cr.contractor_id')
            ->having('last_7_days >=', 3)
            ->orderBy('last_7_days', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/ratings/suspicious', [
            'repeatPairs' => $repeatPairs,
            'recentBurst' => $recentBurst,
        ]);
    }
}
