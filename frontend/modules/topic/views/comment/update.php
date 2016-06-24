<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/30
 * Time: 下午5:14
 */
use yii\helpers\Html;

$this->title = Yii::t('app', 'Update {modelClass}: ', [
        'modelClass' => 'Post Comment',
    ]) . ' ' . $model->post->title;
?>

    <div class="col-md-9 topic-create" contenteditable="false" style="">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <?= Html::encode($this->title) ?>
            </div>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

<?= \frontend\widgets\TopicSidebar::widget([

])?>