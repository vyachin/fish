<?php

namespace market\console\migrations;

use yii\db\Migration;

/**
 * Class M220818062250CreateTableUser
 */
class M220818062250CreateTableUser extends Migration
{
    public function safeUp()
    {
        $this->createTable(
            'user',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'email' => $this->string()->notNull(),
                'password_hash' => $this->char(60)->notNull(),
                'auth_key' => $this->char(64)->notNull(),
                'status' => $this->tinyInteger()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'created_ip' => $this->integer()->unsigned(),
                'created_by' => $this->integer(),
                'updated_at' => $this->dateTime()->notNull(),
                'updated_ip' => $this->integer()->unsigned(),
                'updated_by' => $this->integer(),
            ],
            'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}
