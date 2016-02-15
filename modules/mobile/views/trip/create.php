<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 17.04.15
 * Time: 16:31
 * @var $this \yii\web\View
 * @var $model \app\modules\mobile\models\Trip
 */

use app\components\widgets\ModalView;

ModalView::begin([
    'modalOptions' => [
        'header' => '<p class="h3">Создание командировки<p>'
    ],
    'buttons' => [
        [
            'content' => 'Создать',
            'options' => ['class' => 'btn btn-success'],
            'submit'
        ],
    ]
]);

echo $this->render('_form',['model'=>$model]);

ModalView::end();