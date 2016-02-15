<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 15.04.15
 * Time: 17:08
 */

namespace app\components\widgets;


use app\assets\ContainerLoaderAsset;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class ContainerLoader extends Widget
{
    public $containerSelector;

    public $linkSelector = 'a';

    public function init()
    {
        echo Html::beginTag('div',['id'=>$this->id]);
        parent::init();
    }

    public function run()
    {
        parent::run();
        echo Html::endTag('div');

        $view = $this->getView();
        ContainerLoaderAsset::register($view);

        $settings = [
            'containerSelector' => $this->containerSelector,
            'linkSelector' => $this->linkSelector
        ];
        $view->registerJs("jQuery('div#{$this->id}').containerLoader(".Json::encode($settings).");");
    }

}