<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 29.01.15
 * Time: 13:37
 */

namespace app\tests\codeception\_support;


use Codeception\Module;
use Codeception\TestCase;
use yii\test\FixtureTrait;
use app\tests\codeception\unit\fixtures\NumberFixture;
use app\modules\mobile\models\Document;

class FixtureHelper extends Module{
    use FixtureTrait {
        loadFixtures as protected;
        fixtures as protected;
        globalFixtures as protected;
        unloadFixtures as protected;
        getFixtures as protected;
        getFixture as protected;
    }

    public function _beforeSuite($settings = array())
    {
        Document::deleteAll();
        $this->loadFixtures();
    }

    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    public function fixtures()
    {
        return [
            'numbers' => [
                'class' => NumberFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/models/mobile/number.php'
            ],
        ];
    }
}