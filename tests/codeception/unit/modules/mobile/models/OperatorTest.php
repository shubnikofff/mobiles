<?php
namespace modules\mobile\models;


use app\modules\mobile\models\Operator;
use app\tests\codeception\unit\fixtures\OperatorFixture;
use yii\codeception\DbTestCase;
use Codeception\Specify;

class OperatorTest extends DbTestCase
{
    use Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'numbers' => [
                'class' => OperatorFixture::className(),
            ],
        ];
    }

    public function testItems()
    {
        $this->specify("Попытка получить список операторов", function() {
            $items = [
                'operator1' => 'Оператор 1',
                'operator2' => 'Оператор 2',
            ];
            expect("Список должен состоять из пар идентификатор - имя",Operator::items())->equals($items);
        });

    }

}