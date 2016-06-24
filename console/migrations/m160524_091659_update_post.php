<?php

use common\components\db\Migration;
use yii\db\Schema;

class m160524_091659_update_post extends Migration
{
    public function up()
    {
        $this->addColumn('{{%post_comment}}', 'updated_at', Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间'");
        $this->addColumn('{{%post_comment}}', 'like_count', Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '喜欢数' AFTER `user_id`");
        $this->addColumn('{{%post_meta}}', 'alias' , Schema::TYPE_STRING . "(32) DEFAULT NULL COMMENT '变量（别名）' AFTER `name`");
        $this->createIndex('alias', '{{%post_meta}}', 'alias');
        $this->addColumn('{{%post}}', 'follow_count' , Schema::TYPE_INTEGER . " UNSIGNED NOT NULL DEFAULT '0' COMMENT '讨厌数' AFTER `view_count`");

        $this->addColumn('{{%post_meta}}', 'parent', Schema::TYPE_INTEGER . " UNSIGNED DEFAULT NULL COMMENT '父级ID' AFTER `name`");
        $this->addColumn('{{%post}}', 'last_comment_username' , Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT "最后评论用户" AFTER `tags`');
        $this->addColumn('{{%post}}', 'last_comment_time' , Schema::TYPE_INTEGER . ' DEFAULT NULL COMMENT "最后评论用户" AFTER `tags`');

        $this->addColumn('{{%post}}', 'type' , Schema::TYPE_STRING . '(32) DEFAULT "blog" COMMENT "内容类型" AFTER `id`');

    }

    public function down()
    {
        echo "m160524_091659_update_post cannot be reverted.\n";
        $this->dropColumn('{{%post_comment}}', 'updated_at');
        $this->dropColumn('{{%post_comment}}', 'like_count');
        $this->dropColumn('{{%post_meta}}', 'alias');
        $this->dropColumn('{{%post}}', 'follow_count');
        $this->dropColumn('{{%post_meta}}', 'parent');
        $this->dropColumn('{{%post}}', 'last_comment_time');
        $this->dropColumn('{{%post}}', 'last_comment_username');
        return false;
    }
    
}
