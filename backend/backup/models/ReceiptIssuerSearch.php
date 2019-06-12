<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ReceiptIssuer;

/**
 * ReceiptIssuerSearch represents the model behind the search form about `backend\models\ReceiptIssuer`.
 */
class ReceiptIssuerSearch extends ReceiptIssuer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receipt_id', 'receipt_partners', 'receipt_amount'], 'integer'],
            [['receipt_number', 'receipt_issue_date', 'receipt_date'], 'safe'],
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
        $query = ReceiptIssuer::find();

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
            'receipt_id' => $this->receipt_id,
            'receipt_partners' => $this->receipt_partners,
            'receipt_amount' => $this->receipt_amount,
            'receipt_issue_date' => $this->receipt_issue_date,
            'receipt_date' => $this->receipt_date,
        ]);

        $query->andFilterWhere(['like', 'receipt_number', $this->receipt_number]);

        return $dataProvider;
    }
}
