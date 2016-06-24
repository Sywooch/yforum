<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/31
 * Time: 上午9:53
 */
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Markdown;

?>
<?php switch ($this->context->action->id) {
    case 'show':
        // 评论
        if ($model->post)
        {
            echo Html::a(
                Html::encode($model->post->title),
                ["/{$model->post->type}/default/view", 'id' => $model->post->id],
                ['class' => 'list-group-item-heading']
            );
        }
        echo Html::tag('span', Yii::$app->formatter->asRelativeTime($model->created_at), ['class' => 'ml5 fade-info']);
        echo Html::tag('p', HtmlPurifier::process(Markdown::process($model->comment, 'gfm')));
        break;
    case 'favorite':
        // 收藏
        echo Html::tag('i', '', ['class' => 'fa fa-bookmark red mr5']);

        echo Html::a(
            Html::encode($model->topic->title),
            ["/{$model->topic->type}/default/view", 'id' => $model->topic->id],
            ['class' => 'list-group-item-heading']
        );
        echo Html::tag('span', Yii::$app->formatter->asRelativeTime($model->topic->created_at), ['class' => 'ml5 fade-info']);
        echo Html::beginTag('p', ['class' => 'list-group-item-text title-info']);

        echo Html::a($model->topic->category->name, ["/{$model->topic->type}/default/index", 'node' => $model->topic->category->alias]);
        echo ' • ';
        echo Html::beginTag('span');
        echo "{$model->topic->like_count} 个赞 • {$model->topic->comment_count} 条回复";
        echo Html::endTag('span');
        echo Html::endTag('p');
        break;

    case 'point':
        // 积分
        echo Html::tag('i', '', ['class' => 'fa fa-money red mr5']);
        echo Html::encode($model->description);
        echo Html::tag('span', Yii::$app->formatter->asRelativeTime($model->created_at), ['class' => 'ml5 fade-info']);
        break;
    default:
        // post 文章
        echo Html::a(
            Html::encode($model->title),
            ["/{$model->type}/default/view", 'id' => $model->id],
            ['class' => 'list-group-item-heading']
        );
        echo Html::tag('span', Yii::$app->formatter->asRelativeTime($model->created_at), ['class' => 'ml5 fade-info']);
        echo Html::beginTag('p', ['class' => 'list-group-item-text title-info']);
        echo Html::a($model->category->name, ["/{$model->type}/default/index", 'node' => $model->category->alias]);
        echo ' • ';
        echo Html::beginTag('span');
        echo "{$model->like_count} 个赞 • {$model->comment_count} 条回复";
        echo Html::endTag('span');
        echo Html::endTag('p');
        break;
} ?>