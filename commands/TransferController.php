<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 30.03.15
 * Time: 16:55
 */

namespace app\commands;

use app\modules\mobile\models\Document;
use app\modules\mobile\models\Report;
use app\modules\mobile\models\ReportItem;
use app\modules\mobile\models\Trip;
use Yii;
use app\modules\mobile\models\Number;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;
use yii\mongodb\Collection;
use yii\db\Query;
use app\modules\mobile\models\Operator;
use MongoDate;

class TransferController extends Controller
{
    public $numberTableName = 'mobile';

    public $operatorTableName = 'mobile_operator';

    public $tripTableName = 'trip_logger';

    public $documentsPath = '/var/www/html/teleport/protected/data/mobile/document';

    public $deleteReports = true;

    private $operatorJunction = [];

    public function options($actionID)
    {
        return array_merge([
            'numberTableName',
            'operatorTableName',
            'tripTableName',
            'documentsPath'
        ], parent::options($actionID));
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (in_array($action->id, ['numbers', 'documents']) && !is_dir($this->documentsPath)) {
                $this->stdout("Путь к каталогу документов неверный или не указан.\nЧтобы задать путь, нужно установить опцию: --documentsPath=dir_name\n");
                return false;
            }
            return true;
        } else {
            return false;
        }
    }


    public function actionIndex()
    {
        $this->stdout("Доступно:\n");
        $this->stdout(" transfer/numbers\n transfer/operators", Console::FG_YELLOW);
        $this->stdout("\n");
    }

    public function actionNumbers()
    {
        if($this->deleteReports) {
            $this->cleanCollection(Report::collectionName());
            $this->cleanCollection(ReportItem::collectionName());
        }
        $this->actionOperators();
        $query = (new Query())->from($this->numberTableName);
        $this->stdout("Загружаю номера\n", Console::FG_BLUE, Console::BOLD);
        $this->stdout("Источник: таблица '$this->numberTableName' " . $query->count() . " записей.\nЦелевая коллекция: '" . Number::collectionName() . "'\n");
        $this->cleanCollection(Number::collectionName());

        $count = 0;
        foreach ($query->all() as $item) {
            $number = new Number([
                'number' => $item['number'],
            ]);
            $number->ownerId = (int)$item['owner_id'];
            $number->operatorId = $this->operatorJunction[$item['operator_id']];
            $number->destination = $item['type'] == '1' ? Number::DESTINATION_PHONE : Number::DESTINATION_MODEM;
            $number->limit = ($item['limit'] == '0' || $item['limit'] === null) ? null : (int)$item['limit'];

            $options = [];
            if ($item['rent_date'] === null && $number->limit === null) {
                $options[] = Number::OPTION_TRIP;
            }
            if ($number->limit !== null && $item['accounting'] == '1') {
                $options[] = Number::OPTION_ACCOUNTING;
            }
            $number->options = $options;

            $history = [];
            if ($item['rent_date'] !== null && $item['owner_id'] !== null) {
                $history[] = [
                    'rentDate' => new MongoDate((int)$item['rent_date']),
                    'ownerId' => (int)$item['owner_id']
                ];
            }
            $number->history = $history;
            $number->comment = $item['comment'];

            if ($number->save(false)) {
                $count++;
            }
        }
        $this->stdout("Успешно загружено $count записей\n", Console::BOLD, Console::FG_GREEN);
        $this->stdout("\n");
        $this->actionDocuments();
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionOperators()
    {
        $this->stdout("Загружаю операторов\n", Console::FG_BLUE, Console::BOLD);
        $query = (new Query())->from($this->operatorTableName);
        $this->stdout("Источник: таблица '$this->operatorTableName' " . $query->count() . " записей.\nЦелевая коллекция: '" . Operator::collectionName() . "'\n");
        $this->cleanCollection(Operator::collectionName());

        foreach ($query->all() as $item) {
            $operator = new Operator([
                'name' => $item['name'],
                'contract' => $item['contract'],
                'contact' => $item['contact']
            ]);
            if ($operator->save()) {
                $this->operatorJunction[$item['id']] = $operator->getPrimaryKey();
            }
        }
        $this->stdout("Успешно загружено " . count($this->operatorJunction) . " записей\n", Console::BOLD, Console::FG_GREEN);
        $this->stdout("\n");
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionDocuments()
    {
        $this->stdout("Добавляю документы\n", Console::FG_BLUE, Console::BOLD);
        $this->stdout("Источник: $this->documentsPath\n");
        $this->stdout("Очищаю хранилище документов\n", Console::FG_RED, Console::BOLD);
        Document::deleteAll([]);

        $aborted = ['dirs' => [], 'files' => []];
        $success = 0;

        foreach (scandir($this->documentsPath) as $dirName) {
            $directory = $this->documentsPath . '/' . $dirName;
            if (is_dir($directory)) {
                if ($number = Number::findOne(['number' => $dirName])) {
                    foreach (scandir($directory) as $fileName) {
                        $file = $directory . '/' . $fileName;

                        if (is_file($file)) {
                            if (substr($fileName, 0, 1) !== '.' && $number->attachDocument($file)) {
                                $success++;
                            } else {
                                $aborted['files'][] = $file;
                            }
                        }
                    }
                } else {
                    $aborted['dirs'][] = $dirName;
                }
            }
        }
        $this->stdout("Успешно добавлено $success документов\n", Console::FG_GREEN, Console::BOLD);
        $this->stdout("Каталоги для которых не найдены номера:\n");
        foreach ($aborted['dirs'] as $dir) {
            $this->stdout("$dir\n");
        }
        $this->stdout("Файлы которые не удалось добавить:\n");
        foreach ($aborted['files'] as $file) {
            $this->stdout("$file\n");
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionTrip() {
        $this->stdout("Загружаю командировки\n", Console::FG_BLUE, Console::BOLD);
        $query = (new Query())->from($this->tripTableName);
        $this->stdout("Источник: таблица '$this->tripTableName' " . $query->count() . " записей.\nЦелевая коллекция: '" . Trip::collectionName() . "'\n");
        $this->cleanCollection(Trip::collectionName());
        $successCounter = 0;
        foreach ($query->all() as $item) {
            $trip = new Trip();

            $number = (new Query())->select(['number'])->from($this->numberTableName)->where(['id' => $item['mobile_id']])->one();
            $trip['numberId'] = Number::findOne(['number'=>$number['number']])->getPrimaryKey();

            $trip['employeeId'] = (int)$item['employee_id'];

            $trip['duration'] = [
                'from' => new MongoDate(strtotime("+1 day",(int)$item['rent_date'])),
                'to' => new MongoDate((int)$item['return_date'])
            ];

            $trip['numberPossession'] = [
                'from' => new MongoDate((int)$item['rent_date']),
                'to' => new MongoDate(strtotime($item['actual_return_date']))
            ];

            if($trip['numberPossession']['to'] !== null) {
                $trip['complete'] = true;
            }

            $trip['destination'] = $item['destination'];

            if($trip->save(false)){
                $successCounter++;
            }
        }
        $this->stdout("Успешно загружено {$successCounter} записей\n", Console::BOLD, Console::FG_GREEN);
        $this->stdout("\n");
        return Controller::EXIT_CODE_NORMAL;

    }

    private function cleanCollection($collectionName)
    {
        /** @var $collection Collection */
        $collection = Yii::$app->mongodb->getCollection($collectionName);
        $this->stdout("Очищаю колллекцию '" . $collection->fullName . "'\n", Console::FG_RED, Console::BOLD);
        return $collection->remove([]) ? Controller::EXIT_CODE_NORMAL : Controller::EXIT_CODE_ERROR;
    }
}