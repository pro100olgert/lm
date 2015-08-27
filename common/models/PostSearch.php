<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Post;

/**
 * PostSearch represents the model behind the search form about `backend\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['title', 'image', 'content', 'created', 'updated', 'user_id', 'user.username'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['user.username']);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $user = Yii::$app->getModule("user")->model("User");

        $postTable = Post::tableName();
        // set up query with relation to `user.username`
        $userTable = $user::tableName();

        $query = Post::find();

        $query->joinWith(['user' => function($query) use ($userTable) {
            $query->from(['user' => $userTable]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => ['defaultOrder' => ['created' => SORT_DESC]]
        ]);

        // enable sorting for the related columns
        $addSortAttributes = ["user.username"];
        foreach ($addSortAttributes as $addSortAttribute) {
            $dataProvider->sort->attributes[$addSortAttribute] = [
                'asc'   => [$addSortAttribute => SORT_ASC],
                'desc'  => [$addSortAttribute => SORT_DESC],
            ];
        }

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created' => $this->created,
            'updated' => $this->updated,
            "{$postTable}.status" => $this->status,
        ]);

        $createdTime = strtotime($this->created);
        $startDay = date("Y-m-d 00:00:00",$createdTime);
        $endDay = date("Y-m-d 00:00:00", $createdTime + 60*60*24);
        if($this->created) {
            $query->andFilterWhere(['between', 'created', $startDay, $endDay]);
        }
        
        $updatedTime = strtotime($this->updated);
        $startDay = date("Y-m-d 00:00:00",$updatedTime);
        $endDay = date("Y-m-d 00:00:00", $updatedTime + 60*60*24);
        if($this->updated) {
            $query->andFilterWhere(['between', 'updated', $startDay, $endDay]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', "{$userTable}.username", $this->getAttribute('user.username')]);


        return $dataProvider;
    }
}
