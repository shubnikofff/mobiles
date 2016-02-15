<?php

namespace app\modules\mobile\models;

use Yii;
use yii\mongodb\ActiveRecord;
use yii\validators\NumberValidator;

/**
 * Модель отчета
 *
 * @property Operator $operator
 * @property \MongoId $operatorId
 * @property array $period
 * @property int $periodTimeStamp
 * @property array $outSideDb
 * @property array $outSideOperator
 * @property float $totalOverrun
 */
class Report extends ActiveRecord
{
    public $items;

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'mobile.report';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'operatorId',
            'period',
            'outSideDb',
            'outSideOperator'
        ];
    }

    public function init()
    {
        $this->period = [
            'month' => null,
            'year' => null
        ];
        $this->outSideDb = [];
        $this->outSideOperator = [];
        parent::init();
    }


    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['operatorId', 'exist', 'targetClass' => Operator::className(), 'targetAttribute' => '_id'],
            ['period', function ($attribute) {
                $monthValidator = new NumberValidator(['integerOnly' => true, 'min' => 1, 'max' => 12]);
                $yearValidator = new NumberValidator(['integerOnly' => true, 'min' => 2014]);
                if (!$monthValidator->validate($this->period['month']) || !$yearValidator->validate($this->period['year'])) {
                    $this->addError($attribute, "«{attribute}» имеет неверный формат");
                }
            }]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'period' => 'Период',
            'outSideDB' => 'Вне базы данных',
            'outSideOperator' => 'Вне отчета опреатора',
        ];
    }

    public function getPeriodTimeStamp()
    {
        return mktime(null, null, null, $this->period['month'], 1, $this->period['year']);
    }

    public function getOverrunItems()
    {
        return $this->hasMany(ReportItem::className(), ['reportId' => '_id'])->where(['overrun' => ['$exists' => true]]);
    }

    public function getExpenditureItems($above = 3000)
    {
        return $this->hasMany(ReportItem::className(), ['reportId' => '_id'])->where(['expenditure' => ['$gte' => $above]]);
    }

    public function getItems()
    {
        return $this->hasMany(ReportItem::className(), ['reportId' => '_id']);
    }

    public function getOperator()
    {
        return $this->hasOne(Operator::className(), ['_id' => 'operatorId']);
    }

    /**
     * @param OperatorXML $xml
     * @return Report|null
     */
    public static function generate(OperatorXML $xml)
    {
        $operator = Operator::findOne(['contract' => $xml->getContract()]);
        $operatorId = $operator !== null ? $operator->getPrimaryKey() : null;

        $period = [
            'month' => $xml->getMonth(),
            'year' => $xml->getYear()
        ];

        $report = self::findOne(['operatorId' => $operatorId, 'period' => $period]);

        if (!$report instanceof Report) {
            $report = new Report();
            $report['operatorId'] = $operatorId;
            $report['period'] = $period;
        } else {
            $report->outSideDb = [];
            $report->outSideOperator = [];
        }

        if (!$report->save()) {
            return null;
        }
        $report->unlinkAll('items', true);

        $outSideOperator = Number::find()->where(['operatorId' => $report->operatorId])->indexBy('number')->all();
        $outSideDB = [];

        foreach ($xml->getItems() as $item) {
            $number = $item['number'];
            if (array_key_exists($number, $outSideOperator)) {
                $report->addItem($outSideOperator[$number], $item['expenditure']);
                unset($outSideOperator[$number]);
            } else {
                $outSideDB[] = $item;
            }
        }

        $report->outSideOperator = array_map(function ($item) {
            return ['number' => $item->number, 'id' => $item->getPrimaryKey()];
        }, array_values($outSideOperator));
        $report->outSideDb = $outSideDB;
        $report->save(false);

        return $report;
    }

    public function addItem(Number $number, $expenditure)
    {
        $item = new ReportItem();
        $item['number'] = $number['number'];
        $item['employee'] = $number->owner['fullName'];
        $item['limit'] = $number['limit'];
        $item['expenditure'] = $expenditure;
        $item['comment'] = $number['comment'];

        if ($number->accounting && $number['limit'] < $expenditure) {
            $item['overrun'] = round($expenditure - $number['limit'], 2);
        }

        $item->link('report', $this);
    }

    /**
     * @return float
     * @throws \yii\mongodb\Exception
     */
    public function getTotalOverrun()
    {
        $collection = ReportItem::getCollection();
        $pipeline = [
            [
                '$match' => [
                    'reportId' => $this->getPrimaryKey(),
                    'overrun' => ['$exists' => 1]
                ]
            ],
            [
                '$group' => [
                    '_id' => 'total',
                    'total' => ['$sum' => '$overrun']
                ]
            ]
        ];

        return $collection->aggregate($pipeline)[0]['total'];
    }

    /**
     * @return array
     * @throws \yii\mongodb\Exception
     */
    public function getOutsideDb()
    {
        return array_filter($this->outSideDb, function ($item) {
            return !(Number::find()->where(['number' => $item['number']])->exists());
        });
    }

    public function getOutsideOperator() {
        return array_filter($this->outSideOperator, function ($item) {
            $number = Number::findOne($item['id']);
            return ($number !== null && $number['operatorId'] == $this->operatorId) ? true : false;
        });
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $this->unlinkAll('items', true);
            return true;
        } else {
            return false;
        }
    }


}
