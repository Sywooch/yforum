<?php
/**
 * Created by PhpStorm.
 * User: noble4cc
 * Date: 16/5/18
 * Time: 下午6:38
 */

namespace common\models;


use yii\data\ActiveDataProvider;

class PostSearch extends Post
{
    public function rules()
    {
        return [
            [['id', 'post_meta_id', 'user_id', 'view_count', 'comment_count', 'favorite_count', 'like_count', 'thanks_count', 'hate_count', 'status', 'order', 'created_at', 'updated_at'], 'integer'],
            [['type', 'title', 'author', 'excerpt', 'image', 'content', 'tags'], 'safe'],
        ];
    }
    public function search($params)
    {
        $query=$this->find();
        $dataProvider=new ActiveDataProvider(
          [
              'query' => $query,
              'pagination' => [
                  'pageSize' => 20,
              ],
              'sort' => ['defaultOrder' => [
                  'order' => SORT_ASC,
                  'last_comment_time' => SORT_DESC,
                  'created_at' => SORT_DESC,
              ]]
          ]
        );
        if(!($this->load($params)&&$this->validate()))
        {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'post_meta_id' => $this->post_meta_id,
            'user_id' => $this->user_id,
            'view_count' => $this->view_count,
            'comment_count' => $this->comment_count,
            'favorite_count' => $this->favorite_count,
            'like_count' => $this->like_count,
            'thanks_count' => $this->thanks_count,
            'hate_count' => $this->hate_count,
            'status' => $this->status,
            'order' => $this->order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'excerpt', $this->excerpt])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'tags', $this->tags]);
        return $dataProvider;
    }
    
}