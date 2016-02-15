<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 26.01.15
 * Time: 11:31
 */
use app\tests\codeception\_pages\mobile\number\CreatePage;
use \app\modules\mobile\models\Number;
use \app\modules\mobile\models\Document;
use app\modules\directory\models\Employee;

$I = new FunctionalTester($scenario);
$I->wantTo("ensure that creation Number model works");

$owner = $I->grabRecord(Employee::className(),['id' => 1]);
$newDocument = 'file1.jpg';
$number = '9876543210';

$I->amGoingTo("prepare database.");
Document::deleteAll(['filename'=>$newDocument]);
Number::deleteAll(['number'=>$number]);
$page = CreatePage::openBy($I);
$I->dontSeeElement("input[type=file]");
$I->dontSeeElement("#documents-panel-body");
$I->dontSeeElement("#history-panel-body");

$page->submit([
    'number'=>$number,
    'ownerName' => $owner->fullName,
    'ownerPost' => $owner->post,
    'operatorId' => 'operator2',
    'destination' => Number::DESTINATION_MODEM,
    'limit' => "1000",
    'options' => [Number::OPTION_ACCOUNTING, Number::OPTION_DIRECTORY],
    'comment' => 'This is comment of testing number.'
]);
$I->SeeRecord(Number::className(), [
    'number'=>$number,
    'ownerId' => 1,
    'operatorId' => 'operator2',
    'destination' => Number::DESTINATION_MODEM,
    'limit' => 1000,
    'options' => [Number::OPTION_ACCOUNTING, Number::OPTION_DIRECTORY],
    'history' => [['ownerId' => 'employee1','rentDate' => time()]],
    'comment' => 'This is comment of testing number.'
]);
$I->see("Номер успешно создан",'.alert-success');
$I->seeInField($page->fieldSelector('ownerName'),$owner->fullName);
$I->seeInField($page->fieldSelector('ownerPost'),$owner->posts[1]);
$I->seeOptionIsSelected($page->fieldSelector('operatorId'), "Оператор 2");
$I->seeOptionIsSelected($page->fieldSelector('destination'),Number::DESTINATION_MODEM);
$I->seeCheckboxIsChecked($page->fieldSelector(Number::OPTION_ACCOUNTING));
$I->seeCheckboxIsChecked($page->fieldSelector(Number::OPTION_DIRECTORY));
$I->dontSeeCheckboxIsChecked($page->fieldSelector(Number::OPTION_TRIP));
$I->seeInField($page->fieldSelector('comment'),'This is comment of testing number.');
