<?php

namespace frontend\modules\tweet\controllers;

use common\components\Controller;
use common\models\Post;
use frontend\modules\tweet\models\TweetSearch;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Yii;
use frontend\modules\user\models\UserMeta;
use frontend\modules\tweet\models\Tweet;
/**
 * Default controller for the `tweet` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $modelSearch=new TweetSearch();
        $params=Yii::$app->request->queryParams;
        $params['TweetSearch']['content'] = empty($params['topic']) ? '' : $params['topic'];
        $dataProvider=$modelSearch->search($params);
        $dataProvider->query->andWhere([
            Post::tableName().'.type'=>Tweet::TYPE,
            'status'=>[Tweet::STATUS_ACTIVE,Tweet::STATUS_EXCELLENT]
        ]);
        $model=new Tweet();
        return $this->render('index',['model'=>$model,
            'modelSearch'=>$modelSearch,'dataProvider'=>$dataProvider]);
    }
    public function actionCreate()
    {
        $model=new Tweet();
        if($model->load(Yii::$app->request->post()))
        {
            $model->type=$model::TYPE;
            $model->user_id=Yii::$app->user->id;
            if($model->save())
            {
                (new UserMeta())->saveNewMeta($model->type, $model->id, 'follow');
//                (new NotificationService())->newPostNotify(Yii::$app->user->identity, $model, $rawContent);
                $this->flash('添加动弹成功');
            }
        }
        return $this->redirect('index');
    }
    public function actionDelete()
    {

    }
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
                    // 默认只能Get方式访问
                    ['allow' => true, 'actions' => ['index'], 'verbs' => ['GET']],
                    // 登录用户POST操作
                    ['allow' => true, 'actions' => ['delete'], 'verbs' => ['POST'], 'roles' => ['@']],
                    // 登录用户才能操作
                    ['allow' => true, 'actions' => ['create'], 'roles' => ['@']],
                ]
            ],
        ]);
    }
}
