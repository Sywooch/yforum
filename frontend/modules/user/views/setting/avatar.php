<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/6/1
 * Time: 下午3:32
 */
$this->title=Yii::t('app','Avatar');
?>
<section class="container">
    <div class="col-md-3">
        <?=$this->render('_menu')?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=\yii\helpers\Html::encode($this->title)?>
            </div>
            <div class="panel-body">
                <?php
                    $form=\yii\widgets\ActiveForm::begin([
                        'id'          => 'account-form',
                        'options'     => ['enctype' => 'multipart/form-data'],
                    ]);
                ?>
                <?=\yii\helpers\Html::img($model->user->getUserAvatar(200))?>
                <?=\yii\helpers\Html::img($model->user->getUserAvatar(50))?>
                <?=\yii\helpers\Html::img($model->user->getUserAvatar(24))?>
                <br>
                <br>
                <?=$form->field($model,'avatar')->fileInput();?>
                <div class="form-group">
                    <?=\yii\helpers\Html::submitButton('提交',['class' => 'btn btn-success'])?>
                </div>
                <?php \yii\widgets\ActiveForm::end()?>
            </div>
        </div>
    </div>
</section>
