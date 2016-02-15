<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 05.03.15
 * Time: 14:32
 */

namespace app\tests\codeception\_pages\mobile\number;

use yii\codeception\BasePage;

class IndexPage extends BasePage{
    public $route = 'mobile/number/index';

    public function expandSearchParameters() {
        $this->actor->click('Дополнительные параметры поиска');
        $this->actor->waitForElementVisible('#advancedSearchPanelBody', 15);
    }
}