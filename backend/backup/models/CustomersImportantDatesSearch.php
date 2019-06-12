<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomersImportantDates;

/**
 * CustomersImportantDatesSearch represents the model behind the search form about `backend\models\CustomersImportantDates`.
 */
class CustomersImportantDatesSearch extends CustomersImportantDates
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cust_id', 'type'], 'integer'],
            [['imp_date', 'title'], 'safe'],
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
        $query = CustomersImportantDates::find();

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
            'imp_date' => $this->imp_date,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
