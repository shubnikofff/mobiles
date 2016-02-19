<?php
/**
 * mobiles
 * Created: 18.02.16 14:40
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

/**
 * @var  \yii\data\ActiveDataProvider $dataProvider
 */
?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => ['class' => 'table table-hover'],
    'formatter' => ['class' => \yii\i18n\Formatter::className(), 'nullDisplay' => '-'],
    'columns' => [
        'number',
        'employee',
        'limit',
        'expenditure',
        [
            'attribute' => 'overrun',
            'content' => function ($model) {
                return is_null($model['overrun']) ? 0 : $model['overrun'];
            }
        ],
        'comment'
    ]
]) ?>
