<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/31
 * Time: 下午2:53
 */
use yii\helpers\Html;
$this->title = '个人资料'
?>

<section class="container user-index">
    <div class="col-md-3">
        <?=$this->render('_menu')?>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?=Html::encode($this->title)?>
            </div>
            <div class="panel-body">
                <?php
                    $form=\yii\widgets\ActiveForm::begin([
                        'id' => 'profile-form',
                        'options' => ['class' => 'form-horizontal'],
                        'fieldConfig' => [
                            'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                            'labelOptions' => ['class' => 'col-lg-3 control-label'],
                        ],
       
                    ]);
                ?>
                <?= $form->field($model, 'location') ?>
                <?= $form->field($model, 'company') ?>
                <?= $form->field($model, 'website') ?>
                <?= $form->field($model, 'github') ?>
                <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>
                <div class="form-group">
                    <div class="col-lg-offset-3  col-lg-3">
                        <?=Html::submitButton('保存',['class'=>'btn btn-success'])?><br>
                    </div>
                </div>
                <?php
                    \yii\widgets\ActiveForm::end();
                ?>
            </div>
        </div>
    </div>
</section>
