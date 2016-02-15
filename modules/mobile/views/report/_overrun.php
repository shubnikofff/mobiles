<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 17.03.15
 * Time: 14:09
 */

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\Report */

?>

<div class="report-overrun">

    <p class="h3"><?= "{$model->operator->name}: перерасход за " . Yii::$app->formatter->asDate($model->getPeriodTimeStamp(), 'LLLL Y'); ?></p>

    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Имя сотрудника</th>
            <th>Номер</th>
            <th>Лимит (руб.)</th>
            <th>Расход (руб.)</th>
            <th>Перерасход (руб.)</th>
            <th>Комментарий</th>
        </tr>
        <?php $counter = 1;?>
        <?php foreach($model->overrunItems as $item):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $item['number']?></td>
                <td><?= $item['employee']?></td>
                <td><?= $item['limit']?></td>
                <td><?= str_replace('.',',',$item['expenditure'])?></td>
                <td><?= str_replace('.',',',$item['overrun'])?></td>
                <td><?= $item['comment']?></td>
            </tr>
        <?php endforeach ?>
        <tr>
            <th colspan="6">Итого:</th>
            <td><?= str_replace('.',',',$model['totalOverrun'])?></td>
        </tr>
    </table>

</div>

