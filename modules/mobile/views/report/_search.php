<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\ReportSearch */
/* @var $form yii\widgets\ActiveForm */
?>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'id' => 'reportSearchForm',
                'action' => ['index'],
                'method' => 'get',
                'enableClientValidation' => false,
                'layout' => 'inline',
            ]); ?>

            <?= $form->field($model, 'year')->dropDownList($model->yearItems) ?>

            <?= $form->field($model, 'operator')->dropDownList($model->operatorItems) ?>

            <?= $form->field($model, 'type')->dropDownList($model->typeItems) ?>

            <?= $form->field($model, 'above')->textInput(['id' => 'reportAbove', 'placeholder' => $model->getAttributeLabel('above'), 'disabled' => ($model->type == $model::TYPE_OVERRUN) ? true : false]) ?>

            <?= Html::a('Сброс',['index'],['class'=>'btn btn-default']);?>

            <?php ActiveForm::end() ?>
        </div>
    </div>

<?php
$expenditureType = $model::TYPE_EXPENDITURE;
$script = <<<JS
    $('select, input','form#reportSearchForm').on('change',function() {
        if(this.value === '{$expenditureType}') {
            $('input#reportAbove').prop("disabled",false).focus();
        } else {
            $(this).closest('form').submit();
        }
    });
JS;
$this->registerJs($script);
?>