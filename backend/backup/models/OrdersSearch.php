<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `backend\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cust_id','order_boy_id'], 'integer'],
            [['order_date', 'status', 'session_nos','order_type'], 'safe'],
            [['total_amount', 'due_amount'], 'number'],
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
        $query = Orders::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['order_date'=>SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_boy_id' => $this->order_boy_id,
            'order_date' => $this->order_date,
            'cust_id' => $this->cust_id,
            'total_amount' => $this->total_amount,
            'due_amount' => $this->due_amount,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
			->andFilterWhere(['like', 'order_type', $this->order_type])
            ->andFilterWhere(['like', 'session_nos', $this->session_nos]);

        return $dataProvider;
    }
}
