<?php

use yii\helpers\Html;
use app\components\widgets\ContainerLoader;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\Report */
/* @var $reportView string */
/* @var $above integer */
$this->title = "Отчёты";
?>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel-group" id="outSidePanel" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#outSidePanel" href="#outSideDbPanel" aria-expanded="true"
                       aria-controls="outSideDbPanel">
                        Номера не найденные в базе <span class="badge"><?= count($model->getOutsideDb()) ?></span>
                    </a>
                </h4>
            </div>
            <div id="outSideDbPanel" class="panel-collapse collapse" role="tabpanel">
                <ul class="list-group">
                    <?php ContainerLoader::begin(); ?>
                    <?php foreach ($model->getOutsideDb() as $item): ?>
                        <li class="list-group-item"><?= Html::a($item['number'], ['number/create', 'number' => $item['number']]) . " - " . $item['expenditure'] . " руб." ?></li>
                    <?php endforeach; ?>
                    <?php ContainerLoader::end(); ?>
                </ul>

            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#outSidePanel" href="#outSideReportPanel"
                       aria-expanded="false" aria-controls="outSideReportPanel">
                        Номера не найденные в отчете оператора <span
                            class="badge"><?= count($model->getOutsideOperator()) ?></span>
                    </a>
                </h4>
            </div>
            <div id="outSideReportPanel" class="panel-collapse collapse" role="tabpanel">
                <ul class="list-group">
                    <?php ContainerLoader::begin(); ?>
                    <?php foreach ($model->getOutsideOperator() as $item): ?>
                        <li class="list-group-item"><?= Html::a($item['number'], ['number/update', 'id' => (string)$item['id']]) ?></li>
                    <?php endforeach; ?>
                    <?php ContainerLoader::end(); ?>
                </ul>
            </div>
        </div>
    </div>

<?= Html::a("Назад", 'javascript:history.go(-1)', ['class' => "btn btn-primary"]) ?>

<?= $this->render($reportView, ['model' => $model, 'above' => $above]) ?>

<?= Html::a("Назад", 'javascript:history.go(-1)', ['class' => "btn btn-primary"]) ?>