<?php

namespace tests\codeception\_pages\site;

use yii\codeception\BasePage;

/**
 * Represents about page
 * @property \AcceptanceTester|\FunctionalTester $actor
 *
 */
class AboutPage extends BasePage
{
    public $route = 'site/about';
}
