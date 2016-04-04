<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Post;

/**
 * PostSearch represents the model behind the search form about `app\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'user_id', 'owner_id', 'post_ref'], 'integer'],
            [['post_type', 'post_text', 'post_visibility', 'post_date', 'post_editdate', 'post_additionaltext'], 'safe'],
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
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'post_id' => $this->post_id,
            'user_id' => $this->user_id,
            'owner_id' => $this->owner_id,
            'post_ref' => $this->post_ref,
            'post_date' => $this->post_date,
            'post_editdate' => $this->post_editdate,
        ]);

        $query->andFilterWhere(['like', 'post_type', $this->post_type])
            ->andFilterWhere(['like', 'post_text', $this->post_text])
            ->andFilterWhere(['like', 'post_visibility', $this->post_visibility])
            ->andFilterWhere(['like', 'post_additionaltext', $this->post_additionaltext]);

        return $dataProvider;
    }
}
