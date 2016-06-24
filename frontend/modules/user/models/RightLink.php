<?php

namespace frontend\modules\user\models;

use Yii;

/**
 * This is the model class for table "right_link".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $image
 * @property string $content
 * @property integer $type
 * @property string $created_user
 * @property integer $created_at
 * @property integer $updated_at
 */
class RightLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'right_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'created_user'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['title', 'image', 'content'], 'string', 'max' => 255],
            [['url'], 'string', 'max' => 225],
            [['created_user'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'url' => 'Url',
            'image' => '图片链接',
            'content' => '内容',
            'type' => '展示类别',
            'created_user' => '创建人',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
