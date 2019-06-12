<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CompanyCustomers;

/**
 * CompanyCustomersSearch represents the model behind the search form about `backend\models\CompanyCustomers`.
 */
class CompanyCustomersSearch extends CompanyCustomers
{
    /**
     * @inheritdoc
     */
	
    public function rules()
    {
        return [
            [['id', 'company_id', 'cust_id'], 'integer'],
            [['created_date', 'customer_number','mode'], 'safe'],
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
        $query = CompanyCustomers::find();
		//exit;
		
		

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
		if($this->mode=='amount'){
			
			$query->select('company_customers.id,company_customers.company_id,company_customers.customer_number,company_customers.cust_id,company_customers.created_date,sum(orders.total_amount) as total_amount');
			
			$query->leftJoin('orders','orders.cust_id = company_customers.cust_id');
			$query->orderBy(['orders.total_amount'=>SORT_DESC]);
			$query->groupBy(['cust_id']);
		}
		if($this->mode=='weight'){
			
			$query->select('company_customers.id,company_customers.company_id,company_customers.customer_number,company_customers.cust_id,company_customers.created_date,sum(orders.weight) as weight');
			
			$query->leftJoin('orders','orders.cust_id = company_customers.cust_id');
			$query->orderBy(['orders.weight'=>SORT_DESC]);
			$query->groupBy(['cust_id']);
		}
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'company_customers.company_id' => $this->company_id,
            'cust_id' => $this->cust_id,
            'created_date' => $this->created_date,
        ]);

        $query->andFilterWhere(['like', 'customer_number', $this->customer_number]);
        
        return $dataProvider;
    }
}
