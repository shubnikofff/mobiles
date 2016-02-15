<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 18.02.15
 * Time: 11:44
 */

namespace app\tests\codeception\_pages\mobile\number;

use yii\codeception\BasePage;

class UpdatePage extends BasePage{
    use PageTrait;
    public $route = 'mobile/number/update?id=number1';
}