<?php
/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \app\modules\mobile\models\TripSearch */
use kartik\helpers\Html;
use app\components\widgets\ContainerLoader;

$this->title = 'Командировочные номера';
?>

<?= Html::pageHeader($this->title) ?>

<?php ContainerLoader::begin() ?>

<?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>

<?= Html::a("Сброс", ['index'], ['class' => 'btn btn-default', 'data-container-loader' => '0']) ?>

<?php ContainerLoader::end() ?>

<div id="tripGridView">

    <?= $this->render('_gridview', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]); ?>

</div>