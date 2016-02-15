<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.03.15
 * Time: 13:56
 */
use app\tests\codeception\_pages\mobile\number\IndexPage;
/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that updating Number model works');
$indexPage = IndexPage::openBy($I);
$waitTime = 3;

$I->amGoingTo('update mobile number 1111111111');
$I->fillField('#searchTextInput','1111111111');

$I->click(['link'=>'1111111111']);
$I->waitForElementVisible('#numberModal',15);
$I->waitForElement('#numberEditForm',15);
$I->wait($waitTime);
$I->see("1111111111",'p.modal-header-caption');
$I->fillField('#number-ownername'," ");
$I->fillField('#number-limit',"");
$I->attachFile('input[type="file"]', 'file1.jpg');
$I->wait($waitTime);
$I->click(['link' => 'Загрузить']);
$I->waitForJS("return $.active == 0;", 60);
$I->click("Прикрепленные документы");
$I->waitForElementVisible("#documents-panel-body",15);
$I->wait(1);
$I->see('file1.jpg', '#documents-panel-body');
$I->click('a.detach-document');
$I->waitForJS("return $.active == 0;", 60);
$I->see('Ничего не найдено.', '#documents-panel-body');
$I->wait(1);
$I->click('История');
$I->waitForElementVisible("#history-panel-body",15);
$I->see('Иванов Иван Иванович', '#history-panel-body');
$I->wait($waitTime);
$I->fillField('#number-comment','Changed comment');
$I->click("Обновить");
$I->waitForJS("return $.active == 0;", 60);
$I->see("Данные успешно обновлены",'.alert-success');
$I->dontSeeInField('#number-ownername',"Иванов Иван Иванович");
$I->dontSeeInField('#number-ownerpost',"Первая должность");
$I->click("Закрыть");

$I->amGoingTo('Check GridView data after update');
$I->waitForJS("return $.active == 0;", 60);
$I->waitForElementNotVisible("#numberModal",15);
$I->wait($waitTime);
$I->seeLink('1111111111');
$I->dontSee('Иванов Иван Иванович', '#numberGridView');
$I->see('-', '#numberGridView');
$I->see('Changed comment', '#numberGridView');
