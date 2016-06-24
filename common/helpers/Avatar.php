<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/30
 * Time: 下午10:12
 */
namespace common\helpers;

use Identicon\Identicon;

class Avatar
{
    public $email;
    public $size;
    public function __construct($email,$size)
    {
        $this->email=$email;
        $this->size=$size;
    }
    /**
     * 如果用户没有设置头像的话,使用identicon提供的随机头像
     * @return string
     */
    public function getAvatar()
    {
        return (new Identicon())->getImageDataUri($this->email,$this->size);
    }
}