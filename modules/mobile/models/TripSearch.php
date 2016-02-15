<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 09.04.15
 * Time: 14:43
 */

namespace app\modules\mobile\models;

use app\modules\directory\models\Employee;
use Yii;
use yii\data\ActiveDataProvider;
use \MongoDate;
use yii\mongodb\Query;

class TripSearch extends Trip
{
    const INCOMPLETE = 'inComplete';
    public $rentNumberFrom;
    public $rentNumberTo;

    public function init()
    {
        $this->trigger(self::EVENT_INIT);
    }

    public function scenarios()
    {
        return [
            'search' => ['mobileNumber', 'employeeName', 'rentNumberFrom', 'rentNumberTo', 'complete', 'destination']
        ];
    }

    public function rules()
    {
        return [
            ['mobileNumber', 'number'],
            [['employeeName', 'destination'], 'trim'],
            [['rentNumberFrom', 'rentNumberTo'], 'date', 'format' => 'dd.mm.yyyy'],
            ['complete', 'safe']
        ];
    }

    public function search(array $params)
    {
        $query = Trip::find();
        $query->with('number', 'employee');
        $query->orderBy(['numberPossession.from' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!empty($this->mobileNumber)) {
            $numberIds = array_map(
                function ($item) {
                    return $item['_id'];
                },
                Number::find()->select(['_id'])->asArray()->where(['like', 'number', $this->mobileNumber])->all()
            );
            $query->andWhere(['in', 'numberId', $numberIds]);
        }

        if (!empty($this->employeeName)) {
            $employeeIds = array_map(
                function ($item) {
                    return (int)$item['id'];
                },
                Employee::findByName($this->employeeName, true)->select('id')->asArray()->all()
            );
            $query->andWhere(['in', 'employeeId', $employeeIds]);
        }

        if ($this->complete === self::INCOMPLETE) {
            $query->andWhere(['complete' => false]);
        }

        if ($this->rentNumberFrom !== $this->rentNumberTo) {
            $query->andWhere(['numberPossession.from' => [
                '$gt' => new MongoDate(strtotime($this->rentNumberFrom)),
                '$lte' => new MongoDate(strtotime($this->rentNumberTo))
            ]]);
        }

        $query->andFilterWhere(['like', 'destination', $this->destination]);

        return $dataProvider;
    }
}