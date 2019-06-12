<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomersBonuses;

/**
 * CustomersBonusesSearch represents the model behind the search form about `backend\models\CustomersBonuses`.
 */
class CustomersBonusesSearch extends CustomersBonuses
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cust_id', 'order_id'], 'integer'],
            [['type', 'created_date', 'valid_upto', 'cancelled'], 'safe'],
            [['bonus_amount'], 'number'],
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
        $query = CustomersBonuses::find();

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
            'id' => $this->id,
            'cust_id' => $this->cust_id,
            'order_id' => $this->order_id,
            'created_date' => $this->created_date,
            'valid_upto' => $this->valid_upto,
            'bonus_amount' => $this->bonus_amount,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'cancelled', $this->cancelled]);

        return $dataProvider;
    }
}
