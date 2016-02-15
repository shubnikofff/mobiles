<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.04.15
 * Time: 17:13
 */

namespace app\tests\codeception\unit\modules\mobile\models;


use app\modules\mobile\models\Trip;
use app\tests\codeception\unit\fixtures\TripFixture;
use Codeception\Specify;
use yii\codeception\TestCase;
use MongoDate;

class TripTest extends TestCase{
    use Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'reports' => [
                'class' => TripFixture::className(),
            ],
        ];
    }

    public function testGetStatus() {
        $this->specify("Status must be correct", function($duration, $possession, $complete, $expected) {
            $model = new Trip();
            $model['duration'] = $duration;
            $model['numberPossession'] = $possession;
            $model['complete'] = $complete;
            expect($model->getStatus())->equals($expected);
        },[
            'examples' => [
                [
                    'duration' => ['to' =>  new MongoDate(strtotime('08.03.2015'))],
                    'possession' => ['to' =>  new MongoDate(strtotime('07.03.2015'))],
                    'complete' => true,
                    'expected' => Trip::STATUS_COMPLETE
                ],
                [
                    'duration' => ['to' =>  new MongoDate(strtotime('08.03.2015'))],
                    'possession' => ['to' =>  new MongoDate(strtotime('11.03.2015'))],
                    'complete' => true,
                    'expected' => Trip::STATUS_COMPLETE
                ],
                [
                    'duration' => ['to' =>  new MongoDate(strtotime('08.03.2015'))],
                    'possession' => ['to' =>  new MongoDate(strtotime('12.03.2015'))],
                    'complete' => true,
                    'expected' => Trip::STATUS_EXPIRED
                ],
                [
                    'duration' => ['to' =>  new MongoDate()],
                    'possession' => ['to' =>  null],
                    'complete' => false,
                    'expected' => Trip::STATUS_INCOMPLETE
                ],
                [
                    'duration' => ['to' =>  new MongoDate(time()-Trip::TIME_TO_RETURN_NUMBER - 1)],
                    'possession' => ['to' =>  null],
                    'complete' => false,
                    'expected' => Trip::STATUS_EXPIRED
                ],
            ]
        ]);
    }
}