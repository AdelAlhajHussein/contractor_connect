<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHomeownerProfiles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'home_owner_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'province' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'postal_code' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        // Setting home_owner_id as the Primary Key
        $this->forge->addKey('home_owner_id', true);
        $this->forge->createTable('home_owner_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('home_owner_profiles');
    }
}
