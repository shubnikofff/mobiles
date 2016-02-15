<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.05.15
 * Time: 10:20
 * @var $this \yii\base\View
 * @var $model \app\modules\mobile\models\Number
 */
use app\components\widgets\ModalView;

ModalView::begin([
    'modalOptions' => [
        'header' => '<p class="h3">Новый номер<p>'
    ],
    'buttons' => [
        [
            'content' => 'Создать',
            'options' => ['class' => 'btn btn-success'],
            'submit'
        ]
    ]
]);

echo $this->render('_form',['model'=>$model]);

ModalView::end();

