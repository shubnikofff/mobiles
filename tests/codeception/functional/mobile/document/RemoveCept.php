<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 16.02.15
 * Time: 16:06
 */
use \app\modules\mobile\models\Document;

Document::deleteAll();
$I = new FunctionalTester($scenario);
$I->wantTo('ensure that remove document works');
$id = Document::getCollection()->insertFile(\Yii::getAlias('@data') . '/file1.jpg');
$I->seeRecord(Document::className(),['_id' => $id]);
$I->amOnPage(['mobile/number/detach-document','id'=>(string)$id]);
$I->dontSeeRecord(Document::className(), ['_id' => $id]);