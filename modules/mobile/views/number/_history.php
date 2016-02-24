<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 17.02.15
 * Time: 15:39
 */
use app\modules\directory\models\Employee;

/* @var $this yii\web\View */
/* @var $history array */
/** @var $owner Employee */
?>

<div class="panel panel-default">
    <div class="panel-heading" role="tab">
        <div class="panel-title">
            <a class="h5" data-toggle="collapse" href="#history-panel-body">История</a>
        </div>
    </div>
    <div id="history-panel-body" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body">
        <table id="history-table" class="table table-condensed">
            <tr>
                <th>Сотрудник</th>
                <th>Дата выдачи</th>
                <th>Дата возврата</th>
            </tr>

            <?php foreach ($history as $item): ?>
                <tr>
                    <td><?= $item['ownerName']?><p class="small"> <em><?= $item['ownerPost']?><em></p></td>
                    <td><?= Yii::$app->formatter->asDate($item['rentDate']->toDateTime()) ?></td>
                    <td><?= array_key_exists('returnDate', $item) ? Yii::$app->formatter->asDate($item['returnDate']->toDateTime()) : "-" ?></td>
                </tr>
            <?php endforeach ?>
        </table>
            </div>
    </div>
</div>