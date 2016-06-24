<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/6/1
 * Time: 下午1:52
 */

namespace frontend\modules\user\models;

use Identicon\Identicon;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class AvatarForm extends Model
{
    public $avatar;
    private $_user;

    public function getUser()
    {
        if(!$this->_user)
        {
            $this->_user=Yii::$app->user->identity;
        }
        return $this->_user;
    }
    public function rules()
    {
        return [
            [['avatar'], 'required'],
            [['avatar'], 'file', 'extensions' => 'gif, jpg, png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => \Yii::t('app', 'File has to be smaller than 2MB')],
        ];
    }
    public function activeAttributes()
    {
        return ['avatar'=>'上传头像'];
    }
    public function save()
    {
        if($this->validate())
        {
            $this->user->avatar=$this->avatar;
            return $this->user->save();
        }
        return false;
    }
    public function getImageFile()
    {
        return isset($this->user->avatar)?Yii::$app->basePath.Yii::$app->params['avatarPath'].$this->user->avatar:null;
    }
    public function getNewUploadedImageFile()
    {
        return isset($this->avatar)?Yii::$app->basePath.Yii::$app->params['avatarPath'].$this->avatar:null;
    }

    /**
     * 使用默认头像
     * @return string
     */
    public function useDefaultImage()
    {
        $this->avatar=(new Identicon())->getImageDataUri($this->user->email);
    }

    /**
     * 上传头像
     * @return bool|\yii\web\UploadedFile[]
     */
    public function uploadImage()
    {
        $image=UploadedFile::getInstance($this,'avatar');

        if(empty($image))
        {
            return false;
        }
        $this->avatar=Yii::$app->security->generateRandomString().".{$image->extension}";
        return $image;
    }

    /**
     * 删除头像
     * @return bool
     */
    public function deleteImage()
    {
        $file = $this->getImageFile();

        // check if file exists on server
        if (empty($file) || !file_exists($file)) {
            return false;
        }
        // 删除缓存的旧头像
        $avatarCachePath = \Yii::$app->basePath . \Yii::$app->params['avatarCachePath'];
        $files = glob("{$avatarCachePath}/*_{$this->user->avatar}");
        array_walk($files, function ($file) {
            unlink($file);
        });

        if (!unlink($file)) {
            return false;
        }
        $this->avatar = null;
        return true;
    }
}