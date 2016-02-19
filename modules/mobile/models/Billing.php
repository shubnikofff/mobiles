<?php
/**
 * mobiles
 * Created: 16.02.16 9:59
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\modules\mobile\models;

use app\modules\directory\models\Employee;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\mongodb\Query;
use yii\mongodb\validators\MongoIdValidator;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * Billing
 */
class Billing extends Model
{
    const PERIOD_DELIMITER = '/';
    /**
     * @var  string month and year split via delimiter
     */
    public $periodInput;
    /**
     * @var  array numbers or employees
     */
    public $items;
    /**
     * @var mixed
     */
    public $operatorId;
    /**
     * @var array
     */
    private $_period;
    /**
     * @var array
     */
    private $_numbers = [];
    /**
     * @var array
     */
    private $_employees = [];

    public function rules()
    {
        return [
            [['periodInput', 'items', 'operatorId'], 'required'],
            ['periodInput', 'filter', 'filter' => function ($value) {
                $period = explode(self::PERIOD_DELIMITER, $value);
                $this->_period = [
                    'month' => $period[0],
                    'year' => $period[1]
                ];
                return $value;
            }],
            ['operatorId', MongoIdValidator::className(), 'forceFormat' => 'object'],
            ['operatorId', 'exist', 'targetClass' => Operator::className(), 'targetAttribute' => '_id'],
            ['items', 'filter', 'filter' => function ($value) {
                foreach ($value as $item) {
                    if (is_numeric($item)) {
                        $this->_numbers[] = $item;
                    } else {
                        $this->_employees[] = $item;
                    }
                    $res[$item] = $item;
                }
                return $value;
            }]
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ReportItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'employee' => SORT_ASC
                ]
            ]
        ]);

        if (!$this->load($params) || !$this->validate()) {
            $query->where(['1' => false]);
            return $dataProvider;
        }

        /** @var Report $report */
        $report = Report::findOne(['operatorId' => $this->operatorId, 'period' => $this->_period]);

        $query->andWhere(['reportId' => $report->primaryKey]);

        $query->andWhere(['or', ['number' => ['$in' => $this->_numbers]], ['employee' => ['$in' => $this->_employees]]]);

        return $dataProvider;
    }

    /**
     * @param $queryParam
     * @return array
     */
    static public function itemsList($queryParam)
    {
        if (is_numeric($queryParam)) {
            $query = new Query();
            $query->select(['_id' => false, 'number' => 'text'])->from(Number::collectionName())->where(['like', 'number', $queryParam]);
        } else {
            $query = new \yii\db\Query();
            $query->select(["CONCAT_WS(' ',last_name, first_name, middle_name) AS number"])->from(Employee::tableName())->where(['like', 'last_name', $queryParam]);
        }

        $items['results'] = array_map(function ($item) {
            $item['id'] = $item['number'];
            return $item;
        }, $query->limit(10)->all());

        return $items;
    }

    static public function operatorList()
    {
        return Operator::items();
    }
}