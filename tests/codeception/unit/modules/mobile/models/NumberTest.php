<?php
namespace app\tests\codeception\unit\modules\mobile\models;

use app\tests\codeception\unit\fixtures\EmployeeFixture;
use app\tests\codeception\unit\fixtures\OperatorFixture;
use Yii;
use app\modules\mobile\models\Document;
use app\modules\mobile\models\Operator;
use app\tests\codeception\unit\fixtures\NumberFixture;
use app\modules\directory\models\Employee;
use app\modules\mobile\models\Number;
use yii\codeception\DbTestCase;
use Codeception\Specify;
use yii\web\UploadedFile;
use MongoDate;


/**
 * Class NumberTest
 * @package modules\mobile\models
 * @property array $numbers
 * @method numbers
 * @method employees
 */
class NumberTest extends DbTestCase
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
                'class' => NumberFixture::className(),
            ],
            'employees' => [
                'class' => EmployeeFixture::className()
            ],
            'operators' => [
                'class' => OperatorFixture::className()
            ]
        ];
    }

    public function testOptionsValidator()
    {
        $model = new Number(['scenario' => 'create']);
        $this->specify("Validation should be success", function () use ($model) {
            $model->options = [$model::OPTION_ACCOUNTING, $model::OPTION_TRIP];
            expect($model->validate(['options']))->true();
        });
        $this->specify("Validation should not be success", function () use ($model) {
            $model->options = [$model::OPTION_ACCOUNTING, 'other option', $model::OPTION_TRIP];
            expect($model->validate(['options']))->false();
        });
    }

    public function testGetOwner()
    {
        $this->specify("Владелец номера должен быть доступен.", function () {
            $mobile = $this->numbers('number1');
            expect("Владелец должен быть определен", is_null($mobile->owner))->false();
            expect("Owner must be instance of app\\modules\\directory\\models\\Employee", $mobile->owner instanceof Employee)->true();
        });
    }

    public function testGetOperator()
    {
        $this->specify("Operator must be instance of app\\modules\\mobile\\models\\Operator", function () {
            $model = Number::findOne(['_id' => 'number1']);
            expect($model->operator instanceof Operator)->true();
        });
    }

    public function testGetOptions()
    {
        $this->specify("Опции должны быть доступны.", function () {
            $mobile = $this->numbers('number1');
            expect("Опции должны загружаться ввиде массива", is_array($mobile->options))->true();
        });
    }

    public function  testGetIsTrip()
    {
        $this->specify("Проверка признака \"Командировочный\"", function () {
            $mobile = $this->numbers('number1');
            expect("Номер должен быть с опцией \"Командировочный\"", $mobile->isTrip)->true();
        });
    }

    public function testGetShowInDirectory()
    {
        $this->specify("Проверка признака \"Показывать в справочнике\"", function () {
            $mobile = $this->numbers('number1');
            expect("Номер должен быть с опцией \"Отображать в справочнике\"", $mobile->showInDirectory)->true();
        });
    }

    public function testGetAccounting()
    {
        $this->specify("Проверка признака \"Учитывать перерасход\"", function () {
            $mobile = $this->numbers('number1');
            expect("Номер должен быть с опцией \"Учитывать перерасход\"", $mobile->accounting)->true();
        });
    }

    public function  testGetDestinationLabel()
    {
        $this->specify("Попытка получить название предназначенния", function () {
            expect("Нзвание должно быть 'Телефон'", $this->numbers('number1')->destinationLabel)->equals('Телефон');
            expect("Нзвание не должно быть 'Телефон'", $this->numbers('number2')->destinationLabel)->notEquals('Телефон');
        });
    }


    public function testAttachDocuments()
    {
        Document::deleteAll();
        $model = new Number();
        $model->_id = 'NumberId';

        $this->specify("Error if Document model invalid.", function () use ($model) {
            $model->attachDocument(Yii::getAlias('@data') . '/file2.pdf');
        }, [
            'throws' => new \RuntimeException()
        ]);

        $this->specify("Error if argument not instance of UploadedFile or valid path to file.", function ($file) use ($model) {
            $model->attachDocument($file);
        }, [
            'examples' => [
                ["",[]]
            ],
            'throws' => new \InvalidArgumentException()
        ]);

        $model->save(false);
        $this->specify("Attachment must be save.", function ($file) use ($model) {
            $model->attachDocument($file);
        }, [
            'examples' => [
                [Yii::getAlias('@data') . '/file2.pdf'],
                [new UploadedFile([
                    'name' => 'file1.jpg',
                    'tempName' => \Yii::getAlias('@data') . '/file1.jpg',
                    'type' => mime_content_type(Yii::getAlias('@data') . '/file1.jpg')
                ])]
            ],
        ]);

        $this->tester->seeRecord(Document::className(),['filename'=>'file2.pdf', 'contentType' => 'application/pdf', 'ownerId' => 'NumberId']);
        $this->tester->seeRecord(Document::className(),['filename'=>'file1.jpg', 'contentType' => 'image/jpeg', 'ownerId' => 'NumberId']);
    }

    public function testGetDocuments() {
        Document::deleteAll();
        $this->specify("Attachment must be exist",function () {
            $numberId = $this->tester->haveRecord(Number::className(),['number'=>'9999999999']);
            $model = $this->tester->grabRecord(Number::className(), ['_id' => $numberId]);
            $model->attachDocument(\Yii::getAlias('@data') . '/file1.jpg');
            $model->attachDocument(\Yii::getAlias('@data') . '/file2.pdf');
            expect(count($model->documents))->equals(2);
            foreach ($model->documents as $document) {
                expect($document instanceof Document)->true();
                expect($document->ownerId)->equals($model->getPrimaryKey());
            }
        });
    }

    public function testUpdateHistory() {
        $this->specify("History must be updated", function($newOwnerId, array $history, array $expectedHistory) {
            $model = Number::findOne(['_id'=>'number1']);
            $model->history = $history;
            $model->ownerId = $newOwnerId;
            expect($model->updateHistory())->equals($expectedHistory);
        },[
            'examples' => [
                [null, [], []],
                [1,[],[]],
                [2,
                    [
                        ['ownerId'=>1, 'rentDate'=>new MongoDate(strtotime('13-10-2013'))]
                    ],
                    [
                        ['ownerId'=>1, 'rentDate'=>new MongoDate(strtotime('13-10-2013')),'returnDate' => new MongoDate(time())],
                        ['ownerId'=>2, 'rentDate'=>new MongoDate(time())]
                    ]
                ],
                [2,
                    [
                        ['ownerId'=>1, 'rentDate'=>new MongoDate(strtotime('13-10-2013')), 'returnDate'=>new MongoDate(strtotime('13-11-2013'))]
                    ],
                    [
                        ['ownerId'=>1, 'rentDate'=>new MongoDate(strtotime('13-10-2013')), 'returnDate'=>new MongoDate(strtotime('13-11-2013'))],
                        ['ownerId'=>2, 'rentDate'=>new MongoDate(time())]
                    ]
                ],
                [null,
                    [
                        ['ownerId'=>1, 'rentDate'=>new MongoDate(strtotime('13-10-2013')), 'returnDate'=>new MongoDate(strtotime('13-11-2013'))],
                        ['ownerId'=>2, 'rentDate'=>new MongoDate(strtotime('13-10-2014'))]
                    ],
                    [
                        ['ownerId'=>1, 'rentDate'=>new MongoDate(strtotime('13-10-2013')), 'returnDate'=>new MongoDate(strtotime('13-11-2013'))],
                        ['ownerId'=>2, 'rentDate'=>new MongoDate(strtotime('13-10-2014')), 'returnDate' => new MongoDate(time())]
                    ]
                ]
            ]
        ]);
    }

    public function testFindByOwner() {
        $this->specify("Searching by owner must be work", function() {
            $owner = $this->employees('employee1');
            expect(Number::findAll(['ownerId' => $owner->id]))->equals(Number::findByOwner($owner->fullName,$owner->post)->all());
            expect("Numbers must be find", Number::findByOwner($owner->fullName)->count())->greaterThan(0);
            expect("Numbers must be find", Number::findByOwner($owner->fullName,$owner->post)->count())->greaterThan(0);
            expect("Numbers must be not find", Number::findByOwner("Не существующий сотрудник")->count())->equals(0);
            expect("Numbers must be not find", Number::findByOwner($owner->fullName, "Не существующая должность")->count())->equals(0);
        });
    }

    public function testLimitValidation() {
        $this->specify("if model is accounting, limit must be non empty", function() {
            $model = new Number();
            $model->options = [Number::OPTION_ACCOUNTING];
            $model->validate(['limit']);
            expect($model->hasErrors())->true();
        });
    }

    public function testBeforeSave() {
        $this->specify("ownerId must be set before save number",function($ownerFixture) {
            /** @var $owner Employee */
            $owner = $this->employees($ownerFixture);
            $model = new Number(['scenario' => 'create']);
            $model->number = '1234567890';
            $model->ownerName = $owner->fullName;
            $model->ownerPost = $owner->post;
            $model->operatorId = $this->operators('operator1')->getPrimaryKey();
            $model->save();
            $this->tester->seeInCollection($model::collectionName(),[
                'number' => '1234567890',
                'ownerId' => $owner->getPrimaryKey()
            ]);

            Number::deleteAll(['number' => '1234567890']);
        },[
            'examples' => [
                ['employee1'],['employee4']
            ]
        ]);
        $this->specify("ownerId must be null if ownerName is empty string",function() {
            $model = $this->numbers('number1');
            /** @var $model Number */
            $model->setScenario('update');
            $model->ownerName = "";
            $model->save();
            $this->tester->seeInCollection($model::collectionName(),[
                'number' => $model['number'],
                'ownerId' => null
            ]);
        });
    }

}