<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 10.04.15
 * Time: 14:10
 */

namespace app\tests\codeception\unit\components\validators;


use app\components\validators\MobileNumberValidator;
use app\modules\mobile\models\Number;
use Codeception\Specify;
use yii\base\DynamicModel;
use yii\codeception\TestCase;
use app\tests\codeception\unit\fixtures\NumberFixture;

class MobileNumberValidatorTest extends TestCase
{
    use Specify;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var DynamicModel
     */
    private $model;

    public function fixtures()
    {
        return [
            'numbers' => [
                'class' => NumberFixture::className(),
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();
        $this->model = new DynamicModel(['number']);
        $this->model->addRule('number', MobileNumberValidator::className());;
    }


    public function testValidateAttribute()
    {

        $this->tester->haveInCollection(Number::collectionName(), ['number' => '9101234567']);

        $this->specify("Test validate", function ($number, $expected) {

            $this->model['number'] = $number;
            $this->model->validate(['number']);
            $this->tester->assertEquals($expected, $this->model->hasErrors());

        }, [
            'examples' => [
                ['9101234567',false],
                ['1111111111',true],
                ['9121111111',true]
            ]
        ]);
    }
}