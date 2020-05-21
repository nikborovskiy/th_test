<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * SearchUser represents the model behind the search form of `app\models\User`.
 */
class SearchUser extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'created_at', 'updated_at'], 'string', 'min' => 3, 'max' => 10],
            [['created_at', 'updated_at'], 'safe'],
            [['balance'], 'prepareBalanceValue'],
            [['balance'], 'number'],
        ];
    }

    /**
     * Замена хзапятой на точку
     * @param $attribute
     * @param $params
     */
    public function prepareBalanceValue($attribute, $params)
    {
        if (!empty($this->$attribute)) {
            $this->$attribute = str_replace(',', '.', $this->$attribute);
        }
    }

    /**
     * {@inheritdoc}
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
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'balance' => $this->balance,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        if (!empty($this->status)) {
            $query->andFilterWhere([
                'status' => $this->status,
            ]);
        }

        $query->andFilterWhere(['like', 'username', $this->username]);
        if (!\Yii::$app->getUser()->getIsGuest()) {
            $query->andOnCondition('id <> :id');
            $query->params([':id' => \Yii::$app->getUser()->getId()]);
        }

        return $dataProvider;
    }
}
