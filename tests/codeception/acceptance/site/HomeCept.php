<?php

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('Телепорт');
$I->seeLink('Телефонный справочник');
$I->click('Телефонный справочник');
$I->see('Congratulations!');
