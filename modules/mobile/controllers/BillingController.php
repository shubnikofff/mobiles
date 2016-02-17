<?php
/**
 * mobiles
 * Created: 16.02.16 12:45
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\modules\mobile\controllers;
use app\modules\mobile\models\Billing;
use yii\web\Controller;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * BillingController
 */

class BillingController extends Controller
{
    public function actionIndex()
    {
        $model = new Billing();
        $dataProvider = $model->search(\Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $model,
            'dataProvider' => $dataProvider
        ]);
    }
}