<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBidTasksTable extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'bid_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'est_minutes' => ['type' => 'INT',
                'constraint' => 11
            ],
            'materials_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'labour_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'hst_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ],
            'task_order' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('bid_tasks');
    }

    public function down()
    {
        //
        $this->forge->dropTable('bid_tasks');
    }
}
