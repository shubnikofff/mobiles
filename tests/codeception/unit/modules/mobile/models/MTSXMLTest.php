<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 12.03.15
 * Time: 13:40
 */

namespace app\tests\codeception\unit\modules\mobile\models;

use Yii;
use Codeception\Specify;
use yii\codeception\TestCase;
use app\modules\mobile\models\MTSXML;

class MTSXMLTest extends TestCase{
    use Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testGetItems() {
        $this->specify("Items must be array with correct data", function() {
            $object = simplexml_load_file(Yii::getAlias('@data') . '/mts_report.xml', MTSXML::className());
            /** @var $object MTSXML */
            expect(is_array($object->getItems()))->true();
            expect(count($object->getItems()))->equals(5);
            expect($object->getItems()[1])->equals(['number' => '1111111111', 'expenditure' => 2415.12]);
        });
    }

    public function testValidate() {
        $this->specify("Document must have correct structure", function() {
            /** @var $object MTSXML */
            $object = simplexml_load_string('<a c="123"><b>1</b></a>', MTSXML::className());
            expect($object->validate())->false();
            $object = simplexml_load_file(Yii::getAlias('@data') . '/mts_report.xml', MTSXML::className());
            expect($object->validate())->true();
        });
    }

    public function testGetContract() {
        /**
         * @var $object MTSXML
         */
        $object = simplexml_load_file(Yii::getAlias('@data') . '/mts_report.xml', MTSXML::className());
        $this->tester->assertEquals($object->getContract(),'252301516437');
    }

    public function testGetMonth() {
        /**
         * @var $object MTSXML
         */
        $object = simplexml_load_file(Yii::getAlias('@data') . '/mts_report.xml', MTSXML::className());
        $this->tester->assertEquals($object->getMonth(),'01');
    }

    public function testGetYear() {
        /**
         * @var $object MTSXML
         */
        $object = simplexml_load_file(Yii::getAlias('@data') . '/mts_report.xml', MTSXML::className());
        $this->tester->assertEquals($object->getYear(),'2014');
    }
}