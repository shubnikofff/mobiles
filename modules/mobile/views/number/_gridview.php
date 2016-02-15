<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\grid\ActionColumn;

/* @var $this yii\web\View */

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table table-striped table-bordered table-hover'
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'number',
            'content' => function ($model, $key) {
                return Html::a($model->number, ['update', 'id' => (string)$key],['class' => 'number-update', 'data-pjax' => 0]);
            }
        ],
        [
            'label' => 'Держатель',
            'content' => function ($model) {
                return ($model->owner) ? "<p>{$model->owner->fullName}<br><span class='text-muted small'><em>{$model->owner->post}</em></span></p>" : "";
            },
        ],
        [
            'attribute' => 'destination',
            'value' => function ($data) {
                return $data->destinationlabel;
            },
        ],
        [
            'content' => function ($model) {
                return ($model->documents) ? "<span class='glyphicon glyphicon-file text-info'></span>" : "";
            },
        ],
        [
            'label' => 'Командировочный',
            'content' => function ($model) {
                return ($model->isTrip) ? "<span class='glyphicon glyphicon-ok text-success'></span>" : "";
            },
            'contentOptions' => [
                'class' => 'text-center'
            ]
        ],
        [
            'attribute' => 'limit',
            'value' => function ($data) {
                return (!empty($data->limit)) ? $data->limit : "-";
            },
            'contentOptions' => [
                'class' => 'text-center'
            ]
        ],
        [
            'attribute' => 'comment',
            'value' => function ($data) {
                return (!empty($data->comment)) ? $data->comment : "";
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{delete}',
            //'buttonOptions' => ['data-pjax']
        ],
    ],
]);
?>
