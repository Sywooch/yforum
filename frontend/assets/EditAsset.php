<?php

namespace frontend\assets;
use yii\web\AssetBundle;

class EditAsset extends AssetBundle
{
    public $baseUrl='@web';
    public $basePath='@webroot';
    public $css=[];
    public $js=['js/editor.js'];

}