<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 05.03.15
 * Time: 16:46
 */
use app\tests\codeception\_pages\mobile\number\IndexPage;
/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that creating Number model works');
$indexPage = IndexPage::openBy($I);
$waitTime = 3;

$I->amGoingTo('ensure that number pass to form by clicking on Add button');
$I->fillField('#searchTextInput','9101111111');
$I->wait($waitTime);
$I->click('#numberCreateLink');
$I->waitForElementVisible('#numberModal',15);
$I->see("Новый номер",'p.modal-header-caption');
$I->waitForElement('#numberEditForm',15);
$I->see('Новый номер');
$I->seeElement('#numberField');
$I->seeInField('#numberField', '9101111111');
$I->wait($waitTime);
$I->click('Закрыть');

$I->amGoingTo('ensure that number pass to form by pressing Enter key on keyboard');
$I->fillField('#searchTextInput','9101234567');
$I->dontSeeLink('9101234567');
$I->wait($waitTime);
$I->pressKey('#searchTextInput',WebDriverKeys::ENTER);
$I->waitForElementVisible('#numberModal',15);
$I->waitForElement('#numberEditForm',15);
$I->seeInField('#numberField', '9101234567');

$I->amGoingTo("fill form");
$I->fillField('#number-ownername','Иванов Иван Иванович');
$I->fillField('#number-ownerpost','Первая должность');
$I->selectOption('#number-operatorid', 'operator2');
$I->selectOption('input[name="Number[destination]"]','Модем');
$I->fillField('#number-limit','1000');
$I->checkOption("Командировачная");
$I->fillField('#number-comment','Комментарий для номера');

$I->amGoingTo("create new number");
$I->click("Создать");
$I->waitForJS("return $.active == 0;", 60);
$I->see("Номер успешно создан");
$I->click("Закрыть");
$I->waitForElementNotVisible("#numberModal",15);
$I->waitForJS("return $.active == 0;", 60);
$I->wait($waitTime);
$I->seeLink('9101234567');
$I->see('Иванов Иван Иванович', '#numberGridView');
$I->see('1000', '#numberGridView');
$I->see('Комментарий для номера', '#numberGridView');
