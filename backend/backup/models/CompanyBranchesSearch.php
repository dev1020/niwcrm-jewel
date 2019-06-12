<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CompanyBranches;

/**
 * CompanyBranchesSearch represents the model behind the search form about `backend\models\CompanyBranches`.
 */
class CompanyBranchesSearch extends CompanyBranches
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['branchaddress', 'branchname', 'branchcontact_no'], 'safe'],
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
        $query = CompanyBranches::find();

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
        ]);

        $query->andFilterWhere(['like', 'branchaddress', $this->branchaddress])
            ->andFilterWhere(['like', 'branchname', $this->branchname])
            ->andFilterWhere(['like', 'branchcontact_no', $this->branchcontact_no]);

        return $dataProvider;
    }
}
