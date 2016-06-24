<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/6/3
 * Time: 下午7:56
 */

namespace frontend\modules\topic\models;


use common\models\Post;
use hightman\xunsearch\ActiveRecord;
use yii\data\ActiveDataProvider;

class Search extends ActiveRecord
{
    public static function search($keyword)
    {
        $query=static::find()->where($keyword)
            ->andWhere(['status'=>[Post::STATUS_ACTIVE,Post::STATUS_EXCELLENT]]);
        $dataProvider=new ActiveDataProvider([
            'query'=>$query,
            'sort'=>[
                'defaultOrder'=>['update_at'=>SORT_DESC]
            ]
        ]);
        return $dataProvider;
    }
}