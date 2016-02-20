<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 20.02.15
 * Time: 12:15
 */

namespace app\modules\directory\controllers;


use app\modules\directory\models\Employee;
use yii\helpers\Json;
use yii\web\Controller;
//TODO:controller should be deleted
class EmployeeController extends Controller{

    public function actionAutoComplete($q = null) {
        $result = [];
        /** @var $item Employee */
        foreach(Employee::findByName($q, true)->all() as $item) {
            $result[] = ['value' => $item->fullName, 'post' => $item->post];
        }
        echo Json::encode($result);
    }
}