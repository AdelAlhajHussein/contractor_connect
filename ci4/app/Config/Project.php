<?php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Project extends BaseConfig{

    public array $valid_status =  ['open', 'in-progress', 'closed', 'cancelled', 'completed'];
}

?>