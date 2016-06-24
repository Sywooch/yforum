<?php

namespace common\models;
use Yii;
use common\models\User;
use common\components\db\ActiveRecord;
/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $post_meta_id
 * @property integer $user_id
 * @property string $title
 * @property string $author
 * @property string $excerpt
 * @property string $image
 * @property string $content
 * @property string $tags
 * @property integer $last_comment_time
 * @property string $last_comment_username
 * @property integer $view_count
 * @property integer $follow_count
 * @property integer $comment_count
 * @property integer $favorite_count
 * @property integer $like_count
 * @property integer $thanks_count
 * @property integer $hate_count
 * @property integer $status
 * @property integer $order
 * @property integer $created_at
 * @property integer $updated_at
 */
class Post extends ActiveRecord
{
    /**
     * 博客文章
     */
    const TYPE_BLOG = 'blog';

    /**
     * 社区话题
     */
    const TYPE_TOPIC = 'topic';

    /**
     * 置顶
     */
    const STATUS_TOP = 3;

    /**
     * 推荐
     */
    const STATUS_EXCELLENT = 2;

    /**
     * 发布
     */
    const STATUS_ACTIVE = 1;

    /**
     * 删除
     */
    const STATUS_DELETED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_meta_id', 'user_id', 'last_comment_time', 'view_count', 'follow_count', 'comment_count', 'favorite_count', 'like_count', 'thanks_count', 'hate_count', 'status', 'order', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content', 'tags'], 'required'],
            [['content'], 'string'],
            [['title', 'excerpt', 'image', 'tags'], 'string', 'max' => 255],
            [['author'], 'string', 'max' => 100],
            [['last_comment_username'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '内容类型',
            'post_meta_id' => '分类',
            'category_name' => '分类',
            'user_id' => '用户ID',
            'username' => '用户',
            'title' => '标题',
            'author' => '作者',
            'excerpt' => '摘要',
            'image' => '封面图片',
            'content' => '内容',
            'tags' => '标签',
            'last_comment_username' => '最后回复用户',
            'last_comment_time' => '最后评论时间',
            'view_count' => '查看数',
            'comment_count' => '评论数',
            'favorite_count' => '收藏数',
            'like_count' => '喜欢数',
            'thanks_count' => '感谢数',
            'hate_count' => '讨厌数',
            'status' => '状态',
            'order' => '排序',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'cc' => '注明版权信息（原创文章欢迎使用）',
        ];
    }

    /**
     * 获得发表的作者
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**获得分类信息
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(PostMeta::className(), ['id' => 'post_meta_id']);
    }

    /**
     * 当前用户是否是该文章的作者
     * @return bool
     */
    public function isCurrent()
    {
        return $this->user_id == Yii::$app->user->id;
    }
}