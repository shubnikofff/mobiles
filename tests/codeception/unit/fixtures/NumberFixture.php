<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 23.12.14
 * Time: 15:00
 */

namespace app\tests\codeception\unit\fixtures;

use yii\mongodb\ActiveFixture;

/**
 * Class NumberFixture
 * @package app\tests\codeception\unit\fixtures
 */
class NumberFixture extends ActiveFixture {

    public $modelClass = 'app\modules\mobile\models\Number';
    public $dataFile = '@tests/codeception/unit/fixtures/data/models/mobile/number.php';
    public $depends = [
        'app\tests\codeception\unit\fixtures\EmployeeFixture',
        'app\tests\codeception\unit\fixtures\OperatorFixture',
        //'app\tests\codeception\unit\fixtures\DocumentFixture',
    ];

}