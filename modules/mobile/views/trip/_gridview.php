<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 09.04.15
 * Time: 17:48
 */

use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use app\modules\mobile\models\Trip;
use app\components\widgets\ContainerLoader;

/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \app\modules\mobile\models\TripSearch
 */
?>

<?php ContainerLoader::begin([
    'linkSelector' => 'a.container-loader'
]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'summaryOptions' => ['class' => 'summary text-right'],
    'rowOptions' => function ($model) {
        switch ($model->status) {
            case Trip::STATUS_COMPLETE:
                return ['class' => 'success'];
            case Trip::STATUS_INCOMPLETE:
                return ['class' => 'warning'];
            case Trip::STATUS_EXPIRED:
                return ['class' => 'danger'];
        }
    },
    'columns' => [
        [
            'attribute' => 'mobileNumber',
            'content' => function ($model) {
                return Html::a($model->number->number, ['number/update', 'id' => (string)$model->number->getPrimaryKey()], ['class' => 'container-loader']);
            }
        ],
        [
            'attribute' => 'employeeName',
            'content' => function ($model) {
                return "<p>{$model->employee->fullName}<br><span class='text-muted small'><em>{$model->employee->post}</em></span></p>";
            }
        ],
        [
            'attribute' => 'rentNumberDate',
            'filter' => DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'rentNumberFrom',
                'attribute2' => 'rentNumberTo',
                'type' => DatePicker::TYPE_RANGE,
                'separator' => '-',
                'options' => ['placeholder' => 'c'],
                'options2' => ['placeholder' => 'по'],
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy'
                ]
            ]),
            'content' => function ($model) {
                return Yii::$app->formatter->asDate($model->numberPossession['from']->toDateTime(), 'long');
            }
        ],
        [
            'attribute' => 'complete',
            'filter' => [$searchModel::INCOMPLETE => 'Только не звершенные'],
            'content' => function ($model) {
                return ($model->complete) ? "<span class='glyphicon glyphicon-ok text-success'></span>" : "";
            },
            'contentOptions' => [
                'class' => 'text-center',
            ]
        ],
        'destination',
        [
            'header' => 'Действия',
            'buttons' => [
                'view' => function ($url, $model) {
                    return $model->complete ? Html::a('<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>', $url, ['class' => 'container-loader']) : '';
                },
                'update' => function ($url, $model) {
                    return !$model->complete ? Html::a('<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', $url, ['class' => 'container-loader']) : '';
                },
            ],
            'class' => ActionColumn::className(),
            'contentOptions' => [
                'class' => 'text-center'
            ]
        ]

    ]
]); ?>


<?php ContainerLoader::end() ?>

<?php $this->registerJs('teleport.$commonContainer.on("modalView.actionDone",function(){jQuery("div#tripGridView").load(document.URL);});') ?>