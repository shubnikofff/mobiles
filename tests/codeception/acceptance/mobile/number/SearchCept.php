<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 05.03.15
 * Time: 14:25
 */
use app\tests\codeception\_pages\mobile\number\IndexPage;
/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$waitTime = 3;
$I->wantTo('ensure that searching Number models works');
$indexPage = IndexPage::openBy($I);

$I->amGoingTo('input mobile number');
$I->fillField('#searchTextInput','111');
$I->wait($waitTime);
$I->seeLink('1111111111');
$I->dontSeeLink('2222222222');
$I->dontSeeLink('3333333333');
$I->click('Сброс');

$I->amGoingTo('input employee name');
$I->fillField('#searchTextInput','пет');
$I->wait($waitTime);
$I->seeLink('3333333333');
$I->dontSeeLink('1111111111');
$I->dontSeeLink('2222222222');
$I->click('Сброс');

$I->amGoingTo('input no existing data');
$I->fillField('#searchTextInput','Я устал писать тесты !!!');
$I->wait($waitTime);
$I->see("Ничего не найдено.");
$I->click('Сброс');

$I->amGoingTo('choose mobile operator');
$indexPage->expandSearchParameters();
$I->selectOption('select','Оператор 1');
$I->wait($waitTime);
$I->seeLink('1111111111');
$I->seeLink('3333333333');
$I->dontSeeLink('2222222222');
$I->click('Сброс');

$I->amGoingTo('input comment for number');
$indexPage->expandSearchParameters();
$I->fillField('#numberCommentInput','1111111111');
$I->wait($waitTime);
$I->seeLink('1111111111');
$I->dontSeeLink('2222222222');
$I->dontSeeLink('3333333333');
$I->click('Сброс');

$I->amGoingTo('input combine search filters');
$indexPage->expandSearchParameters();
$I->fillField('#searchTextInput','Иванов');
$I->selectOption('select','Оператор 2');
$I->wait($waitTime);
$I->seeLink('2222222222');
$I->dontSeeLink('1111111111');
$I->dontSeeLink('3333333333');

$I->fillField('#numberCommentInput','111');
$I->wait($waitTime);
$I->see("Ничего не найдено.");

$I->selectOption('select','Оператор 1');
$I->wait($waitTime);
$I->seeLink('1111111111');
$I->dontSeeLink('2222222222');
$I->dontSeeLink('3333333333');