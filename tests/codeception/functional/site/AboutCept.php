<?php

use tests\codeception\_pages\site\AboutPage;

/* @var $scenario Codeception\Scenario */
$I = new FunctionalTester($scenario);
$I->wantTo('ensure that about works');
AboutPage::openBy($I);
$I->see('Помощь', 'h1');
