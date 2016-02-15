<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 05.02.15
 * Time: 10:36
 */

namespace app\tests\codeception\unit\components\validators;

use app\components\validators\EmployeeNameValidator;
use app\tests\codeception\unit\fixtures\EmployeeFixture;
use yii\base\DynamicModel;
use Codeception\Specify;
use yii\codeception\TestCase;

class EmployeeNameValidatorTest extends TestCase{

    use Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    private $model;

    public function fixtures()
    {
        return [
            'employees' => [
                'class' => EmployeeFixture::className(),
                //'dataFile' => '@tests/codeception/unit/fixtures/data/components/validators/employee.validator.php'
            ]
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->model = new DynamicModel(['ownerName']);
        $this->model->addRule('ownerName', EmployeeNameValidator::className());
    }

    public function testValidateAttribute(){
        $this->specify("Validation should be success", function($ownerName){
            $this->model->ownerName = $ownerName;
            expect($this->model->validate())->true();
        },[
            'examples' => [
                [''],
                ['Иванов Иван Иванович'],
            ]
        ]);

        $this->specify("Validation shouldn't be success", function($ownerName){
            $this->model->ownerName = $ownerName;
            expect($this->model->validate())->false();
        },[
            'examples' => [
                ['Не существующий сотрудник'],
                ['НеСуществующийСотрудник']
            ]
        ]);
    }

}