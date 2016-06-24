<?php
namespace frontend\widgets;
use yii\bootstrap\Widget;
class Nav extends Widget
{
    public function run()
    {
        return $this->render('nav',[]);
    }
}