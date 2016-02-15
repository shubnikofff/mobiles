<?php

use tests\codeception\_pages\site\ContactPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that contact works');

$contactPage = ContactPage::openBy($I);

$I->see('Contact', 'h1');

$I->amGoingTo('submit contact form with no data');
$contactPage->submit([]);
$I->expectTo('see validations errors');
$I->see('Contact', 'h1');
$I->see('Необходимо заполнить «Name».');
$I->see('Необходимо заполнить «Email»');
$I->see('Необходимо заполнить «Subject»');
$I->see('Необходимо заполнить «Body»');
$I->see('Неправильный проверочный код.');

$I->amGoingTo('submit contact form with not correct email');
$contactPage->submit([
    'name' => 'tester',
    'email' => 'tester.email',
    'subject' => 'test subject',
    'body' => 'test content',
    'verifyCode' => 'testme',
]);
$I->expectTo('see that email adress is wrong');
$I->dontSee('Необходимо заполнить «Name»', '.help-inline');
$I->see('Значение «Email» не является правильным email адресом');
$I->dontSee('Необходимо заполнить «Subject»', '.help-inline');
$I->dontSee('Необходимо заполнить «Body»', '.help-inline');
$I->dontSee('Неправильный проверочный код.', '.help-inline');

$I->amGoingTo('submit contact form with correct data');
$contactPage->submit([
    'name' => 'tester',
    'email' => 'tester@example.com',
    'subject' => 'test subject',
    'body' => 'test content',
    'verifyCode' => 'testme',
]);
$I->dontSeeElement('#contact-form');
$I->see('Thank you for contacting us. We will respond to you as soon as possible.');
