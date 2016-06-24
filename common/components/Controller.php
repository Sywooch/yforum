<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/18
 * Time: 下午2:50
 */
namespace common\components;

use Yii;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yiier\merit\MeritBehavior;
class Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'returnUrl' => [
                'class' => 'yiier\returnUrl\ReturnUrl',
                'uniqueIds' => ['site/qrcode', 'site/login', 'user/security/auth']
            ],
            MeritBehavior::className(),
        ]);
    }
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
    public function afterAction($action, $result)
    {
        return parent::afterAction($action, $result);
    }

    /**
     * 设置闪现消息
     * @param $msg
     * @param $type
     * @param $url
     * @throws \yii\base\ExitException
     */
    public function flash($msg,$type='success',$url=null)
    {
        Yii::$app->getSession()->setFlash($type,$msg);
        if($url)
        {
            Yii::$app->end(0,$this->redirect($url));
        }
    }

    /**
     * 向客户端传送json信息
     * @param $message
     * @param string $type
     * @param null $redirect
     * @param null $resultType
     * @return array|string
     */
    public function message($message, $type = 'info', $redirect = null, $resultType = null)
    {
        $resultType === null && $resultType = Yii::$app->getRequest()->getIsAjax() ? 'json' : 'html';
        is_array($redirect) && $redirect = Url::to($redirect);
        $data = [
            'type' => $type,
            'message' => $message,
            'redirect' => $redirect
        ];

        if ($resultType === 'json') {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return $data;
        } elseif ($resultType === 'html') {
            return $this->render('/common/message', $data);
        }
    }

}