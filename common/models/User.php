<?php
namespace common\models;

use common\components\db\ActiveRecord;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\imagine\Image;
use yii\web\IdentityInterface;
use yiier\merit\models\Merit;
use common\helpers\Avatar;
use common\models\UserInfo;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const ROLE_USER = 10;
    const ROLE_ADMIN = 20;
    const ROLE_SUPER_ADMIN = 30;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     *  根据email获得用户
     * @param $email
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**获得用户角色
     * @param $role
     */
    public static function getRole($role)
    {
        $roles = [self::ROLE_USER => ['name' => '用户', 'color' => 'primary'],
            self::ROLE_ADMIN => ['name' => '管理员', 'color' => 'info'],
            self::ROLE_SUPER_ADMIN => ['name' => '超级管理员', 'color' => 'success']
        ];
        return $roles[$role];
    }

    public function getUserAvatar($size = 50)
    {
        if($this->avatar)
        {
            $avatarCachePath=Yii::$app->basePath.Yii::$app->params['avatarCachePath'];
            $avatarPath=Yii::$app->basePath.Yii::$app->params['avatarPath'];
            if(file_exists($avatarCachePath.$size.'_'.$this->avatar))
            {
                return Yii::$app->params['avatarCacheUrl'] . $size . '_' . $this->avatar;
            }
            if(file_exists($avatarPath.$this->avatar))
            {
                Image::thumbnail($avatarPath.$this->avatar,$size,$size)
                    ->save($avatarCachePath.$size.'_'.$this->avatar,['quality'=>100]);
                return Yii::$app->params['avatarCacheUrl'] . $size . '_' . $this->avatar;
            }
        }
        return (new Avatar($this->email,$size))->getAvatar();
    }

    /**
     * 是否是管理员
     * @param $username
     */
    public static function isAdmin($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_ADMIN])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否是超级管理员
     * @param $username
     */
    public static function isSuperAdmin($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_SUPER_ADMIN])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断用户权限
     * @param string $username
     * @return bool
     */
    public static function getThrones($username = '')
    {
        if (!$username && Yii::$app->user->id) {
            $username = Yii::$app->user->identity->username;
        } else {
            return false;
        }
        return self::isAdmin($username) ? true : self::isSuperAdmin($username);
    }

    public function getUserInfo()
    {
        return $this->hasOne(UserInfo::className(), ['user_id' => 'id']);
    }

    public function getMerit()
    {
        return $this->hasOne(Merit::className(), ['user_id' => 'id']);
    }

    /**
     * 创建user的同时创建用户信息
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\base\InvalidConfigException
     */
    public function afterSave($insert, $changedAttributes)
    {
        if($insert)
        {
            $time=time();
            $ip=isset(Yii::$app->request->userIP)?Yii::$app->request->getUserIP():'127.0.0.1';
            $userInfo=Yii::createObject([
                'class'=>UserInfo::className(),
                'user_id'=>$this->id,
                'prev_login_time' => $time,
                'prev_login_ip' => $ip,
                'last_login_time' => $time,
                'last_login_ip' => $ip,
                'created_at' => $time,
                'updated_at' => $time,
            ]);
            $userInfo->save();
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

}
