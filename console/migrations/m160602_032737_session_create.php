<?php

use yii\db\Migration;
use yii\db\Schema;
class m160602_032737_session_create extends \common\components\db\Migration
{
    public $tableName = '{{%session}}';
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => "varchar(40) NOT NULL",
            'expire' => "int(11)",
            'data' => "blob",
        ]);
        $this->addPrimaryKey('idx', $this->tableName, 'id');
        $this->createIndex('idx_expire', $this->tableName, 'expire');
    }

    public function down()
    {
        echo "m160602_032737_session_create cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
