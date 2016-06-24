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
//        用户登录验证表
        $tableName = '{{%user_account}}';
        $this->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . " UNSIGNED DEFAULT NULL COMMENT '用户ID'",
            'provider' => Schema::TYPE_STRING . "(100) NOT NULL DEFAULT '' COMMENT '授权提供商'",
            'client_id' => Schema::TYPE_STRING . " NOT NULL",
            'data' => Schema::TYPE_TEXT . " NOT NULL",
            'created_at' => Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'",
        ], $this->tableOptions);
        $this->createIndex('client_id', $tableName, 'client_id');
        $this->createIndex('user_id', $tableName, 'user_id');
//        用户信息
        $this->createTable('{{%user_info}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'info' => Schema::TYPE_STRING . ' DEFAULT NULL COMMENT "会员简介"',
            'login_count' => Schema::TYPE_INTEGER . ' DEFAULT 1 COMMENT "登录次数"',
            'prev_login_time' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL COMMENT "上次登录时间"',
            'prev_login_ip' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT "上次登录IP"',
            'last_login_time' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL COMMENT "最后登录时间"',
            'last_login_ip' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT "最后登录IP"',
            'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $this->tableOptions);

    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m160515_155842_create_user cannot be reverted.\n";
        $this->dropColumn('{{%user}}', 'avatar');
        $this->dropTable('{{%user_meta}}');
        $this->dropTable('{{%user_account}}');
        $this->dropTable('{{%user_info}}');
    }
}
