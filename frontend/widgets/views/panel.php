<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/18
 * Time: 下午9:34
 */
use yii\helpers\Html;

?>
<?php if ($model): ?>
    <div class="panel panel-default corner-radius">
        <div class="panel-heading text-center">
            <h3 class="panel-title"><?= $model['title']?></h3>
        </div>
        <div class="panel-body side-bar">
            <ul class="list">
                <?php foreach ($model['items'] as $key => $value) {
                    echo Html::tag('li', Html::a(Html::encode($key), $value));
                } ?>
            </ul>
        </div>
    </div>
<?php endif ?>

