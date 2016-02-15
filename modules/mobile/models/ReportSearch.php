<?php

namespace app\modules\mobile\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReportSearch represents the model behind the search form about `app\modules\mobile\models\Report`.
 * @property $yearItems array
 * @property $operatorItems array
 * @property $monthItems array
 * @property $typeItems array
 */
class ReportSearch extends Model
{
    const YEAR_ALL = 'allYears';
    const OPERATOR_ALL = 'allOperators';
    const TYPE_OVERRUN = 'overrun';
    const TYPE_EXPENDITURE = 'expenditure';

    public $year;
    public $operator;
    public $type;
    public $above;
    private static $operatorList;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['year', 'in', 'range' => array_keys(self::getYearItems())],
            ['type', 'in', 'range' => array_keys(self::getTypeItems())],
            ['operator', 'in', 'range' => array_keys(self::getOperatorItems())],
            ['above', 'integer', 'min' => 0],
            ['above', function ($attribute) {
                if ($this->type == self::TYPE_EXPENDITURE && empty($this->$attribute)) {
                    $this->addError($attribute, "Необходимо указать сумму");
                }
            }, 'skipOnEmpty' => false]
        ];
    }

    public function init()
    {
        parent::init();
        $this->year = date('Y');
        $this->operator = self::OPERATOR_ALL;
        $this->type = self::TYPE_OVERRUN;
    }

    public function attributeLabels()
    {
        return [
            'year' => 'Год',
            'operator' => 'Оператор',
            'type' => 'Тип отчета',
            'above' => 'Сумма'
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
        $query = Report::find();
        $query->orderBy(['period.year' => SORT_DESC, 'period.month' => SORT_DESC]);
        $query->with('operator');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where(['1' => false]);
            return $dataProvider;
        }

        if ($this->year !== self::YEAR_ALL) {
            $query->andWhere(['period.year' => $this->year]);
        }

        if ($this->operator !== self::OPERATOR_ALL) {
            $query->andWhere(['operatorId' => Operator::findOne($this->operator)->getPrimaryKey()]);
        }

        return $dataProvider;
    }

    public static function getYearItems()
    {
        $items = [self::YEAR_ALL => 'Все года'];
        for ($i = 2014; $i <= 2020; $i++) {
            $items[$i] = $i;
        }
        return $items;
    }

    public static function getTypeItems()
    {
        return [
            self::TYPE_OVERRUN => 'Перерасход',
            self::TYPE_EXPENDITURE => 'Расход свыше'
        ];
    }

    public static function getOperatorItems()
    {
        if (self::$operatorList === null) {
            self::$operatorList = array_merge([self::OPERATOR_ALL => "Все операторы"],Operator::items());
        }
        return self::$operatorList;
    }
}
