<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/6/1
 * Time: 下午6:40
 */
namespace common\service;
use DevGroup\TagDependencyHelper\NamingHelper;
use Yii;
use common\models\User;
use frontend\modules\topic\models\Topic;
use common\models\PostComment;
use common\models\Post;
use frontend\modules\user\models\UserMeta;
use common\models\UserInfo;
use yii\caching\TagDependency;

class UserService
{
    public static function topicActionA(User $user,Topic $topic,$action)
    {
        return self::toggleType($user, $topic, $action);
    }
    public static function topicActionB(User $user,Post $model,$action)
    {
        $data = [
            'target_id' => $model->id,
            'target_type' => $model->type,
            'user_id' => $user->id,
            'value' => '1',
        ];
        if (!UserMeta::deleteOne($data + ['type' => $action])) { // 删除数据有行数则代表有数据,无行数则添加数据
            $userMeta = new UserMeta();
            $userMeta->setAttributes($data + ['type' => $action]);
            $result = $userMeta->save();
            if ($result) {
                $model->updateCounters([$action . '_count' => 1]);
                if ($action == 'thanks') {
                    UserInfo::updateAllCounters([$action . '_count' => 1], ['user_id' => $model->user_id]);
                }
            }
            return [$result, $userMeta];
        }
        $model->updateCounters([$action . '_count' => -1]);
        if ($action == 'thanks') {
            UserInfo::updateAllCounters([$action . '_count' => -1], ['user_id' => $model->user_id]);
        }

        return [true, null];
    }
    public static function toggleType(User $user, Post $model, $action)
    {
        $data = [
            'target_id' => $model->id,
            'target_type' => $model->type,
            'user_id' => $user->id,
            'value' => '1',
        ];
        if (!UserMeta::deleteOne($data + ['type' => $action])) { // 删除数据有行数则代表有数据,无行数则添加数据
            $userMeta = new UserMeta();
            $userMeta->setAttributes($data + ['type' => $action]);
            $result = $userMeta->save();
            if ($result) { // 如果是新增数据, 删除掉Hate的同类型数据
                $attributeName = ($action == 'like' ? 'hate' : 'like');
                $attributes = [$action . '_count' => 1];
                if (UserMeta::deleteOne($data + ['type' => $attributeName])) { // 如果有删除hate数据, hate_count也要-1
                    $attributes[$attributeName . '_count'] = -1;
                }
                //更新版块统计
                $model->updateCounters($attributes);
                // 更新个人总统计
                UserInfo::updateAllCounters($attributes, ['user_id' => $model->user_id]);
            }
            return [$result, $userMeta];
        }
        $model->updateCounters([$action . '_count' => -1]);
        UserInfo::updateAllCounters([$action . '_count' => -1], ['user_id' => $model->user_id]);

        return [true, null];
    }
    public static function commentAction(User $user,PostComment $comment,$action)
    {
        $data = [
            'target_id' => $comment->id,
            'target_type' => $comment::TYPE,
            'user_id' => $user->id,
            'value' => '1',
        ];
        if (!UserMeta::deleteOne($data + ['type' => $action])) { // 删除数据有行数则代表有数据,无行数则添加数据
            $userMeta = new UserMeta();
            $userMeta->setAttributes($data + ['type' => $action]);
            $result = $userMeta->save();
            if ($result) {
                $comment->updateCounters([$action . '_count' => 1]);
                // 更新个人总统计
                UserInfo::updateAllCounters([$action . '_count' => 1], ['user_id' => $comment->user_id]);
            }
            return [$result, $userMeta];
        }
        $comment->updateCounters([$action . '_count' => -1]);
        // 更新个人总统计
        UserInfo::updateAllCounters([$action . '_count' => -1], ['user_id' => $comment->user_id]);
        return [true, null];
    }

    public static function findActiveUser($limit=12)
    {
        $cacheKey=md5(__METHOD__.$limit);
        if(false===$items=Yii::$app->cache->get($cacheKey))
        {
            $items=User::find()
                ->joinWith(['merit','userInfo'])
                ->where([User::tableName().'.status'=>10])
                ->orderBy(['merit'=>SORT_DESC,'(like_count+thanks_count)'=>SORT_DESC])
                ->limit($limit)
                ->all();
            //添加缓存
            Yii::$app->cache->set($cacheKey,$items,3600*24,new TagDependency([
                'tags' => [NamingHelper::getCommonTag(User::className())]
            ]));
        }
        return $items;
    }

}