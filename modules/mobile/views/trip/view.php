<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 16.04.15
 * Time: 17:20
 * @var $this \yii\web\View
 * @var $model \app\modules\mobile\models\Trip
 */
use app\components\widgets\ModalView;
use yii\widgets\DetailView;
use yii\helpers\Html;
use app\modules\mobile\models\Trip;
use yii\bootstrap\Alert;

?>
<?php ModalView::begin([
    'modalOptions' => [
        'header' => "<p class='h3'>Иформация о командировке</p>",
    ],
]) ?>

<?php if (Yii::$app->session->hasFlash('tripSaved')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success',
        ],
        'body' => Yii::$app->session->getFlash('tripSaved')
    ]);
}?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'number.number',
        'employee.fullName',
        'employee.post',
        [
            'attribute' => 'statusName',
            'value' => Html::tag('div',$model->getStatusName(),['class' => $model->status === Trip::STATUS_EXPIRED ? 'text-danger' : 'text-success']),
            'format' => 'html'
        ],
        [
            'attribute' => 'beginDate',
            'value' => $model->duration['from']->sec,
            'format' => ['date', 'long'],
        ],
        [
            'attribute' => 'endDate',
            'value' => $model->duration['to']->sec,
            'format' => ['date', 'long'],
        ],
        [
            'attribute' => 'rentNumberDate',
            'value' => $model->numberPossession['from']->sec,
            'format' => ['date', 'long'],
        ],
        [
            'attribute' => 'returnNumberDate',
            'value' => $model->numberPossession['to']->sec,
            'format' => ['date', 'long'],
        ],
        'destination',
    ]
]);?>

<?php ModalView::end() ?>