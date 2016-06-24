<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/6/2
 * Time: 上午11:35
 */

namespace common\helpers;


use yii\helpers\ArrayHelper;

class Arr extends ArrayHelper
{
    /**
     * 从数组中随机选出一部分数据
     * @param array $arr
     * @param int $num
     * @return array|bool
     */
    public static function ArrayRandomAssoc(Array $arr,$num=1)
    {
        if(!$arr)
        {
            return false;
        }
        $res=[];
        $keys=array_keys($arr);
        shuffle($keys);
        for($i=0;$i<$num;$i++)
        {
            $res[$keys[$i]]=$arr[$keys[$i]];
        }
        return $res;
    }
}