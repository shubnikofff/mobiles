<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.05.15
 * Time: 11:45
 * @var $this \yii\base\View
 * @var $model \app\modules\mobile\models\Number
 */
use app\components\widgets\ModalView;

ModalView::begin([
    'modalOptions' => [
        'header' => "<p class='h3'>Номер {$model->number}<p>"
    ],
    'buttons' => [
        [
            'content' => 'Сохранить',
            'options' => ['class' => 'btn btn-primary'],
            'submit'
        ],
        [
            'content' => 'Удалить',
            'options' => ['class' => 'btn btn-danger'],
            'action' => \yii\helpers\Url::to(['delete', 'id' => (string)$model->getPrimaryKey()])
        ]
    ]
]);

echo $this->render('_form',['model'=>$model]);

ModalView::end();
