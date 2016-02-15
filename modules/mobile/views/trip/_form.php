<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 17.04.15
 * Time: 16:41
 * @var $this \yii\web\View
 * @var $model \app\modules\mobile\models\Trip
 */

use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;
use yii\web\JsExpression;

?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'layout' => 'horizontal',
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-4',
            'offset' => 'col-sm-offset-4',
            'wrapper' => 'col-sm-7',
        ],
    ],
]); ?>

<?= $form->field($model, 'mobileNumber')->widget(Typeahead::className(), [
    'pluginOptions' => ['highlight' => true],
    'dataset' => [
        [
            'remote' => Url::to(['number-list']) . '?q=%QUERY',
        ]
    ]
]) ?>

<?= $form->field($model, 'employeeName')->widget(Typeahead::className(),[
    'pluginOptions' => ['highlight' => true],
    'pluginEvents' => [
        "typeahead:selected" => 'function(e, suggestion){$(\'input[name="'.$model->formName().'[employeePost]"]\').val(suggestion[\'post\']);}',
    ],
    'dataset' => [
        [
            'templates' => [
                'suggestion' => new JsExpression("Handlebars.compile('<p>{{value}}</p><p class=\"text-muted small\"><em>{{post}}</em></p>')")
            ],
            'remote' => Url::to(['/directory/employee/auto-complete']) . '?q=%QUERY',
            'limit' => 5
        ]
    ]
]) ?>

<?= $form->field($model, 'employeePost'); ?>

<?= $form->field($model, 'beginDate')->widget(DatePicker::className(), [
    'attribute2' => 'endDate',
    'type' => DatePicker::TYPE_RANGE,
    'separator' => '-',
    'options' => ['placeholder' => 'c'],
    'options2' => ['placeholder' => 'по'],
    'pluginOptions' => [
        'autoclose' => true,
    ]
])->label('Дата командировки') ?>

<?= $form->field($model, 'rentNumberDate')->widget(DatePicker::className(), [
    'type' => DatePicker::TYPE_INPUT,
    'pluginOptions' => [
        'autoclose' => true,
        'todayHighlight' => true,
    ]
]); ?>

<?= $form->field($model, 'destination')->textarea(); ?>

<?php ActiveForm::end(); ?>