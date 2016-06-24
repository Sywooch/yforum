<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/29
 * Time: 下午8:23
 */
namespace common\service;
use frontend\modules\topic\models\Topic;
use Yii;
use common\service\UserService;
class TopicService extends PostService
{
    /**
     * 恢复删除的帖子
     * @param Topic $topic
     */
    public static function revoke(Topic $topic)
    {
//        $topic->status=Topic::STATUS_ACTIVE;
        $topic->setAttribute('status',Topic::STATUS_ACTIVE);
        $topic->save();
    }

    /**
     * 加精华
     * @param Topic $topic
     */
    public static function excellent(Topic $topic)
    {
        $status=($topic->status==Topic::STATUS_ACTIVE)?Topic::STATUS_EXCELLENT:Topic::STATUS_ACTIVE;
        $topic->status=$status;
        $topic->save();
    }
    public function userDoAction($id,$action)
    {
        $user=Yii::$app->user->identity;
        $topic=Topic::findTopic($id);
        if(in_array($action,['hate','like']))
        {
            return UserService::topicActionA($user,$topic,$action);
        }
        else
        {
            return UserService::topicActionB($user,$topic,$action);
        }
    }

}