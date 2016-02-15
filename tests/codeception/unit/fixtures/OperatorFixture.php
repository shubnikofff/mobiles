<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 19.01.15
 * Time: 10:50
 */

namespace app\tests\codeception\unit\fixtures;

use yii\mongodb\ActiveFixture;

/**
 * Class OperatorFixture
 * @package app\tests\codeception\unit\fixtures
 */
class OperatorFixture extends ActiveFixture{

    public $modelClass = 'app\modules\mobile\models\Operator';
    public $dataFile = '@tests/codeception/unit/fixtures/data/models/mobile/operator.php';

}