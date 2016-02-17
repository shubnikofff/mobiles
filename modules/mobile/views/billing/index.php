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
        'method' => 'get',
        'layout' => 'inline'
    ]) ?>

    <?= $form->field($searchModel, 'periodInput')->widget(DateControl::className(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'pluginOptions' => [
                'autoclose' => true,
                'minViewMode' => 1,
            ],
        ],
        'saveFormat' => 'php:n' . $searchModel::PERIOD_DELIMITER . 'Y',
        'displayFormat' => 'php:F Y'

    ]) ?>

    <?= $form->field($searchModel, 'items')->widget(Select2::className(), [
        'options' => [
            'multiple' => true,
        ],
        'pluginOptions' => [
            'width' => '600px'
        ],
        'data' => [1,2,3]
    ]) ?>

    <?= Html::submitButton('Ок', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>

</div>