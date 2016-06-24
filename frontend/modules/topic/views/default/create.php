<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/18
 * Time: 下午10:20
 */

$this->title = '发布新话题';
?>
    <div class="col-md-9 topic-create" contenteditable="false" style="">

        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <?= $this->title ?>
            </div>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

<?= \frontend\widgets\TopicSidebar::widget([
    'type' => 'create'
])?>