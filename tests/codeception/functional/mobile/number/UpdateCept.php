<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 18.02.15
 * Time: 11:52
 */

use app\tests\codeception\_pages\mobile\number\UpdatePage;
use app\modules\mobile\models\Number;
use app\modules\directory\models\Employee;

/**
 * @var $model app\modules\mobile\models\Number
 */
$I = new FunctionalTester($scenario);
$I->wantTo('ensure that update Number model works');

$page = UpdatePage::openBy($I);

$model = $I->grabRecord(Number::className(),['_id'=>'number1']);

$I->seeInTitle('Номер '.$model['number']);
$I->dontSeeElement($page->fieldSelector('number'));
$I->SeeElement("#documents-panel-body");
$I->SeeElement("#history-panel-body");

$I->seeInField($page->fieldSelector('ownerName'),$model->owner['fullName']);
$I->seeInField($page->fieldSelector('ownerPost'),$model->owner['post']);
$I->seeInField($page->fieldSelector('operatorId'),$model['operatorId']);
$I->seeInField($page->fieldSelector('destination'),$model['destination']);
$I->seeInField($page->fieldSelector('limit'),$model['limit']);
foreach(array_keys(Number::optionItems()) as $option) {
    in_array($option,$model->options) ?
        $I->seeCheckboxIsChecked($page->fieldSelector($option)) :
        $I->dontSeeCheckboxIsChecked($page->fieldSelector($option));
}
$I->seeInField($page->fieldSelector('comment'),$model['comment']);

$owner = $I->grabRecord(Employee::className(), ['_id' => 'employee2']);

$page->submit([
    'number' => '1234567890',
    'ownerName' => $owner['fullName'],
    'ownerPost' => $owner['post'],
    'operatorId' => 'operator2',
    'destination' => Number::DESTINATION_MODEM,
    'limit' => '2000',
    'options' => [
        Number::OPTION_TRIP
    ],
    'comment' => 'This is update comment'
]);

$I->see("Данные успешно обновлены",'.alert-success');
$history = $model['history'];
$history[count($history) - 1]['returnDate'] = time();
$history[] = ['ownerId' => 'employee2','rentDate' => time()];
$I->seeRecord(Number::className(),[
    '_id' => 'number1',
    'number' => $model['number'],
    'ownerId' => 'employee2',
    'operatorId' => 'operator2',
    'destination' => Number::DESTINATION_MODEM,
    'limit' => 2000,
    'options' => [
        Number::OPTION_TRIP
    ],
    'history' => $history,
    'comment' => 'This is update comment'
]);