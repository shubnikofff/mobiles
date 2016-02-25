<?php
/**
 * mobiles
 * Created: 24.02.16 15:31
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\commands;
use app\modules\directory\models\Employee;
use app\modules\mobile\models\Number;
use yii\console\Controller;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * HistoryController
 */

class HistoryController extends Controller
{
    public function actionUpdate()
    {
        foreach(Number::find()->all() as $number) {
            $history = [];
            foreach($number->history as $item) {
                /** @var Employee $employee */
                $employee = Employee::findOne(['id' => $item['ownerId']]);
                if (!is_null($employee)) {
                    $newItem = [
                        'ownerName' => $employee->fullName,
                        'ownerPost' => $employee->post,
                    ];
                    if (isset($item['rentDate'])) {
                        $newItem['rentDate'] = $item['rentDate'];
                    }
                    if (isset($item['returnDate'])) {
                        $newItem['returnDate'] = $item['returnDate'];
                    }
                    $history[] = $newItem;
                }
                $number->history = $history;
                $number->save(false);
            }
        }
    }
}