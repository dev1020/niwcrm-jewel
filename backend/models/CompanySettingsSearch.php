<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CompanySettings;

/**
 * CompanySettingsSearch represents the model behind the search form about `backend\models\CompanySettings`.
 */
class CompanySettingsSearch extends CompanySettings
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company_id'], 'integer'],
            [['brand_name', 'loyalty_bonus_percentage', 'referral_bonus_percentage', 'site_logo', 'multi_store'], 'safe'],
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
        $query = CompanySettings::find();

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
            'company_id' => $this->company_id,
        ]);

        $query->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'loyalty_bonus_percentage', $this->loyalty_bonus_percentage])
            ->andFilterWhere(['like', 'referral_bonus_percentage', $this->referral_bonus_percentage])
            ->andFilterWhere(['like', 'site_logo', $this->site_logo])
            ->andFilterWhere(['like', 'multi_store', $this->multi_store]);

        return $dataProvider;
    }
}
