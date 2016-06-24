<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/30
 * Time: 下午2:09
 */
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;
use yii\widgets\ActiveForm;

?>
<div class="list-group-item">
    <?php $form = ActiveForm::begin([
        'action' => [$model->isNewRecord ? '/topic/comment/create' : '/topic/comment/update', 'id' => Yii::$app->request->getQueryParam('id')],
        'fieldConfig' => [
            'template' => "{input}\n{hint}\n{error}"
        ]]) ?>
    <?=$form->errorSummary($model,['class'=>'alert alert-danger'])?>
    <?=$this->render('@frontend/views/partials/markdwon_help')?>
    <?=$form->field($model,'comment',['selectors'=>['input'=>'#md-input']])
        ->textarea(
            ['placeholder'=>'内容',
            'id'=>'md-input',
            'disabled'=>Yii::$app->user->isGuest,
            'rows'=>6]
        )?>
    <div class="form-group">
        <?=Html::submitButton($model->isNewRecord?'创建评论':'更新评论',
            ['class'=>$model->isNewRecord?'btn btn-success':'btn btn-primary'])?>
        <div class="pull-right">
            <?=Html::a('排版说明','/site/markdown',['target'=>'_blank'])?>
        </div>
    </div>
    <div id="md-preview"><?= HtmlPurifier::process(Markdown::process($model->comment, 'gfm')) ?></div>
    <?php ActiveForm::end() ?>
</div>

