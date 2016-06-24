<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/18
 * Time: 下午9:31
 */

namespace frontend\widgets;


namespace frontend\widgets;

class Panel extends \yii\bootstrap\Widget
{
    public $items = [];
    public $title = '';

    public function run()
    {
        $model = [
            'items' => $this->items,
            'title' => $this->title,
        ];

        return $this->render('panel', [
            'model' => $model,
        ]);
    }
}