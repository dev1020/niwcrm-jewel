<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Place;

/**
 * PlaceSearch represents the model behind the search form about `backend\models\Place`.
 */
class PlaceSearch extends Place
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'place_type', 'created_by', 'pin', 'location', 'place_bpost_id'], 'integer'],
            [['google_place_id', 'created_at', 'address1', 'address2'], 'safe'],
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
        $query = Place::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['place_bpost_id'=>SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'place_type' => $this->place_type,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'pin' => $this->pin,
            'location' => $this->location,
            'place_bpost_id' => $this->place_bpost_id,
        ]);

        $query->andFilterWhere(['like', 'google_place_id', $this->google_place_id])
            ->andFilterWhere(['like', 'address1', $this->address1])
            ->andFilterWhere(['like', 'address2', $this->address2]);

        return $dataProvider;
    }
}
