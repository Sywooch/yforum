<?php

use yii\db\Schema;
use common\components\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m160515_155842_create_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%user}}', 'avatar' , Schema::TYPE_STRING . " DEFAULT NULL COMMENT '头像' AFTER `username` ");
        $tableName = '{{%user_meta}}';
        $this->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL COMMENT '用户ID'",
            'type' => Schema::TYPE_STRING . "(100) NOT NULL DEFAULT '' COMMENT '操作类型'",
            'value' => Schema::TYPE_STRING . " NOT NULL DEFAULT '' COMMENT '操作类型值'",
            'target_id' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '目标id'",
            'target_type' => Schema::TYPE_STRING . "(100) NOT NULL DEFAULT '' COMMENT '目标类型'",
            'created_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'",
        ], $this->tableOptions);
        $this->createIndex('type', $tableName, 'type');
        $this->createIndex('user_id', $tableName, 'user_id');
        $this->createIndex('target_id', $tableName, 'target_id');
        $this->createIndex('target_type', $tableName, 'target_type');

//        创建用户登录验证表格
        $tableName = '{{%user_auth}}';
        $this->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL COMMENT '用户ID'",
            'type' => Schema::TYPE_STRING . "(100) NOT NULL DEFAULT '' COMMENT '联合登录类型'",
            'token' => Schema::TYPE_STRING . " NOT NULL",
            'openid' => Schema::TYPE_STRING . " NOT NULL",
            'created_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'",
        ], $this->tableOptions);
        $this->createIndex('type', $tableName, 'type');
        $this->createIndex('user_id', $tableName, 'user_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m160515_155842_create_user cannot be reverted.\n";
        $this->dropColumn('{{%user}}', 'avatar');
        $this->dropTable('{{%user_meta}}');
        $this->dropTable('{{%user_auth}}');
    }
}
