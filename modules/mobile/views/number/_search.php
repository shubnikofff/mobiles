<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\components\widgets\ActiveRadioList;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\NumberSearch */
/* @var $form yii\bootstrap\ActiveForm */
?>
    <div class="mobile-search">

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => ['id' => 'numberSearchForm']
]); ?>

<?= $form->field($model, 'searchText', [
    'inputOptions' => [
        'id' => 'searchTextInput',
        'placeHolder' => 'Введите номер или имя сотрудника',
    ],
    'labelOptions' => [
        'class' => 'sr-only'
    ],
    'inputTemplate' => '<div class="input-group input-group-lg">{input}' .
        Html::beginTag('span', ['class' => 'input-group-btn']) .
        Html::beginTag('a', ['href' => Url::to(['create']), 'id' => 'numberCreateLink', 'class' => 'btn btn-success number-create', 'data-toggle' => 'modal', 'data-target' => '#numberModal']) .
        Html::beginTag('span', ['class' => 'glyphicon glyphicon-plus']) . Html::endTag('span') .
        Html::endTag('a') .
        Html::endTag('span') . '</div>'
]) ?>

    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <div class="panel-title">
                <a class="h5" data-toggle="collapse" href="#advancedSearchPanelBody">Дополнительные параметры поиска</a>
            </div>
        </div>
        <div id="advancedSearchPanelBody" class="panel-collapse collapse" role="tabpanel"
             aria-labelledby="advanced-search-panel-body">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'operatorId')->dropDownList($model->getOperators()) ?>
                    </div>
                    <div class="col-md-3">
                        <?= ActiveRadioList::widget([
                            'activeField' => $form->field($model, 'destination'),
                            'items' => $model->getDestinations()
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'comment', ['inputOptions' => ['id' => 'numberCommentInput', 'placeHolder' => 'Фильтр по примечанию',]]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-right"> <?= Html::a('Сброс', ['index'], ['class' => 'btn btn-primary']) ?></div>

<?php ActiveForm::end(); ?>