<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 12.03.15
 * Time: 12:11
 */

namespace app\tests\codeception\unit\modules\mobile\models;

use app\modules\mobile\models\Number;
use app\modules\mobile\models\Report;
use app\modules\mobile\models\ReportItem;
use app\modules\mobile\models\ReportSearch;
use app\tests\codeception\unit\fixtures\ReportFixture;
use app\tests\codeception\unit\fixtures\ReportItemFixture;
use Yii;
use yii\base\ErrorException;
use yii\codeception\DbTestCase;
use MongoDate;
use \Codeception\Specify;
use app\modules\mobile\models\MTSXML;
use app\modules\mobile\models\Operator;
/**
 * Class ReportTest
 * @package app\tests\codeception\unit\modules\mobile\models
 * @method reports
 */
class ReportTest extends DbTestCase{
    use Specify;
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'reports' => [
                'class' => ReportFixture::className(),
            ],
        ];
    }

    public function testGenerate() {
        $xml = simplexml_load_file(Yii::getAlias('@data') . '/mts_report.xml', MTSXML::className());
        $report = Report::generate($xml);
        $this->tester->assertTrue($report instanceof Report);

        //codecept_debug(ReportSearch::getOperatorItems());
        //$report = new Report();
        //codecept_debug( Yii::$app->formatter->asDate(mktime(null,null,null,1,1,2015),'LLLL yyyy'));
        //codecept_debug();
        //Report::find();



    }

    /*public function testAddItem() {

        $this->specify("Error if accounting is set and limit is null", function() {
            $this->tester->haveInCollection(Number::collectionName(),['number' => '9999999999', 'options' => ['accounting'], 'limit' => null]);
            (new Report())->addItem('9999999999', 999.99);
        },[
            'throws' => new ErrorException()
        ]);

    }

    public function testBeforeSave() {
        $this->specify("Report must be save", function() {
            $operatorReport = simplexml_load_file(Yii::getAlias('@data') . '/mts_report.xml', MTSXML::className());
            $date = new MongoDate($operatorReport->getTimeStamp());
            $model = new Report([
                'date' => $date,
                'rawItems' => $operatorReport->getItems()
            ]);
            expect($model->save())->true();
            $this->tester->seeInCollection(Report::collectionName(),[
                '_id' => $model->getPrimaryKey(),
                'date' => $date,
                'outsideDb' => [
                    [ "number" => "9100062670" , "expenditure" => 0.0],
                    [ "number" => "9100071961" , "expenditure" => 474.2],
                    [ "number" => "9100074240" , "expenditure" => 448.95]
                ],
                'outsideOperatorReport' => [
                    [ "_id" => "number3" , "number" => "3333333333"],
                    [ "_id" => "number4" , "number" => "4444444444"]
                ]
            ]);
            $this->tester->seeInCollection(ReportItem::collectionName(),[
                'reportId' => $model->getPrimaryKey(), "number" => "1111111111" , "ownerName" => "Иванов Иван Иванович" , "limit" => 1500 , "expenditure" => 2415.12 , "overrun" => 915.12 , "comment" => "Comment for number 1111111111",
            ]);
            $this->tester->seeInCollection(ReportItem::collectionName(),[
                'reportId' => $model->getPrimaryKey(), "number" => "2222222222" , "ownerName" => "Иванов Иван Иванович" , "limit" =>  null  , "expenditure" => 113.52 , "comment" => ""
            ]);
        });
    }

    public function testGetItems() {
        $model = Report::findOne('report1');

        $this->specify("Check count received overrun items", function() use ($model){
            /**
             * @var Report $model
             */
    /*        expect(count($model->overrunItems))->equals(2);
            expect(count($model->expenditureItems))->equals(1);
            expect(count($model->getExpenditureItems(1000)->all()))->equals(4);
        });
    }

    public function testGetOutsideDB () {
        $this->specify("Check nowInDb state", function() {
            /** @var $model Report */
      /*      $model = $this->reports('report1');
            foreach($model->getOutsideDb() as $item) {
                switch($item['number']) {
                    case '9100071961' : expect($item['nowInDb'])->false();break;
                    case '9100333961' : expect($item['nowInDb'])->false();break;
                    case '4444444444' : expect($item['nowInDb'])->true();break;
                }
            }
        });
    }*/

}