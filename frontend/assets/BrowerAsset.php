<?php

namespace frontend\assets;


use yii\web\AssetBundle;

class BrowerAsset extends AssetBundle
{

    public $baseUrl='@bower';
    public $sourcePath='@bower';
    public $css=[
        'font-awesome/css/font-awesome.min.css',
        'highlightjs/styles/monokai_sublime.css',
        'pace/themes/green/pace-theme-minimal.css',
    ];

    public $js = [
        'marked/lib/marked.js',
        'highlightjs/highlight.pack.js',
        'localforage/dist/localforage.min.js',
        'pace/pace.min.js',
    ];
}