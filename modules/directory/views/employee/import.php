<?php
/**
 * mobiles
 * Created: 22.03.16 15:12
 * @copyright Copyright (c) 2016 OSKR NIAEP
 * @var $this \yii\web\View
 * @var $model \app\modules\directory\models\ImportForm
 */
use yii\widgets\ActiveForm;
use kartik\helpers\Html;

$this->title = "Импорт данных";
?>
<div>

    <?= Html::pageHeader($this->title) ?>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'datafile')->fileInput() ?>

    <?= Html::submitButton('<span class="glyphicon glyphicon-upload"></span> Загрузить', ['class' => 'btn btn-primary']) ?>

    <?php ActiveForm::end() ?>

</div>

<?php if ($model->notImported->totalCount): ?>

    <div style="padding-top: 15px">

        <h3>Сотрудники, не найденные в базе по табельному номеру</h3>

        <?= \yii\grid\GridView::widget([
            'dataProvider' => $model->notImported,
            'columns' => [
                [
                    'attribute' => 'personnel_number',
                    'label' => 'Табельный номер'
                ],
                [
                    'attribute' => 'name',
                    'label' => 'ФИО'
                ],
                [
                    'attribute' => 'post',
                    'label' => 'Должность'
                ],
                [
                    'attribute' => 'division1',
                    'label' => 'Подразделение 1'
                ],
                [
                    'attribute' => 'division2',
                    'label' => 'Подразделение 2'
                ],
                [
                    'attribute' => 'division3',
                    'label' => 'Подразделение 3'
                ],
                [
                    'attribute' => 'group',
                    'label' => 'Группа сотрудников'
                ]
            ]
        ]) ?>

    </div>

<?php endif; ?>
