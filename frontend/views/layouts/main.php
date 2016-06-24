<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\assets\BrowerAsset;
use \frontend\assets\EditAsset;
use frontend\assets\EmojifyAsset;
use yii\helpers\Url;

AppAsset::register($this);
BrowerAsset::register($this);
EditAsset::register($this);
$emojify=EmojifyAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
        echo \frontend\widgets\Nav::widget();
    ?>
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <p style="text-align: center"> Copyright © 2016yforum.pub powerby <a href="http://www.yiiframework.com/">Yii Framework</a> </p>
        </div>
    </div>
</footer>

<div class="btn-group-vertical" id="floatButton">
    <button type="button" class="btn btn-default" id="goTop" title="去顶部"><span
            class="glyphicon glyphicon-arrow-up"></span></button>
    <button type="button" class="btn btn-default" id="refresh" title="刷新"><span
            class="glyphicon glyphicon-repeat"></span></button>
    <button type="button" class="btn btn-default" id="pageQrcode" title="本页二维码"><span
            class="glyphicon glyphicon-qrcode"></span>
        <img class="qrcode" width="130" height="130"
             src="<?= Url::to(['/site/qrcode', 'url' => Yii::$app->request->absoluteUrl]) ?>"/>
    </button>
    <button type="button" class="btn btn-default" id="goBottom" title="去底部"><span
            class="glyphicon glyphicon-arrow-down"></span></button>
</div>

<div style="display:none">
        <?= \Yii::$app->setting->get('siteAnalytics'); ?>
</div>
<?php
$this->registerJs(
    'Config = {emojiBaseUrl: "' . $emojify->baseUrl . '"};',
    \yii\web\View::POS_HEAD
);
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
