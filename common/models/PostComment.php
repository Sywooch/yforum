<?php

namespace common\models;

use common\components\db\ActiveRecord;
use frontend\modules\user\models\UserMeta;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "post_comment".
 *
 * @property integer $id
 * @property integer $parent
 * @property integer $post_id
 * @property string $comment
 * @property integer $status
 * @property integer $user_id
 * @property integer $like_count
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class PostComment extends ActiveRecord
{
    const TYPE = 'comment';
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
        return 'post_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'post_id', 'status', 'user_id', 'like_count', 'created_at', 'updated_at'], 'integer'],
            [['post_id', 'comment', 'user_id', 'ip'], 'required'],
            [['comment'], 'string'],
            [['ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => '父级评论',
            'post_id' => '文章ID',
            'comment' => '评论',
            'status' => '1为正常 0为禁用',
            'user_id' => '用户ID',
            'like_count' => '喜欢数',
            'ip' => '评论者ip地址',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 查找文章的评论
     * @param $id
     * @return $this
     */
    public static function findCommentList($id)
    {
        return static::find()->where(['post_id'=>$id]);
    }

    /**
     * 是否是自己写的评论
     * @return bool
     */
    public function isCurrent()
    {
        return $this->user_id==Yii::$app->user->id;
    }

    /**
     * 查找指定的评论
     * @param $id
     * @param string $condition
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function findModel($id,$condition='')
    {
        $model=static::find()
            ->where(['id'=>$id])
            ->andWhere($condition)
            ->one();
        if(!$model)
        {
            throw new NotFoundHttpException;
        }
        return $model;

    }

    /**
     * 查找指定的评论
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function findComment($id)
    {
        return static::findModel($id,['status'=>self::STATUS_ACTIVE]);
    }

    /**
     * 查找已经删除的评论
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function findDeleteComment($id)
    {
        return static::findModel($id,['>=','status',self::STATUS_DELETED]);
    }
    public function getuser()
    {
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
    public function getLike()
    {
        $model=new UserMeta;
        return $model->isUserAction(self::TYPE,'like',$this->id);
    }
}
