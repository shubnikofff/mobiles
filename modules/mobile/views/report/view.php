<?php

use yii\helpers\Html;
use app\components\widgets\ContainerLoader;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\Report */

$this->title = "Отчёты";
$outsideDB = $model->getOutsideDb();
$outsideOperator = $model->getOutsideOperator();
?>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="panel-group" id="outSidePanel" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#outSidePanel" href="#outSideDbPanel" aria-expanded="true"
                       aria-controls="outSideDbPanel">
                        Номера не найденные в базе <span class="badge"><?= count($outsideDB) ?></span>
                    </a>
                </h4>
            </div>
            <div id="outSideDbPanel" class="panel-collapse collapse" role="tabpanel">
                <ul class="list-group">
                    <?php ContainerLoader::begin(); ?>
                    <?php foreach ($outsideDB as $item): ?>
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
                            class="badge"><?= count($outsideOperator) ?></span>
                    </a>
                </h4>
            </div>
            <div id="outSideReportPanel" class="panel-collapse collapse" role="tabpanel">
                <ul class="list-group">
                    <?php ContainerLoader::begin(); ?>
                    <?php foreach ($outsideOperator as $item): ?>
                        <li class="list-group-item"><?= Html::a($item['number'], ['number/update', 'id' => (string)$item['id']]) ?></li>
                    <?php endforeach; ?>
                    <?php ContainerLoader::end(); ?>
                </ul>
            </div>
        </div>
    </div>

<?= Html::a("Назад", 'javascript:history.go(-1)', ['class' => "btn btn-primary"]) ?>

    <div>

        <p class="h3"><?= $model->header() ?></p>

        <?= ExportMenu::widget([
            'dataProvider' => $model->itemsDataProvider(),
            'columns' => $model->itemsColumns(),
            'target' => ExportMenu::TARGET_SELF,
            'showConfirmAlert' => false,
            'formatter' => [
                'class' => \yii\i18n\Formatter::className(),
                'nullDisplay' => ''
            ],
            'filename' => $model->header(),
            'exportConfig' => [
                ExportMenu::FORMAT_HTML => false,
                ExportMenu::FORMAT_PDF => false,
            ]
        ])?>

        <?= GridView::widget([
            'dataProvider' => $model->itemsDataProvider(),
            'columns' => $model->itemsColumns(),
            'formatter' => [
                'class' => \yii\i18n\Formatter::className(),
                'nullDisplay' => ''
            ],
            'tableOptions' => [
                'class' => 'table table-striped table table-hover'
            ],
        ]) ?>

    </div>

<?= Html::a("Назад", 'javascript:history.go(-1)', ['class' => "btn btn-primary"]) ?>