<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Project;

class ProjectModel extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'home_owner_id',
        'category_id',
        'title',
        'description',
        'address',
        'contact_phone',
        'start_date',
        'end_date',
        'deadline_date',
        'budget_min',
        'budget_max',
        'status',
        'completed_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';


    protected $validationRules = [

        'home_owner_id' => 'required|is_natural_no_zero',
        'category_id' => 'required|is_natural_no_zero',
        'title' => 'required|min_length[3]|max_length[255]',
        'address' => 'required|min_length[3]|max_length[255]',
        'budget_min' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'budget_max' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'status' => 'required|in_list[open, closed, in_progress, completed, cancelled]',
    ];
    /*
    public function __construct(){

        parent::__construct();

        // Load from config file
        $config = config('Project');

        $status = implode(',', $config->status );

        $this->validationRules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'budget_min' => 'required|numeric',
            'budget_max' => 'required|numeric',
            'status' => "required|in_list[open,closed]",
            'deadline_date' => 'valid_date',
        ];
    }
    */
}
