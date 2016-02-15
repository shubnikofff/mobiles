<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 18.02.15
 * Time: 11:14
 */

namespace app\tests\codeception\_pages\mobile\number;

use app\modules\mobile\models\Number;
use yii\helpers\Html;

/**
 * Class PageTrait
 * @package app\tests\codeception\_pages\mobile\number
 * @property \FunctionalTester|\AcceptanceTester $actor
 */
trait PageTrait
{
    /**
     * @param array $data
     */

    public function fillForm(array $data = [])
    {
        foreach ($data as $field => $value) {
            switch ($field) {
                case 'number':
                    if ($this instanceof UpdatePage) continue 2;
                case 'ownerName':
                case 'ownerPost':
                case 'limit' :
                case 'comment':
                    $this->actor->fillField($this->fieldSelector($field), $value);
                    break;
                case 'operatorId' :
                case 'destination' :
                    $this->actor->selectOption($this->fieldSelector($field), $value);
                    break;
                case 'options' :
                    foreach ($data['options'] as $option) {
                        $this->actor->checkOption($this->fieldSelector($option));
                    }
                    break;
            }
        }
    }

    public function submit(array $data = [])
    {
        $this->fillForm($data);
        $this->actor->click('button[type="submit"]');
    }

    public function fieldSelector($attribute)
    {
        switch ($attribute) {
            case 'number':
            case 'ownerName':
            case 'ownerPost':
            case 'limit' :
                return 'input[name="Number[' . $attribute . ']"]';
            case 'operatorId' :
                return 'select[name="Number[' . $attribute . ']"]';
            case 'destination' :
                return 'input[name="Number[' . $attribute . ']"]';
            case Number::OPTION_ACCOUNTING:
            case Number::OPTION_DIRECTORY:
            case Number::OPTION_TRIP:
                return 'input[value="' . $attribute . '"]';
            case 'comment' :
                return 'textarea[name="Number[' . $attribute . ']"]';
        }
    }
}