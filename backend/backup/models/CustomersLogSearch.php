<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomersLog;

/**
 * CustomersLogSearch represents the model behind the search form about `backend\models\CustomersLog`.
 */
class CustomersLogSearch extends CustomersLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cust_id'], 'integer'],
            [['log_date', 'start_session_time', 'end_session_time', 'time_spent', 'status', 'session_no'], 'safe'],
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
        $query = CustomersLog::find();

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
            'log_date' => $this->log_date,
        ]);

        $query->andFilterWhere(['like', 'start_session_time', $this->start_session_time])
            ->andFilterWhere(['like', 'end_session_time', $this->end_session_time])
            ->andFilterWhere(['like', 'time_spent', $this->time_spent])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'session_no', $this->session_no]);

        return $dataProvider;
    }
}
