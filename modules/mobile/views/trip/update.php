<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 17.04.15
 * Time: 16:41
 * @var $this \yii\web\View
 * @var $model \app\modules\mobile\models\Trip
 */

use app\components\widgets\ModalView;
use yii\helpers\Url;

ModalView::begin([
    'modalOptions' => [
        'header' => '<p class="h3">Изменение параметров командировки<p>'
    ],
    'buttons' => [
        [
            'content' => 'Сохранить',
            'options' => ['class' => 'btn btn-primary'],
            'submit'
        ],
        [
            'content' => 'Завершить',
            'options' => ['class' => 'btn btn-danger'],
            'action' => Url::to(['complete','id'=>(string)$model->getPrimaryKey()])
        ],
    ]
]);

echo $this->render('_form',['model'=>$model]);

ModalView::end();