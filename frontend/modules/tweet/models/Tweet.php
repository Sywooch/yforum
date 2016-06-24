<?php
namespace frontend\modules\tweet\models;
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/6/2
 * Time: 下午7:52
 */
use common\models\Post;
use frontend\modules\user\models\UserMeta;
class Tweet extends Post
{
    const TYPE='tweet';
    public function getLike()
    {
        $model=new UserMeta();
        return $model->isUserAction(self::TYPE,'like',$this->id);
    }

    /**
     * 根据指定的id查找出内容
     * @param $id
     * @param string $condition
     * @return array|mixed|null|\yii\db\ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public static function findModel($id,$condition='')
    {
        if(!($model=Yii::$app->cache->get('topic',$id)))
        {
            $model=static::find()->where(['id'=>$id])
                ->andWhere($condition)
                ->one();
        }
        if($model)
        {
            Yii::$app->cache->set('topic',$id,$model,0);
            return $model;
        }
        {
            throw new \yii\web\NotFoundHttpException;
        }

    }

    /**
     * @param $id
     * @return array|mixed|null|\yii\db\ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public static function findTweet($id)
    {
        return static::findModel($id,['>=','status',self::STATUS_ACTIVE]);
    }

    /**
     * @param $id
     * @return array|mixed|null|\yii\db\ActiveRecord
     * @throws \yii\web\NotFoundHttpException
     */
    public function findDeleteTweet($id)
    {
        return static::findModel($id,['>=','status',self::STATUS_DELETED]);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['post_meta_id', 'user_id', 'view_count', 'comment_count', 'favorite_count', 'like_count', 'thanks_count', 'hate_count', 'status', 'order', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string', 'min' => 3, 'max' => 500],
            [['post_meta_id'], 'default', 'value' => 0],
            [['title'], 'default', 'value' => ''],
        ];
    }
}