<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContractorSpecialties extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'contractor_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'specialty_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);

        $this->forge->addForeignKey('contractor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('specialty_id', 'specialties', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('contractor_specialties');
    }

    public function down()
    {
        $this->forge->dropTable('contractor_specialties');
    }
}
