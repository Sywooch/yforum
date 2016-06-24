<?php

use common\components\db\Migration;
use yii\db\Schema;
class m160523_122509_update_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_info}}', 'location' , Schema::TYPE_STRING . '(10) DEFAULT NULL COMMENT "城市" AFTER `info`');
        $this->addColumn('{{%user_info}}', 'company' , Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT "公司" AFTER `info`');
        $this->addColumn('{{%user_info}}', 'website' , Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT "个人主页" AFTER `info`');
        $this->addColumn('{{%user_info}}', 'github' , Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT "GitHub 帐号" AFTER `info`');
        $this->addColumn('{{%user}}', 'tagline' , Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT "一句话介绍" AFTER `email`');
        $this->addColumn('{{%user_info}}', 'like_count' , Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "被赞次数" AFTER `location`');
        $this->addColumn('{{%user_info}}', 'thanks_count' , Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "被感谢次数" AFTER `location`');
        $this->addColumn('{{%user_info}}', 'post_count' , Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "发布文章数" AFTER `location`');
        $this->addColumn('{{%user_info}}', 'comment_count' , Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "发布评论数" AFTER `location`');
        $this->addColumn('{{%user_info}}', 'view_count' , Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "个人主页浏览次数" AFTER `location`');
        $this->addColumn('{{%user_info}}', 'hate_count' , Schema::TYPE_INTEGER . ' DEFAULT 0 COMMENT "喝倒彩次数" AFTER `like_count`');

    }

    public function down()
    {
        echo "m160523_122509_update_user cannot be reverted.\n";
        $this->dropColumn('{{%user_info}}', 'location');
        $this->dropColumn('{{%user_info}}', 'company');
        $this->dropColumn('{{%user_info}}', 'website');
        $this->dropColumn('{{%user_info}}', 'github');
        $this->dropColumn('{{%user}}', 'tagline');
        $this->dropColumn('{{%user_info}}', 'like_count');
        $this->dropColumn('{{%user_info}}', 'thanks_count');
        $this->dropColumn('{{%user_info}}', 'post_count');
        $this->dropColumn('{{%user_info}}', 'comment_count');
        $this->dropColumn('{{%user_info}}', 'view_count');
        $this->dropColumn('{{%user_info}}', 'hate_count');
        return false;
    }
    
}
