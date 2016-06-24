<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/30
 * Time: 上午10:23
 */
namespace frontend\modules\topic\controllers;
use Yii;
use common\components\Controller;
use frontend\modules\topic\models\Topic;
use common\models\PostComment;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class CommentController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // 登录用户POST操作
                    ['allow' => true, 'actions' => ['delete'], 'verbs' => ['POST'], 'roles' => ['@']],
                    // 登录用户才能操作
                    ['allow' => true, 'actions' => ['create', 'update'], 'roles' => ['@']],
                ]
            ],
        ]);
    }

    /**
     * 创建评论
     * @param $id
     */
    public function actionCreate($id)
    {
        $topic=Topic::findTopic($id);
        $model=new PostComment();
        if($model->load(Yii::$app->request->post()))
        {
            $model->user_id=Yii::$app->user->id;
            $model->post_id=$id;
            $model->ip=Yii::$app->getRequest()->getUserIP();
            if($model->save())
            {
                $this->flash('评论成功');
            }
            else
            {
                $this->flash($model->getFirstErrors(),'warning');
            }
            return $this->redirect(['/topic/default/view','id'=>$id]);
        }
        return $model;
    }

    /**
     * 更新评论
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model=PostComment::findComment($id);
        if(!$model->isCurrent())
        {
            throw new NotFoundHttpException;
        }
        if($model->load(Yii::$app->request->post())&&$model->save())
        {
            $this->flash('更新评论成功');
            return $this->redirect(['/topic/default/view','id'=>$model->post_id]);
        }
        else
        {
            return $this->render('update',['model'=>$model]);
        }
    }

    /**
     * 删除评论
     * @param $id
     */
    public function actionDelete($id)
    {
        $model=PostComment::findComment($id);
        if(!$model)
        {
            throw new NotFoundHttpException;
        }
        $model->status=PostComment::STATUS_DELETED;
//        Notification::updateAll(['status' => 0], ['comment_id' => $model->id]);
        $model->save();
        return $this->redirect(['/topic/default/view','id'=>$model->post_id]);
    }
}