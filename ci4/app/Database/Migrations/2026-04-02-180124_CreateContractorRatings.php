<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContractorRatings extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'project_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'contractor_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'home_owner_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],

            'quality' => ['type' => 'INT', 'constraint' => 1],
            'timeliness' => ['type' => 'INT', 'constraint' => 1],
            'communication' => ['type' => 'INT', 'constraint' => 1],
            'pricing' => ['type' => 'INT', 'constraint' => 1],
            'comment' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('project_id');
        $this->forge->addKey('contractor_id');
        $this->forge->addKey('home_owner_id');

        $this->forge->createTable('contractor_ratings');
    }

    public function down()
    {
        $this->forge->dropTable('contractor_ratings');
    }
}