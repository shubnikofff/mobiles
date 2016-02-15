<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.03.15
 * Time: 13:17
 */

namespace app\tests\codeception\unit\fixtures;
use yii\mongodb\ActiveFixture;

class DocumentFixture extends ActiveFixture{
    public $modelClass = 'app\modules\mobile\models\Document';
    public $dataFile = '@tests/codeception/unit/fixtures/data/models/mobile/document.php';
}