<?php
/**
 * mobiles
 * Created: 16.02.16 9:59
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\modules\mobile\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;


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
     * @var array
     */
    private $_period;
    /**
     * @var array
     */
    private $_numbers;
    /**
     * @var array
     */
    private $_employees;

    public function rules()
    {
        return [
            [['periodInput', 'items'], 'required'],
            ['periodInput', 'filter', 'filter' => function($value) {
                $period = explode(self::PERIOD_DELIMITER, $value);
                $this->_period = [
                    'month' => $period[0],
                    'year' => $period[1]
                ];
                return $value;
            }],
            ['items', 'filter', 'filter' => function($value) {
                foreach ($value as $item) {
                    if (is_numeric($item)) {
                        $this->_numbers[] = $item;
                    } else {
                        $this->_employees[] = $item;
                    }
                }
                return $value;
            }]
        ];
    }

    public function attributeLabels()
    {
        return [
            'periodInput' => 'Период',
            'items' => 'Номер или имя сотрудника'
        ];
    }


    public function search($params)
    {
        $query = ReportItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if (!$this->load($params) || !$this->validate()) {
            $query->where(['1' => false]);
            return $dataProvider;
        }

        /** @var Report $report */
        $report = Report::findOne(['period' => $this->_period]);

        if (!is_null($report)) {
            $query->andWhere(['reportId' => $report->primaryKey]);
        }

        $query->andWhere(['number' => ['$in' => $this->_numbers]]);

        $query->andWhere(['employee' => ['$in' => $this->_employees]]);

        return $dataProvider;
    }
}