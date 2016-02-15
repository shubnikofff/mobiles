<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 17.03.15
 * Time: 17:18
 */

/* @var $this yii\web\View */
/* @var $model app\modules\mobile\models\Report */
/* @var $above integer */
?>

<div class="report-overrun">

    <p class="h3"><?= "{$model->operator->name}: расход свыше {$above} руб. за " . Yii::$app->formatter->asDate($model->getPeriodTimeStamp(), 'LLLL Y'); ?></p>

    <table class="table table-hover">
        <tr>
            <th>#</th>
            <th>Имя сотрудника</th>
            <th>Номер</th>
            <th>Лимит (руб.)</th>
            <th>Расход (руб.)</th>
            <th>Комментарий</th>
        </tr>
        <?php $counter = 1;?>

        <?php foreach($model->getExpenditureItems((int)$above)->all() as $item):?>
            <tr>
                <td><?= $counter++?></td>
                <td><?= $item['employee']?></td>
                <td><?= $item['number']?></td>
                <td><?= $item['limit']?></td>
                <td><?= $item['expenditure']?></td>
                <td><?= $item['comment']?></td>
            </tr>
        <?php endforeach ?>
    </table>

</div>