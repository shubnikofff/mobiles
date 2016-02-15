<?php
use yii\bootstrap\Nav;

/**
 * @var $content mixed page content
 */

?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<?php
echo Nav::widget([
    'options' => [
        'class' => 'nav-tabs'
    ],
    'items' => [
        [
            'label' => 'Служебные',
            'url' => ['number/index'],
            'active' => ($this->context->id === 'number')
        ],
        [
            'label' => 'Командировочные',
            'url' => ['trip/index'],
            'active' => ($this->context->id === 'trip')
        ],
        [
            'label' => 'Отчёты',
            'url' => ['report/index'],
            'active' => ($this->context->id === 'report')
        ],
    ]
]);
?>

<?= $content ?>

<?php $this->endContent(); ?>
