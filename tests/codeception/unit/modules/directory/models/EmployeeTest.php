<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 22.01.15
 * Time: 14:33
 */

namespace app\tests\codeception\unit\modules\directory\models;

use app\modules\directory\models\Employee;
use app\tests\codeception\unit\fixtures\EmployeeFixture;
use Codeception\Specify;
use yii\codeception\DbTestCase;

/**
 * Class EmployeeTest
 * @package app\tests\codeception\unit\modules\directory\models
 * @property array $employees
 * @method employees
 *
 */
class EmployeeTest extends DbTestCase
{
    use Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'employees' => [
                'class' => EmployeeFixture::className()
            ]
        ];
    }

    public function testGetFullName() {
        $this->specify("test getFullName of Employee model", function() {
            /** @var $employee Employee*/
            $employee = $this->employees('employee1');
            expect($employee->fullName)->equals("Иванов Иван Иванович");
        });
    }

    public function testFindByName() {
        $this->specify("Model must be found on accurate data (like is false). ", function($name) {
            expect(Employee::findByName($name)->count())->greaterThan(0);
        },[
            'examples' => [
                ["Иванов Иван Иванович"],
            ]
        ]);

        $this->specify("Model must be not found on accurate data (like is true). ", function($name) {
            expect(Employee::findByName($name, true)->count())->greaterThan(0);
        },[
            'examples' => [
                ["иванов иВан ИВАНОвич"],
                ["Ива"]
            ]
        ]);

        $this->specify("Model must be not found", function($name) {
            expect(Employee::findByName($name)->count())->equals(0);
        },[
            'examples' => [
                ["Не существующий сотрдуник"],
                ["Сотрудник"]
            ]
        ]);
    }

}