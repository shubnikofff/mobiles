<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 05.02.15
 * Time: 12:42
 */

namespace app\tests\codeception\unit\components\validators;

use app\components\validators\EmployeePostValidator;
use Codeception\Specify;
use yii\base\DynamicModel;
use yii\base\ErrorException;
use yii\codeception\TestCase;
use app\tests\codeception\unit\fixtures\EmployeeFixture;

class EmployeePostValidatorTest extends TestCase{
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

    private function prepareModel($ownerName, $ownerPost) {
        $this->model = new DynamicModel(compact('ownerPost'));
        $this->model->addRule('ownerPost', EmployeePostValidator::className(), ['ownerName' => $ownerName]);
    }

    public function testValidateAttribute(){

        $this->specify("Error if ownerName is not defined.",function($ownerName, $ownerPost){
            $this->prepareModel($ownerName, $ownerPost);
            $this->model->validate();
        },[
            'throws'=> new ErrorException(),
            'examples' => [
                ['','Сотрудник'],
                [null,'Сотрудник']
            ]
        ]);

        $this->specify("Validation shouldn't be success",function($ownerName, $ownerPost){
            $this->prepareModel($ownerName, $ownerPost);
            expect($this->model->validate())->false();
        },[
            'examples' => [
                ['Не существующий сотрудник','Сотрудник'],
                ['Иванов Иван Иванович','Не существующая должность'],
            ]
        ]);


        $this->specify("Validation should be success",function($ownerName, $ownerPost){
            $this->prepareModel($ownerName, $ownerPost);
            expect($this->model->validate())->true();
        },[
            'examples' => [
                ['Иванов Иван Иванович','Первый сотрудник'],
                ['Иванов Иван Иванович','Второй сотрудник'],
                ['Иванов Иван Иванович',''],
                ['Иванов Иван Иванович',null]
            ]
        ]);

        $this->specify("Validation with skipOnEmpty=>false", function($ownerName, $ownerPost){
            $this->model = new DynamicModel(compact('ownerPost'));
            $this->model->addRule('ownerPost', EmployeePostValidator::className(), ['ownerName' => $ownerName, 'skipOnEmpty' => false]);
            expect($this->model->validate())->true();
        },[
            'examples' => [
                ["Петров Петр Петрович", null],
                ["Петров Петр Петрович", ""],
            ]
        ]);
    }


}