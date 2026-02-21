<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,

            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                null => false,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,

            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'password_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'role_id' =>[
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => '1', // default to admin?
            ],
            'is_active' =>[
                'type' => 'INT',
                'constraint' => 11,
                'default' => '1', // default active
            ],


            // timestamps
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        // Primary key
        $this->forge->addKey('user_id', true);

        // Build table
        $this->forge->createTable('users');

    }

    public function down()
    {
        // Undo migration
        $this->forge->dropTable('users');
    }
}
