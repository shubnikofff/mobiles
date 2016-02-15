<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 26.03.15
 * Time: 12:24
 */

namespace app\components\widgets;


use yii\base\Widget;
use yii\base\Exception;
use yii\widgets\ActiveField;
use yii\helpers\Html;

class ActiveRadioList extends Widget
{

    public $items = [];

    /** @var $activeField ActiveField  */
    public $activeField = null;

    public $options = null;


    public function init()
    {
        if (!$this->activeField instanceof ActiveField) {
            throw new Exception("'activeField' must be set and instance of yii\\widgets\\ActiveField");
        }

        $defaultOptions = [
            'class' => 'btn-group',
            'data-toggle' => 'buttons',
            'item' => function ($index, $label, $name, $checked, $value) {
                return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' . Html::radio($name, $checked, ['value' => $value, 'class' => '']) . $label . '</label>';
            }
        ];

        if($this->options === null) {
            $this->options = $defaultOptions;
        } elseif (is_array($this->options)) {
            $this->options = array_merge($defaultOptions, $this->options);
        } else {
            throw new Exception("'options' must be array");
        }

        parent::init();
    }

    public function run()
    {
        return $this->activeField->inline()->radioList($this->items, $this->options);
    }


}