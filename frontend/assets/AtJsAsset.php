<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/18
 * Time: 下午10:23
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class AtJsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
    ];

    public $js = [
        'js/At.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'common\assets\AtJs',
        'common\assets\CaretJs',
    ];
}