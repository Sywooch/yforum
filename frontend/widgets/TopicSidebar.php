<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/18
 * Time: 下午6:06
 */

namespace frontend\widgets;

use common\helpers\Arr;
use common\models\PostMeta;
use common\models\RightLink;
use frontend\modules\topic\models\Topic;
use frontend\modules\user\models\Donate;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class TopicSidebar extends \yii\bootstrap\Widget
{
    public $type = 'node';
    public $node;
    public $tags;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $tipsModel = ArrayHelper::map(
            RightLink::find()->where(['type' => RightLink::RIGHT_LINK_TYPE_TIPS])->all(),
            'content',
            'title'
        );
        $tips = array_rand($tipsModel);

        $recommendResources = ArrayHelper::map(
            RightLink::find()->where(['type' => RightLink::RIGHT_LINK_TYPE_RSOURCES])->all(),
            'title',
            'url'
        );

        $links = RightLink::find()->where(['type' => RightLink::RIGHT_LINK_TYPE_LINKS])->all();

        $sameTopics = [];
        if ($this->node) {
            $sameTopics = ArrayHelper::map(
                Topic::find()
                    ->where('status >= :status', [':status' => Topic::STATUS_ACTIVE])
                    ->andWhere(['post_meta_id' => $this->node->id, 'type' => 'topic'])
                    ->limit(200)->all(),
                'title',
                function ($e) {
                    return Url::to(['/topic/default/view', 'id' => $e->id]);
                }
            );
            if (count($sameTopics) > 10) {
                $sameTopics = Arr::arrayRandomAssoc($sameTopics, 10);
            }

//            if ($this->type == 'view' && (in_array($this->node->alias, params('donateNode')) || array_intersect(explode(',', $this->tags), params('donateTag')))) {
//                $donate = Donate::findOne(['user_id' => Topic::findOne(['id' => request()->get('id')])->user_id, 'status' => Donate::STATUS_ACTIVE]);
//            }
        }

        return $this->render('topicSidebar', [
            'category' => PostMeta::blogCategory(),
            'config' => ['type' => $this->type, 'node' => $this->node],
            'sameTopics' => $sameTopics,
            'tips' => $tips,
            'donate' => isset($donate) ? $donate : [],
            'recommendResources' => $recommendResources,
            'links' => $links,
        ]);
    }
}