<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Createusertable extends Migration
{
    public function up()
    {
         $this->forge->addField([
          'id'=>[
            'type'=>'INT',
            'constraint'=>6,
            'auto_increment'=>true,
            'unsigned'=>true,
          ],
          'username'=>[
            'type'=>'VARCHAR',
            'constraint'=>50,
          ],
          'email'=>[
            'type'=>'VARCHAR',
            'constraint'=>256,
            'unique'=> true, 
          ],
          'password'=>[
            'type'=>'VARCHAR',
            'constraint'=>256,
          ],
          'phone_number'=>[
            'type'=>'VARCHAR',
            'constraint'=>20,
          ],
          'gender'=>[
            'type'=>'ENUM',
            'constraint'=>['male','female','other'],
          ],
         'user_type'=>[
            'type'=>'ENUM',
            'constraint'=>['user','admin'],
            'default'=>'user',
          ],
          'profile_pic'=>[
            'type'=>'VARCHAR',
            'constraint'=>256,
            'null'=>true,
          ],
          'last_seen' => [
                'type' => 'DATETIME',
                'null' => true,
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

         $this->forge->addKey('id',true);
         $this->forge->createTable('users');  
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
