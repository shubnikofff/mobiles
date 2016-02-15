<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 12.03.15
 * Time: 19:50
 */

namespace app\tests\codeception\unit\fixtures;

use yii\mongodb\ActiveFixture;

class ReportItemFixture extends ActiveFixture{
    public $modelClass = 'app\modules\mobile\models\ReportItem';
    public $dataFile = '@tests/codeception/unit/fixtures/data/models/mobile/reportItem.php';
}