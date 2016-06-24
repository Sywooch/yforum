<?php

namespace frontend\modules\user\controllers;

use common\models\PostComment;
use common\models\User;
use common\models\UserInfo;
use frontend\modules\topic\models\Topic;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->redirect(['show','username'=>Yii::$app->user->identity->username]);
    }

    /**
     * 显示用户信息页面
     * @param string $username
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionShow($username='')
    {
        $user=$this->user($username);
        if(!$user)
        {
            throw new NotFoundHttpException;
        }
        $currentId=Yii::$app->user->id;
        if(!$currentId&&$currentId!=$user->id)
        {
            UserInfo::updateAllCounters(['view_count'=>1],['user_id'=>$user->id]);
        }
        return $this->render('show',['user'=>$user,'dataProvider'=>$this->comment($user->id)]);
    }

    /**
     * 查看最近发表的文章
     * @param $username
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPost($username)
    {
        $user=$this->user($username);
        $dataProvider=new ActiveDataProvider([
           'query'=>Topic::find()->where(['user_id'=>$user->id,'type'=>Topic::TYPE])
            ->andWhere(['>=','status',Topic::STATUS_DELETED])
            ->orderBy(['created_at'=>SORT_DESC])
        ]);
        return $this->render('show',['user'=>$user,'dataProvider'=>$dataProvider]);
    }
    public function actionFavorite($username='')
    {

    }
    public function actionPoint($username='')
    {

    }
    /**
     * 根据用户名找出用户
     * @param string $username
     * @return null|static
     * @throws NotFoundHttpException
     */
    public function user($username='')
    {
        $user=User::findOne(['username'=>$username]);
        if(!$user)
        {
            throw new NotFoundHttpException;
        }
        return $user;
    }

    /**
     * 查找出用户的评论
     * @param $id
     * @return ActiveDataProvider
     */
    public function comment($id)
    {
        return new ActiveDataProvider([
            'query' => PostComment::find()->where(['user_id' => $id, 'status' => 1])->orderBy(['created_at' => SORT_DESC]),
        ]);
    }
}
