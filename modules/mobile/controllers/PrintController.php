<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 28.05.15
 * Time: 10:27
 */

namespace app\modules\mobile\controllers;

use app\modules\mobile\models\Number;
use yii\web\Controller;

class PrintController extends Controller{
    public function actionAll() {
        $numbers = Number::find()->with('owner')->orderBy('number')->all();
        return $this->render('all', ['numbers' => $numbers]);
    }
}