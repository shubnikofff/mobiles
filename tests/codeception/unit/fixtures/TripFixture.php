<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.04.15
 * Time: 16:16
 */

namespace app\tests\codeception\unit\fixtures;


use yii\mongodb\ActiveFixture;

class TripFixture extends ActiveFixture{
    public $modelClass = 'app\modules\mobile\models\Trip';
    public $dataFile = '@tests/codeception/unit/fixtures/data/models/mobile/trip.php';
    public $depends = [
        'app\tests\codeception\unit\fixtures\NumberFixture',
        'app\tests\codeception\unit\fixtures\EmployeeFixture',
    ];
}