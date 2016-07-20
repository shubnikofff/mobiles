<?php
/**
 * mobiles
 * Created: 19.07.16 13:47
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\modules\mobile\models;

use yii\data\ActiveDataProvider;
use yii\validators\NumberValidator;
use InvalidArgumentException;
use yii\grid\SerialColumn;
use yii\data\Pagination;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ExpenditureReportMaker
 */
class ExpenditureReportMaker implements ReportMaker
{
    private $sum;

    public function __construct($sum)
    {
        if (!(new NumberValidator(['min' => 0]))->validate($sum)) {
            throw new InvalidArgumentException(__METHOD__);
        }
        $this->sum = (int)$sum;
    }

    public function getDataProvider($reportId)
    {
        $dataProvider = new ActiveDataProvider();

        $pagination = new Pagination([
            'pageSize' => 50
        ]);

        $query = ReportItem::find()->where([
            'reportId' => $reportId,
            'expenditure' => ['$gte' => $this->sum]
        ]);

        $dataProvider->query = $query;
        $dataProvider->pagination = $pagination;

        return $dataProvider;
    }

    public function getColumns()
    {
        return [
            ['class' => SerialColumn::className()],
            'employee',
            'number',
            'limit',
            [
                'attribute' => 'expenditure',
                'format' => 'decimal'
            ],
            'comment'
        ];
    }

    public function getReportHeader($operator, $timestamp)
    {
        return "{$operator}: расход свыше {$this->sum} руб. за " . \Yii::$app->formatter->asDate($timestamp, 'LLLL Y');
    }

}