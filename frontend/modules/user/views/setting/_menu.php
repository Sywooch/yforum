<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/31
 * Time: 下午3:07
 */
$user=Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?=\yii\helpers\Html::img($user->getUserAvatar(24),['class'=>'img-rounded', 'alt' => $user->username]);?>
            <?=$user->username?>
        </h3>
    </div>
    <div class="panel-body">
        <?=\yii\widgets\Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked'
            ],
            'items' => [
                ['label' => '个人资料',  'url' => ['/user/setting/profile']],
                ['label' => '账号设置',  'url' => ['/user/setting/account']],
                ['label' => '更换头像',  'url' => ['/user/setting/avatar']],
//                ['label' => '打赏设置',  'url' => ['/user/setting/donate']],
            ]
        ]);?>
    </div>
</div>
