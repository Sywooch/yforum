<?php

namespace frontend\modules\topic\controllers;
use common\components\Controller;
use common\models\Post;
use common\models\PostComment;
use common\models\PostSearch;
use common\models\User;
use common\service\TopicService;
use frontend\modules\topic\models\Search;
use frontend\modules\topic\models\Topic;
use frontend\modules\user\models\UserMeta;
use common\models\PostMeta;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `topic` module
 */
class DefaultController extends Controller
{
    const PAGE_SIZE=50;
    public $sorts = [
        'newest' => '最新的',
        'excellent' => '优质主题',
        'hotest' => '热门的',
        'uncommented' => '未回答的'
    ];
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $model=new PostSearch();
        $params=Yii::$app->request->queryParams;
        empty($params['tag']) ?: $params['PostSearch']['tags'] = $params['tag'];
        if (isset($params['node'])) {
            $postMeta = PostMeta::findOne(['alias' => $params['node']]);
            ($postMeta) ? $params['PostSearch']['post_meta_id'] = $postMeta->id : '';
        }
        $dataProvider=$model->search($params);
        $dataProvider->query->andWhere([Post::tableName().'.type'=>'topic','status'=>[Post::STATUS_ACTIVE,Post::STATUS_EXCELLENT]]);
        $sort=$dataProvider->getSort();
        $sort->attributes = array_merge($sort->attributes, [
            'hotest' => [
                'asc' => [
                    'comment_count' => SORT_DESC,
                    'created_at' => SORT_DESC
                ],
            ],
            'excellent' => [
                'asc' => [
                    'status' => SORT_DESC,
                    'comment_count' => SORT_DESC,
                    'created_at' => SORT_DESC
                ],
            ],
            'uncommented' => [
                'asc' => [
                    'comment_count' => SORT_ASC,
                    'created_at' => SORT_DESC
                ],
            ]
        ]);
        return $this->render('index',['searchModel' => $model,
            'sorts' => $this->sorts,
            'dataProvider' => $dataProvider]);
    }

    /**
     * 新建帖子
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model=new Topic();
        if($model->load(Yii::$app->request->post())&&$model->validate())
        {
            if($model->save())
            {
                (new UserMeta)->saveNewMeta('topic',$model->id,'follow');
                $this->flash('创建成功','success');
                return $this->redirect(['view','id'=>$model->id]);
            }
        }
        else
        {
            return $this->render('create',['model'=>$model]);
        }
    }

    /**
     * 查看文章
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model=Topic::findTopic($id);
        $dataPrivoder=new ActiveDataProvider([
            'query'=>PostComment::findCommentList($id),
            'pagination'=>[
                'pageSize'=>self::PAGE_SIZE,
            ],
            'sort' => ['defaultOrder' => ['created_at' => SORT_ASC]]
        ]);
        $user=Yii::$app->user->identity;
        $admin=($user&&($user->isAdmin($user->username)||$user->isSuperAdmin($user->username)))?true:false;
        return $this->render('view',[
            'model'=>$model,
            'admin'=>$admin,
            'dataProvider'=>$dataPrivoder,
            'comment'=>new PostComment()
        ]);
    }

    /**
     * 更新帖子
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model=Topic::findTopic($id);
        if(!($model&&(User::getThrones()||$model->isCurrent())))
        {
            throw new NotFoundHttpException();
        }
        if($model->load(Yii::$app->request->post())&&$model->validate())
        {
            if($model->save())
            {
                $this->flash('发表更新成功','success');
                return $this->redirect(['view','id'=>$id]);
            }
        }
        else
        {
            return $this->render('update',['model'=>$model]);
        }

    }

    /**
     * 删除帖子
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model=Topic::findTopic($id);
        if(!($model&&(User::getThrones()||$model->isCurrent())))
        {
            throw new NotFoundHttpException();
        }
        if($model->comment_count)
        {
            $this->flash('不能删除该帖子,因为已经存在评论','warning');
        }
        else
        {
            TopicService::delete($model);
            $revoke=Html::a('撤消', ['/topic/default/revoke', 'id' => $model->id]);
            $this->flash("「{$model->title}」文章删除成功。 反悔了？{$revoke}", 'success');
        }
        return $this->redirect(['/site/index']);
    }

    /**
     * 恢复删除的文章
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRevoke($id)
    {
        $model=Topic::findDeleteTopic($id);
        if(!($model&&(User::getThrones()||$model->isCurrent())))
        {
            throw new NotFoundHttpException();
        }
        TopicService::revoke($model);
        $this->flash("「{$model->title}」文章撤销删除成功。", 'success');
        return $this->redirect(['view','id'=>$id]);
    }

    /**
     * 给帖子加精华
     * @param $id
     */
    public function actionExcellent($id)
    {
        $model=Topic::findOne($id);
        $user=Yii::$app->user->identity;
        if(($model&&($user->isAdmin($user->username)||$user->isSuperAdmin($user->username))))
        {
            TopicService::excellent($model);
            $this->flash("[{$model->title}]加精华成功",'success');
            return $this->redirect(['view','id'=>$model->id]);
        }
        else
        {
            throw new NotFoundHttpException;
        }
    }

    /**
     * 查询操作
     * @return string
     */
    public function actionSearch()
    {
        $searchModel=new Search();
        $keywords=Yii::$app->request->get('keyword');
        if(empty($keywords))
        {
            $this->goHome();
        }
        $dataProvider=$searchModel->search($keywords);
        return $this->render('search',[
            'dataProvider'=>$dataProvider,
            'searchMOdel'=>$searchModel
        ]);
    }
    /**
     * 添加行为
     * @return array
     */
    public function behaviors()
    {
        Yii::info(parent::behaviors());
        return ArrayHelper::merge(parent::behaviors(),[
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
                    ['allow' => true, 'actions' => ['view', 'index', 'search'], 'verbs' => ['GET']],
                    // 登录用户才能提交评论或其他内容
                    ['allow' => true, 'actions' => ['api', 'view', 'delete'], 'verbs' => ['POST'], 'roles' => ['@']],
                    // 登录用户才能使用API操作(赞,踩,收藏)
                    ['allow' => true, 'actions' => ['create', 'update', 'revoke', 'excellent'], 'roles' => ['@']],
                ]
            ],
        ]);
    }
}
