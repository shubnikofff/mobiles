<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 16.04.15
 * Time: 18:20
 */

namespace app\components\widgets;

use app\assets\ModalViewAsset;
use yii\base\Widget;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Class ModalView
 * @package app\components\widgets
 *
 * ~~~php
 * ModalView::begin([
 *      'modalOptions' => [
 *          'header' => 'MyModal'
 *      ],
 *      buttons = [
 *          [
 *              'content' => "Save",
 *              'options' => ['class' => 'btn btn-primary'],
 *              'submit'
 *          ],
 *          [
 *              'content' => "Delete",
 *              'options' => ['class' => 'btn btn-danger'],
 *              'action' => \yii\helpers\Url::to(['delete']),
 *          ]
 *      ]
 * ]);
 *
 * echo 'Hello from modal!';
 *
 * ModalView::end()
 * ~~~
 */
class ModalView extends Widget
{

    public $modalOptions;
    /**
     * Define the button's settings
     * @var $buttons array
     */
    public $buttons;

    public $containerSelector;

    public function init()
    {
        echo Html::beginTag('div', ['id' => $this->id]);

        $footer = "";
        if ($this->buttons !== null) {
            foreach ($this->buttons as $button) {
                $options = array_key_exists('options', $button) ? $button['options'] : [];
                if (in_array('submit', $button)) {
                    $options['data-submit'] = 1;
                }
                if (array_key_exists('action', $button)) {
                    $options['data-action'] = $button['action'];
                }
                $footer .= Html::button($button['content'], $options);
            }
        }
        $footer .= Html::button('Закрыть', ['class' => 'btn btn-default']);

        $this->modalOptions['footer'] = $footer;
        Modal::begin($this->modalOptions);

        parent::init();
    }

    public function run()
    {
        parent::run();
        Modal::end();
        echo Html::endTag('div');

        $view = $this->getView();
        ModalViewAsset::register($view);

        $settings = [
            'containerSelector' => $this->containerSelector
        ];

        $context = $this->containerSelector === null ? 'teleport.$commonContainer' : "$('{$this->containerSelector}')";
        $view->registerJs("jQuery('#{$this->id}',{$context}).modalView(" . Json::encode($settings) . ");");
    }

}