<?php

namespace frontend\modules\user\models;

use Yii;
use yii\base\Model;
use yii\swiftmailer\Mailer;

/**
 * This is the model class for table "user_account".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $client_id
 * @property string $data
 * @property integer $created_at
 */
class AccountForm extends Model
{
    /** @var string */
    public $email;

    /** @var string */
    public $username;

    /** @var string */
    public $tagline;

    /** @var string */
    public $new_password;

    /** @var string */
    public $current_password;

    /** @var Module */
    protected $module;

    /** @var Mailer */
    protected $mailer;

    /** @var User */
    private $_user;
    public function getUser()
    {
        if(!$this->_user)
        {
            $this->_user=Yii::$app->user->identity;
        }
        return $this->_user;
    }
    public function __construct(Mailer $mailer, array $config=[])
    {
        $this->mailer = $mailer;
        $this->module = Yii::$app->getModule('user');
        $this->setAttributes([
            'username' => $this->user->username,
            'email' => $this->user->email,
            'tagline' => $this->user->tagline
        ]);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'current_password'], 'required'],
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            ['username', 'match', 'pattern' => '/^[a-zA-Z]\w+$/'],
            ['username', 'string', 'min' => 3, 'max' => 20],
            ['email', 'email'],
            [['email', 'username'], 'unique', 'when' => function ($model, $attribute) {
                return $this->user->$attribute != $model->$attribute;
            }, 'targetClass' => '\common\models\User', 'message' => '此{attribute}已经被使用。'],
            ['new_password', 'string', 'min' => 6],
            ['tagline', 'string', 'max' => 40],
            ['current_password', function ($attr) {
                if (!\Yii::$app->security->validatePassword($this->$attr, $this->user->password_hash)) {
                    $this->addError($attr, '当前密码是输入错误');
                }
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'username' => '用户名',
            'new_password' => '新密码',
            'tagline' => '一句话介绍',
            'current_password' => '当前密码'
        ];
    }

    /**
     * 自定义保存
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->validate()) {
            $this->user->username=$this->username;
            $this->new_password?$this->user->password=$this->new_password:'';
            $this->user->tagline=$this->tagline;
            return $this->user->save();
        }
        return false;
    }
}
