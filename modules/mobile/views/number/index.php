<?php

use kartik\helpers\Html;
use app\modules\mobile\assets\NumberAsset;
use yii\helpers\Json;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\mobile\models\NumberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $exportDataProvider \yii\data\ActiveDataProvider */
/* @var $form yii\bootstrap\ActiveForm */
?>

<?php $this->title = 'База номеров'; ?>

    <div id="mobileNumberIndexView">

        <?= Html::pageHeader($this->title) ?>

        <?= $this->render('_search', ['model' => $searchModel]); ?>

        <?= ExportMenu::widget([
            'dataProvider' => $exportDataProvider,
            'exportConfig' => [
                ExportMenu::FORMAT_PDF => false
            ],
            'target' => ExportMenu::TARGET_SELF,
            'showConfirmAlert' => false,
            'formatter' => [
                'class' => \yii\i18n\Formatter::className(),
                'nullDisplay' => ''
            ],
            'columns' => [
                'number',
                [
                    'label' => 'Сотрудник',
                    'content' => function ($model) {
                        return ($model->owner) ? $model->owner->fullName : "";
                    },
                ],
                'comment'
            ],
            'filename' => 'База сотовых номеров'
        ])?>

        <?php Pjax::begin(['formSelector' => 'form#numberSearchForm', 'id' => 'pjaxContainerForGridView']);?>

        <?= $this->render('_gridview', ['dataProvider' => $dataProvider]); ?>

        <?php Pjax::end() ?>

    </div>

<?php NumberAsset::register($this); ?>

<?php $options = Json::encode([
    'searchForm' => '#numberSearchForm',
    'pjaxContainer' => '#pjaxContainerForGridView',
    'searchTextInput' => '#searchTextInput',
    'createNumberControl' => '#numberCreateLink',
    'updateNumberControl' => 'a.number-update'
]); ?>

<?php $this->registerJs("$('div#mobileNumberIndexView').mobileNumberIndexView({$options})"); ?>
