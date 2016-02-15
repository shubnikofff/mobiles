<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 18.02.15
 * Time: 14:47
 */
use app\tests\codeception\_pages\mobile\number\CreatePage;
use \app\modules\mobile\models\Number;

$I = new FunctionalTester($scenario);
$I->wantTo('test validation of Number model');
CreatePage::openBy($I);
$I->seeInTitle('Новый номер');

$I->amGoingTo("send empty form");
CreatePage::openBy($I)->submit();
$I->see('Необходимо заполнить «Номер».');

$I->amGoingTo("introduce too short number");
CreatePage::openBy($I)->submit(['number'=>'1234']);
$I->see('Значение «Номер» должно содержать 10 символов.');

$I->amGoingTo("introduce number and limit of letters and numbers");
CreatePage::openBy($I)->submit(['number' => '123abc', 'limit' => '123abc']);
$I->see('Значение «Номер» должно быть целым числом.');
$I->see('Значение «Лимит» должно быть целым числом.');

$I->amGoingTo("introduce negative value of number and limit");
CreatePage::openBy($I)->submit(['number' => '-123', 'limit' => '-456']);
$I->see('Значение «Номер» должно быть не меньше 0.');
$I->see('Значение «Лимит» должно быть не меньше 0.');

$I->amGoingTo("introduce non unique value of number");
$I->haveRecord(Number::className(),['number'=>'9101234567']);
CreatePage::openBy($I)->submit(['number'=>'9101234567']);
$I->see('Номер «9101234567» уже занят.');

$I->amGoingTo("check accounting option and empty limit");
CreatePage::openBy($I)->submit(['number'=>'0123456789', 'options'=>[Number::OPTION_ACCOUNTING]]);
$I->see("При выбранной опции «Учитывать перерасход» лимит должен быть указан");

$I->amGoingTo("introduce non existent owner name");
CreatePage::openBy($I)->submit(['number'=>'0123456789', 'ownerName' => 'Non Existent Employee']);
$I->see("Сотрудник «Non Existent Employee» не найден.");

$I->amGoingTo("introduce non existent owner post");
CreatePage::openBy($I)->submit(['number'=>'0123456789', 'ownerName' => 'Иванов Иван Иванович', 'ownerPost' => 'Non Existent Post']);
$I->see("Сотрудник «Иванов Иван Иванович» c должностью «Non Existent Post» не найден.");
