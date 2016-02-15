<?php
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */

echo Tabs::widget([
    'items' => [
        [
            'label' => 'One',
            'content' => $this->context->renderPartial('about'),
            'active' => true
        ],
        [
            'label' => 'Two',
            'content' => $this->context->renderPartial('index'),
            'headerOptions' => [],
            'options' => ['id' => 'myveryownID'],
        ],
        [
            'label' => 'Dropdown',
            'items' => [
                [
                    'label' => 'DropdownA',
                    'content' => 'DropdownA, Anim pariatur cliche...',
                ],
                [
                    'label' => 'DropdownB',
                    'content' => 'DropdownB, Anim pariatur cliche...',
                ],
            ],
        ],
    ],
]);