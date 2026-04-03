<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContractorProfiles extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id'=>[
                'type'=>'BIGINT',
                'constraint'=>20,
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'contractor_id'=>[
                'type'=>'BIGINT',
                'constraint'=>20,
                'unsigned'=>true,
            ],
            'address'=>[
                'type'=>'VARCHAR',
                'constraint'=>255,
                'null'=>true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'province'=>[
                'type'=>'VARCHAR',
                'constraint'=>100,
                'null'=>false,
            ],
            'postal_code'=>[
                'type'=>'VARCHAR',
                'constraint'=>20,
                'null'=>true,
            ],
            'approval_status'=>[
                'type'=>'VARCHAR',
                'constraint'=>20,
                'default' => 'pending',
                'null'=>true,
            ],
            'created_at'=>[
                'type'=>'DATETIME',
                'null'=>true,
            ],
            'updated_at'=>[
                'type'=>'DATETIME',
                'null'=>true,
            ],
        ]);

        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('contractor_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contractor_profiles');
    }

    public function down()
    {
        //
        $this->forge->dropTable('contractor_profiles');
    }
}
