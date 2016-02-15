<?php
/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \app\modules\mobile\models\TripSearch */
use yii\helpers\Html;
use app\components\widgets\ContainerLoader;

$this->title = 'Командировочные номера';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php ContainerLoader::begin() ?>

<?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>

<?= Html::a("Сброс", ['index'], ['class' => 'btn btn-default', 'data-container-loader' => '0']) ?>

<?php ContainerLoader::end() ?>

<div id="tripGridView">

    <?= $this->render('_gridview', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]); ?>

</div>