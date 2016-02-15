<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 18.02.15
 * Time: 11:00
 */

namespace app\tests\codeception\_pages\mobile\number;

use yii\codeception\BasePage;

class CreatePage extends BasePage{

    use PageTrait;
    public $route = 'mobile/number/create';
}