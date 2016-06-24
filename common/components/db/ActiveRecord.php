<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/31
 * Time: 下午1:53
 */

namespace common\components\db;
use yii\behaviors\TimestampBehavior;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}