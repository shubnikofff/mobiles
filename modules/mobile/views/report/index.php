<?php

use kartik\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\bootstrap\Alert;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\mobile\models\ReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчёты';
?>
<div>

    <?= Html::pageHeader($this->title) ?>

    <?php if (Yii::$app->session->hasFlash('reportCreated')) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-success',
            ],
            'body' => Yii::$app->session->getFlash('reportCreated')
        ]);
    }
    ?>

    <div class="form-group">
        <label class="control-label">Новый отчёт</label>
        <?= FileInput::widget([
            'name' => 'operatorReport',
            'id' => 'operatorReport',
            'pluginOptions' => [
                'showPreview' => false,
                'initialCaption' => 'Данные оператора',
                'allowedFileExtensions' => ['xml'],
                'msgInvalidFileExtension' => 'Для создания отчета необходим файл в формате XML',
                'uploadUrl' => Url::to(['create']),
                'elErrorContainer' => '#uploadError'
            ],
        ]) ?>
        <p id="uploadError"></p>
    </div>


    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'list-group'],
        'itemOptions' => ['class' => 'list-group-item'],
        'itemView' => function ($model) use ($searchModel) {
            return Html::a("{$model->operator->name} - " . Yii::$app->formatter->asDate($model->getPeriodTimeStamp(), 'LLLL yyyy'),
                ['view', 'id' => (string)$model->getPrimaryKey(), 'type' => $searchModel->type, 'above' => $searchModel->above]);
        }
    ]) ?>

</div>