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
            [['id', 'cust_id', 'created_by','company_id','branch_id','order_approved_by'], 'integer'],
            [['order_date', 'status', 'session_nos', 'created_date', 'sms_delivered','order_approved','cancelled'], 'safe'],
            [['total_amount'], 'number'],
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
		$session = Yii::$app->session;
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['order_date'=>SORT_DESC]
					],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_date' => $this->order_date,
            'cust_id' => $this->cust_id,
            'total_amount' => $this->total_amount,
            'created_by' => $this->created_by,
            'order_approved_by' => $this->order_approved_by,
            'created_date' => $this->created_date,
            'company_id' => $session['company.company_id'],
            'branch_id' => $session['company.branch_id'],
        ]);

        $query->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'session_nos', $this->session_nos])
            ->andFilterWhere(['like', 'cancelled', $this->cancelled])
            ->andFilterWhere(['like', 'order_approved', $this->order_approved])
            ->andFilterWhere(['like', 'sms_delivered', $this->sms_delivered]);

        return $dataProvider;
    }
}
