<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomersServices;

/**
 * CustomersServicesSearch represents the model behind the search form about `backend\models\CustomersServices`.
 */
class CustomersServicesSearch extends CustomersServices
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cust_id', 'service_id'], 'integer'],
            [['service_status', 'service_start_time', 'service_end_time'], 'safe'],
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
        $query = CustomersServices::find();

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
            'service_id' => $this->service_id,
        ]);

        $query->andFilterWhere(['like', 'service_status', $this->service_status])
            ->andFilterWhere(['like', 'service_start_time', $this->service_start_time])
            ->andFilterWhere(['like', 'service_end_time', $this->service_end_time]);

        return $dataProvider;
    }
}
