<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 12.03.15
 * Time: 19:50
 */

namespace app\tests\codeception\unit\fixtures;

use yii\mongodb\ActiveFixture;

class ReportFixture extends ActiveFixture{
    public $modelClass = 'app\modules\mobile\models\Report';
    public $dataFile = '@tests/codeception/unit/fixtures/data/models/mobile/report.php';
    public $depends = [
        'app\tests\codeception\unit\fixtures\OperatorFixture',
        'app\tests\codeception\unit\fixtures\NumberFixture',
        'app\tests\codeception\unit\fixtures\ReportItemFixture',
    ];
}