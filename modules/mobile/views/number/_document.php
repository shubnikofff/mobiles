<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.02.15
 * Time: 16:15
 */
use yii\helpers\Html;
use app\modules\mobile\models\Document;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $ownerId  MongoId|string */
?>

<?php $dataProvider = new ActiveDataProvider([
    'query' => Document::find()->select(['filename'])->where(['ownerId' => $ownerId]),
    'pagination' => false,
    'sort' => false,
]); ?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['class' => 'list-group'],
    'summary' => "",
    'itemOptions' => ['class' => 'list-group-item'],
    'itemView' => function ($model) {
        /** @var $model Document */
        return Html::a($model->filename, ['render-document', 'id' => (string)$model->getPrimaryKey()], ['target' => '_blank',]) .
        Html::a("<span class='glyphicon glyphicon-trash'></span>", ['detach-document', 'id' => (string)$model->getPrimaryKey()], ['style' => 'float: right', 'class'=>'detach-document']);
    }
]); ?>

<?php
$script = <<<JS
$('a.detach-document').on('click', function(e) {
    e.preventDefault();
    $('#documents-panel-body').load($(this).attr('href'));
});
JS;
$this->registerJs($script);

?>