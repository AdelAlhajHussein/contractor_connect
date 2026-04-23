<?php

namespace App\Controllers\Homeowner;

use App\Controllers\BaseController;
use Config\Database;

class BrowseController extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        $city        = trim((string) $this->request->getGet('city'));
        $province    = trim((string) $this->request->getGet('province'));
        $specialtyId = $this->request->getGet('specialty_id');
        $minRating   = $this->request->getGet('min_rating');

        $builder = $db->table('users u')
            ->select("
                u.id,
                u.first_name,
                u.last_name,
                u.email,
                cp.city,
                cp.province,
                cp.approval_status,
                GROUP_CONCAT(DISTINCT s.name) AS specialties,
                ROUND(AVG((cr.quality + cr.timeliness + cr.communication + cr.pricing) / 4), 2) AS avg_rating,
                COUNT(cr.id) AS rating_count
            ")
            ->join('contractor_profiles cp', 'cp.contractor_id = u.id', 'inner')
            ->join('contractor_specialties cs', 'cs.contractor_id = u.id', 'left')
            ->join('specialties s', 's.id = cs.specialty_id', 'left')
            ->join('contractor_ratings cr', 'cr.contractor_id = u.id', 'left')
            ->where('u.role_id', 2) // contractors
            ->groupBy('u.id');

        // Optional: only approved contractors
        $builder->where('cp.approval_status', 'approved');

        if ($city !== '') {
            $builder->where('cp.city', $city);
        }

        if ($province !== '') {
            $builder->where('cp.province', $province);
        }

        if ($specialtyId !== null && $specialtyId !== '') {
            $builder->where('cs.specialty_id', (int) $specialtyId);
        }

        if ($minRating !== null && $minRating !== '') {
            $builder->having('avg_rating >=', (float) $minRating);
        }

        $contractors = $builder->get()->getResultArray();

        $specialties = $db->table('specialties')->select('id, name')->orderBy('name', 'ASC')->get()->getResultArray();

        return view('homeowner/browse/index', [
            'contractors' => $contractors,
            'specialties' => $specialties,
            'filters' => [
                'city' => $city,
                'province' => $province,
                'specialty_id' => $specialtyId,
                'min_rating' => $minRating,
            ],
        ]);
    }
}
