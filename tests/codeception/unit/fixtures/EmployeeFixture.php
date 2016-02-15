<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 16.01.15
 * Time: 15:05
 */

namespace app\tests\codeception\unit\fixtures;

use yii\test\ActiveFixture;

/**
 * Class EmployeeFixture
 * @package app\tests\codeception\unit\fixtures
 */
class EmployeeFixture extends ActiveFixture {

    public $modelClass = 'app\modules\directory\models\Employee';
    public $dataFile = '@tests/codeception/unit/fixtures/data/models/directory/employee.php';

}