<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/30
 * Time: 下午1:02
 */
use yii\helpers\Html;

?>
<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <?= Yii::t('app', 'Received {0} reply', $model->comment_count) ?>
        <?php if ($tags = $model->tags): ?>
            <span class="pull-right">
            <i class="icon-tags"></i>
                <?php foreach (explode(',', $model->tags) as $key => $val) {
                    echo Html::a(Html::encode($val)
                        , ['/topic/default/index', 'tag' => $val]
                        , ['class' => 'btn btn-xs btn-primary']), ' ';
                } ?>
            </span>

        <?php endif ?>
    </div>
    <?= \yii\widgets\ListView::widget(['dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'list-group-item media mt0'],
        'summary' => false,
        'itemView' => '_item',]) ?>
</div>