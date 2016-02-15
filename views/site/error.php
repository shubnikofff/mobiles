<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Если данная проблема повторяется, пожалуйста позвоните по номеру 00-00 или напишите на адрес <?= Html::a("oskr@niaep.ru","mailto:oskr@niaep.ru")?>
    </p>

</div>
