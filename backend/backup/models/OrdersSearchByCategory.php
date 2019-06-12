<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `backend\models\Orders`.
 */
class OrdersSearchByCategory extends Orders
{
    
	
	
    public function rules()
    {
        return [
            [['order_date','category_name'], 'safe'],
            [['category_id'], 'number'],
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
		$query->select(['orders.order_date,categories.category_id,categories.category_name,SUM(services_price) as total_price,COUNT(categories.category_id) as catcount',])
		->from('orders')
		->leftJoin('orders_details','orders.id = orders_details.orders_id' )
		->leftJoin('services','services.id = orders_details.services_id' )
		->leftJoin('categories','categories.category_id = services.category_id' )
		->groupBy('orders.order_date,categories.category_id');
		
		
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['attributes' => ['category_id','order_date','total_price','catcount'],
						'defaultOrder' => ['order_date'=>SORT_DESC]
					],
			 
        ]);
		

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		$query->andFilterWhere([
            'order_date' => $this->order_date,
            'categories.category_id' => $this->category_id,
            
        ]);

        
        return $dataProvider;
    }
}
