<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\PlaceLocations;

/**
 * PlaceLocationsSearch represents the model behind the search form about `backend\models\PlaceLocations`.
 */
class PlaceLocationsSearch extends PlaceLocations
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['loc_id', 'loc_city_id'], 'integer'],
            [['loc_name', 'loc_status'], 'safe'],
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
        $query = PlaceLocations::find();

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
            'loc_id' => $this->loc_id,
            'loc_city_id' => $this->loc_city_id,
        ]);

        $query->andFilterWhere(['like', 'loc_name', $this->loc_name])
            ->andFilterWhere(['like', 'loc_status', $this->loc_status]);

        return $dataProvider;
    }
}
