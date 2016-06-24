<?php

namespace common\models;

use common\components\db\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "post_meta".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $alias
 * @property string $type
 * @property string $description
 * @property integer $count
 * @property integer $order
 * @property integer $create_at
 * @property integer $update_at
 */
class PostMeta extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_meta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent', 'count', 'order', 'create_at', 'update_at'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['alias', 'type'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'parent' => '父级分类',
            'alias' => '变量（别名）',
            'type' => '项目类型',
            'description' => '选项描述',
            'count' => '项目所属内容个数',
            'order' => '项目排序',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     *
     */
    public static function blogCategory()
    {
        ArrayHelper::map(static::find()->where(['type'=>'blog_category'])->all(),'id','name');
    }

    /**
     * 返回Topic的分类
     * @return array
     */
    public static function topicCategory()
    {
        $parents = ArrayHelper::map(
            static::find()->where(['parent' => null])->orWhere(['parent' => 0])->orderBy(['order' => SORT_ASC])->all(),
            'id', 'name'
        );
        $nodes = [];
        foreach ($parents as $key => $value) {
            $nodes[$value] = ArrayHelper::map(static::find()->where(['parent' => $key])->asArray()->all(), 'id', 'name');
        }
        return $nodes;
    }
}
