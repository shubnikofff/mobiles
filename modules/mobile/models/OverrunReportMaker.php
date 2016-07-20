<?php
/**
 * mobiles
 * Created: 19.07.16 13:45
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\modules\mobile\models;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\SerialColumn;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * OverrunReportMaker
 */

class OverrunReportMaker implements ReportMaker
{
    public function getDataProvider($reportId)
    {
        $dataProvider = new ActiveDataProvider();
        
        $pagination = new Pagination([
            'pageSize' => 50
        ]);
        
        $query = ReportItem::find()->where([
            'reportId' => $reportId,
            'overrun' => ['$exists' => true]
        ]);
        
        $dataProvider->query = $query;
        $dataProvider->pagination = $pagination;
        
        return $dataProvider;
    }

    public function getColumns()
    {
        return [
            ['class' => SerialColumn::className()],
            'number',
            'employee',
            'limit',
            [
                'attribute' => 'expenditure',
                'value' => function($item) {return str_replace('.',',',$item['expenditure']);}
            ],
            [
                'attribute' => 'expenditure',
                'value' => function($item) {return str_replace('.',',',$item['overrun']);}
            ],
            'comment'
        ];
    }

    public function getReportHeader($operator, $timestamp)
    {
        return "{$operator}: перерасход за " . \Yii::$app->formatter->asDate($timestamp, 'LLLL Y');
    }
    
}