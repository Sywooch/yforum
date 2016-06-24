<?php
/**
 * Created by PhpStorm.
 * User: noble4ccs
 * Date: 16/5/22
 * Time: 下午4:58
 */


namespace common\helpers;

class Formatter
{
    const DATETIME='php:Y-m-d H:i:s';
    const DATE='php:Y-m-d';
    const TIME='php:H:i:s';

    /**
     * 转换时间格式
     * @param $date
     * @param string $type
     * @param null $format
     * @return string
     */
    public static function convert($date,$type='date',$format=null)
    {
        if($type=='datatime')
        {
            $fmt=($format==null)?self::DATETIME:$format;
        }
        else if($type=='time')
        {
            $fmt=($format==null)?self::TIME:$format;
        }
        else{
            $fmt=($format==null)?self::DATE:$format;
        }
        return \Yii::$app->formatter->asDate($date,$fmt);
    }

    /** 转换为相对时间
     * @param $date
     * @return string
     */
    public static function relative($date)
    {
        return \Yii::$app->formatter->asRelativeTime($date);
    }
}