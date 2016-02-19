<?php

namespace app\modules\mobile\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\directory\models\Employee;

/**
 * NumberSearch represents the model behind the search form about `app\modules\mobile\models\Number`.
 */
class NumberSearch extends Number
{
    const DESTINATION_ANY = 'anyDestination';
    const OPERATOR_ANY = 'anyOperator';
    public $searchText;

    public function init()
    {
        parent::init();
        $this->operatorId = self::OPERATOR_ANY;
        $this->destination = self::DESTINATION_ANY;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['searchText', 'comment'], 'trim'],
            [['operatorId', 'destination'], 'safe']
        ];
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
        $query = Number::find();
        $query->with('owner', 'documents');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'number',
                'destination',
                'comment'
            ],
            'defaultOrder' => [
                'number' => SORT_ASC
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (is_numeric($this->searchText)) {
            $query->andFilterWhere(['like', 'number', $this->searchText]);
        } elseif (!empty($this->searchText)) {
            $ownerId = [];
            foreach (Employee::findByName($this->searchText, true)->all() as $owner) {
                $ownerId[] = $owner->getPrimaryKey();
            }
            $query->andWhere(['in', 'ownerId', $ownerId]);
        }

        if ($this->operatorId !== self::OPERATOR_ANY) {
            $query->andFilterWhere(['operatorId' => $this->operatorId]);
        }

        if ($this->destination !== self::DESTINATION_ANY) {
            $query->andFilterWhere(['destination' => $this->destination]);
        }
        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }

    public function getOperators()
    {
        return array_merge([self::OPERATOR_ANY => 'Выберите оператора'], Operator::items());
    }

    public function getDestinations()
    {
        return array_merge([self::DESTINATION_ANY => 'Любая'], parent::destinationItems());
    }

}
