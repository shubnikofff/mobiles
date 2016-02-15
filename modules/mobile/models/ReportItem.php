<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 17.03.15
 * Time: 10:03
 */

namespace app\modules\mobile\models;

use yii\mongodb\ActiveRecord;
use app\modules\mobile\models\Report;

class ReportItem extends ActiveRecord{

    public static function collectionName()
    {
        return 'mobile.report.item';
    }


    public function attributes()
    {
        return [
            '_id',
            'reportId',
            'number',
            'employee',
            'limit',
            'expenditure',
            'overrun',
            'comment'
        ];
    }

    public function getReport() {
        return $this->hasOne(Report::className(),['_id' => 'reportId']);
    }
}