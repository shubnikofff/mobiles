<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 30.03.15
 * Time: 15:41
 */

namespace app\modules\mobile\models;

use yii\base\Model;


class ReportOutsideItem extends Model{

    public $id;
    public $number;
    public $nowInDb = false;
    public $expenditure;

    public function init()
    {
        parent::init();
        $this->nowInDb = Number::find()->where(['number'=>$this->number])->count() ? true : false;
    }


}