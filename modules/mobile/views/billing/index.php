<?php
/**
 * mobiles
 * Created: 16.02.16 12:51
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */
use kartik\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\Pjax;

/**
 * @var $this \yii\web\View
 * @var $searchModel \app\modules\mobile\models\Billing
 * @var $dataProvider \yii\data\ActiveDataProvider
 */
$this->title = "Биллинг";
?>
<div class="billing">

    <?= Html::pageHeader($this->title) ?>

    <?php $form = ActiveForm::begin([
        'id' => 'billing-form',
        'method' => 'get',
        'layout' => 'inline'
    ]) ?>

    <?= $form->field($searchModel, 'periodInput')->widget(DateControl::className(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'options' => ['placeholder' => 'Период...'],
            'pluginOptions' => [
                'autoclose' => true,
                'minViewMode' => 1,
            ],
        ],
        'saveFormat' => 'php:n' . $searchModel::PERIOD_DELIMITER . 'Y',
        'displayFormat' => 'php:F Y'

    ]) ?>

    <?= $form->field($searchModel, 'operatorId')->dropDownList($searchModel::operatorList())?>

    <?= $form->field($searchModel, 'items')->widget(Select2::className(), [
        'showToggleAll' => false,
        'options' => [
            'multiple' => true,
            'placeholder' => 'Номер или имя сотрудника...'
        ],
        'pluginOptions' => [
            'width' => '600',
            'allowClear' => true,
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => \yii\helpers\Url::to(['items-list']),
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                'delay' => 250
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(item) {return item.number; }'),
            'templateSelection' => new JsExpression('function (item) {return item.number; }'),
        ]
    ]) ?>

    <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>

    <?php Pjax::begin([
        'formSelector' => '#billing-form',
        'options' => ['style' => 'padding: 20px 0']
    ]) ?>

    <?= $this->render('_grid', ['dataProvider' => $dataProvider]) ?>

    <?php Pjax::end(); ?>

</div>