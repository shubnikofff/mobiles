<?php
/**
 * mobiles
 * Created: 18.07.16 15:41
 * @copyright Copyright (c) 2016 OSKR NIAEP
 * 
 * @var $dataProvider \yii\data\ActiveDataProvider
 *@var $this \yii\web\View
 */
use kartik\export\ExportMenu;
use kartik\helpers\Html;
$this->title = "Экспорт данных"
?>
<div>

    <?= Html::pageHeader($this->title)?>

    <?= ExportMenu::widget([
        'dataProvider' => $dataProvider,
        'exportConfig' => [
            ExportMenu::FORMAT_PDF => false
        ],
        'columns' => [
            'number',
            [
                'label' => 'Сотрудник',
                'content' => function ($model) {
                    return ($model->owner) ? $model->owner->fullName : "";
                },
            ],
            [
                'attribute' => 'comment',
                'value' => function($model) {
                    return $model->comment ? $model->comment : "";
                }
            ]
        ]
    ])?>
    
</div>
