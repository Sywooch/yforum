<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/6/1
 * Time: 下午4:37
 */

namespace frontend\modules\user\controllers;

use common\service\TopicService;
use Yii;
use common\components\Controller;
use yii\web\NotFoundHttpException;

class ActionController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        return parent::beforeAction($action);
    }
    public function actionLike($type,$id)
    {
        switch ($type)
        {
            case 'topic':
                $service=new TopicService();
                list($result,$data)=$service->userDoAction($id,'like');
                break;
            case 'tweet':
                $service=new TopicService();
                list($result,$data)=$service->userDoAction($id,'tweet');
                break;
            case 'comment':
                $service=new TopicService();
                list($result,$data)=$service->userDoAction($id,'comment');
                break;
            default:
                throw new NotFoundHttpException;
                break;
        }
        if($result)
        {
            return $this->message('提交成功','success');
        }
        else
        {
            Yii::warning($data->getErrors());
            return $this->message($data?$data->getErrors():'提交失败!');
        }
    }
    public function actionThanks($type, $id)
    {
        if ($type == 'topic') {
            $topicService = new TopicService();
            list($result, $data) = $topicService->userDoAction($id, 'thanks');

            if ($result) {
                return $this->message('提交成功!', 'success');
            } else {
                return $this->message($data ? $data->getErrors() : '提交失败!');
            }
        }
    }
    public function actionFavorite($type, $id)
    {
        if ($type == 'topic') {
            $topicService = new TopicService();
            list($result, $data) = $topicService->userDoAction($id, 'favorite');

            if ($result) {
                return $this->message('提交成功!', 'success');
            } else {
                return $this->message($data ? $data->getErrors() : '提交失败!');
            }
        }
    }
    public function actionFollow($type, $id)
    {
        if ($type == 'topic') {
            $topicService = new TopicService();
            list($result, $data) = $topicService->userDoAction($id, 'follow');

            if ($result) {
                return $this->message('提交成功!', 'success');
            } else {
                return $this->message($data ? $data->getErrors() : '提交失败!');
            }
        }
    }
    public function actionHate($type, $id)
    {
        if ($type == 'topic') {
            $topicService = new TopicService();
            list($result, $data) = $topicService->userDoAction($id, 'hate');

            if ($result) {
                return $this->message('提交成功!', 'success');
            } else {
                return $this->message($data ? $data->getErrors() : '提交失败!');
            }
        }
    }
}