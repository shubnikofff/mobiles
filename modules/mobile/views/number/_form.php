<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.05.15
 * Time: 10:39
 * @var $model \app\modules\mobile\models\Number
 * @var $this \yii\web\View
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;
use yii\web\JsExpression;
use yii\helpers\Url;
use app\modules\mobile\models\Operator;
use app\components\widgets\ActiveRadioList;
use kartik\file\FileInput;
use app\modules\mobile\models\Document;
use yii\web\View;

?>

<?php $form = ActiveForm::begin([
    'action' => Url::to($model->isNewRecord ? ['create'] : ['update', 'id' => (string)$model->getPrimaryKey()]),
    'enableClientValidation' => false,
]) ?>

<?php if (Yii::$app->session->hasFlash('numberSaved')) {
    echo Alert::widget([
        'options' => [
            'class' => 'alert-success',
        ],
        'body' => Yii::$app->session->getFlash('numberSaved')
    ]);
} ?>

<?= $model->isNewRecord ? $form->field($model, 'number', ['enableClientValidation' => true]) : null ?>

<?php $formatJs = <<< 'JS'
var formatEmployeeList = function(item) {
    return '<b>' + item.name + '</b><p><small>' + item.post + ' (' + item.division + ')</small></p>';
}
var formatEmployeeSelection = function (employee) {
    return employee.name || employee.text;
}
JS;
$this->registerJs($formatJs, View::POS_HEAD); ?>

<?= $form->field($model, 'ownerId')->widget(\kartik\select2\Select2::className(), [
    'initValueText' => $model->owner->fullName,
    'showToggleAll' => false,
    'options' => [
        'placeholder' => 'Имя сотрудника...'
    ],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 3,
        'ajax' => [
            'url' => \yii\helpers\Url::to(['owner-list']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }'),
            'delay' => 250
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('formatEmployeeList'),
        'templateSelection' => new JsExpression('formatEmployeeSelection'),
    ]
]) ?>

<?= $form->field($model, 'operatorId')->dropDownList(Operator::items()) ?>

<?= ActiveRadioList::widget([
    'activeField' => $form->field($model, 'destination'),
    'items' => $model::destinationItems(),
    'options' => ['class' => 'btn-group  btn-group-sm']
]) ?>

<?= $form->field($model, 'limit', ['enableClientValidation' => true]) ?>

<?= $form->field($model, 'options')->checkboxList($model::optionItems()) ?>

<?php if (!$model->isNewRecord): ?>
    <div class="form-group">
        <label class="control-label">Новые документы</label>
        <?= FileInput::widget([
            'model' => new Document(),
            'attribute' => 'file[]',
            'options' => [
                'multiple' => true,
            ],
            'pluginOptions' => [
                'showPreview' => false,
                'uploadUrl' => Url::to(['attach-document', 'ownerId' => (string)$model->getPrimaryKey()]),
                'maxFileCount' => 10,
            ],
            'pluginEvents' => [
                'filebatchuploadsuccess' => 'function(e, data){$(\'#documents-panel-body\').empty().append(data.response.view);}'
            ],

        ]); ?>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab">
            <div class="panel-title">
                <a class="h5" data-toggle="collapse" href="#documents-panel-body">Прикрепленные документы</a>
            </div>
        </div>
        <div id="documents-panel-body" class="panel-collapse collapse list-group" role="tabpanel">
            <?= $this->render('_document', ['ownerId' => $model->getPrimaryKey()]) ?>
        </div>
    </div>
    <?= $this->render('_history', ['history' => $model->history]) ?>
<?php endif ?>

<?= $form->field($model, 'comment')->textarea() ?>

<?php ActiveForm::end() ?>
