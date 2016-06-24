<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/30
 * Time: 下午2:03
 */
?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        添加评论<?php if(Yii::$app->user->isGuest): ?><small>(需要登录)</small><?php endif?>
    </div>
    <?= $this->render('_form',['model'=>$model])?>
</div>
