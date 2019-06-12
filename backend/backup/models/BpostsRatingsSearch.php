<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\BpostsRatings;

/**
 * BpostsRatingsSearch represents the model behind the search form about `backend\models\BpostsRatings`.
 */
class BpostsRatingsSearch extends BpostsRatings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rating_id', 'rating_bposts_id', 'rating_user_id', 'created_at'], 'integer'],
            [['rating_score'], 'number'],
            [['rating_review_text'], 'safe'],
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
        $query = BpostsRatings::find();

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
            'rating_id' => $this->rating_id,
            'rating_bposts_id' => $this->rating_bposts_id,
            'rating_score' => $this->rating_score,
            'rating_user_id' => $this->rating_user_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'rating_review_text', $this->rating_review_text]);

        return $dataProvider;
    }
}
