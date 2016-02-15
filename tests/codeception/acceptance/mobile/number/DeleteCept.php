<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.03.15
 * Time: 15:08
 */
use app\tests\codeception\_pages\mobile\number\IndexPage;
use app\modules\mobile\models\Number;
/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that deleting Number model works');
(new Number(['number'=>'9999999999']))->save(false);
$indexPage = IndexPage::openBy($I);
$waitTime = 2;

$I->wait($waitTime);
$I->amGoingTo("delete mobile number from modal window");
$I->click(['link'=>'9999999999']);
$I->waitForElementVisible('#numberModal',15);
$I->waitForElement('#numberEditForm',15);
$I->wait($waitTime);
$I->click(['link'=>'Удалить']);
$I->waitForElement("#numberGridView");
$I->dontSeeLink('9999999999', '#numberGridView');