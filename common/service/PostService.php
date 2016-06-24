<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/29
 * Time: 下午8:06
 */
namespace common\service;
use common\models\Post;
class PostService
{
    /**
     * 删除文章
     * @param Post $post
     */
    public static function delete(Post $post)
    {
        $post->setAttribute('status',Post::STATUS_DELETED);
        $post->save();
//        Notification::updateAll(['status' => Post::STATUS_DELETED], ['post_id' => $post->id]);
    }
}