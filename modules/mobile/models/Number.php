<?php

namespace app\modules\mobile\models;

use app\components\validators\EmployeeNameValidator;
use Yii;
use app\modules\directory\models\Employee;
use yii\db\Query;
use yii\mongodb\ActiveQuery;
use yii\mongodb\ActiveRecord;
use yii\web\UploadedFile;
use MongoDate;
use yii\mongodb\validators\MongoIdValidator;

/**
 * This is the model class for collection "mobile.number".
 *
 * @property \MongoId|string $_id
 * @property string $number
 * @property integer $ownerId
 * @property Employee $owner
 * @property \MongoId|string $operatorId
 * @property Operator $operator
 * @property string $operatorName
 * @property string $destination
 * @property string $destinationLabel
 * @property integer $limit
 * @property array $options
 * @property array $documents
 * @property boolean $isTrip
 * @property boolean $showInDirectory
 * @property boolean $accounting
 * @property array $history
 * @property string $comment
 */
class Number extends ActiveRecord
{
    const DESTINATION_PHONE = 'phone';
    const DESTINATION_MODEM = 'modem';
    const OPTION_ACCOUNTING = 'accounting';
    const OPTION_TRIP = 'trip';
    const OPTION_DIRECTORY = 'directory';

    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'mobile.number';
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'number',
            'ownerId',
            'operatorId',
            'destination',
            'limit',
            'options',
            'history',
            'comment',
        ];
    }

    public function init()
    {
        $this->destination = self::DESTINATION_PHONE;
        $this->limit = null;
        $this->options = [];
        $this->history = [];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['number', 'ownerId', 'operatorId', 'destination', 'limit', 'options', 'comment'];
        $scenarios['update'] = ['ownerId', 'operatorId', 'destination', 'limit', 'options', 'comment'];
        return $scenarios;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['number', 'operatorId'], 'required'],
            ['number', 'match', 'pattern' => '/^9[0-9]{9}$/'],
            ['number', 'unique'],
            ['ownerId', 'filter', 'filter' => function ($value) {
               return empty($value) ? null : (int)$value;
            }],
            ['ownerId', 'exist', 'targetClass' => Employee::className(), 'targetAttribute' => 'id'],
            ['limit', 'integer', 'min' => 0],
            ['limit', function ($attribute) {
                if ($this->accounting && empty($this->$attribute)) {
                    $this->addError($attribute, "При выбранной опции «Учитывать перерасход» лимит должен быть указан");
                }
            }, 'skipOnEmpty' => false],
            ['operatorId', MongoIdValidator::className(), 'forceFormat' => 'object'],
            ['operatorId', 'exist', 'targetClass' => Operator::className(), 'targetAttribute' => '_id'],
            ['destination', 'in', 'range' => [self::DESTINATION_MODEM, self::DESTINATION_PHONE]],
            ['options', function ($attribute) {
                $range = array_keys(Number::optionItems());
                foreach ($this->$attribute as $item) {
                    if (!in_array($item, $range)) {
                        $this->addError('options', "Опция «{$item}» не существует.");
                    }
                }
            }],
            ['comment', 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'number' => 'Номер',
            'ownerId' => 'Сотрудник',
            'isTrip' => 'Командиоровочная',
            'operatorId' => 'Оператор',
            'destination' => 'Используется как',
            'options' => 'Опции',
            'limit' => 'Лимит',
            'newDocuments' => 'Новые документы',
            'comment' => 'Примечание',
        ];
    }

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            $this->options = is_array($this->options) ? array_values($this->options) : [];
            return true;
        } else return false;
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getOwner()
    {
        return $this->hasOne(Employee::className(), ['id' => 'ownerId']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getOperator()
    {
        return $this->hasOne(Operator::className(), ['_id' => 'operatorId']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['ownerId' => '_id']);
    }

    /**
     * @return boolean
     */
    public function getIsTrip()
    {
        return in_array(self::OPTION_TRIP, $this->options);
    }

    /**
     * @return boolean
     */
    public function getShowInDirectory()
    {
        return in_array(self::OPTION_DIRECTORY, $this->options);
    }

    /**
     * @return boolean
     */
    public function getAccounting()
    {
        return in_array(self::OPTION_ACCOUNTING, $this->options);
    }

    /**
     * @return string
     */
    public function getDestinationLabel()
    {
        return self::destinationItems()[$this->destination];
    }

    /**
     * @return array
     */
    public static function destinationItems()
    {
        return [
            self::DESTINATION_PHONE => 'Телефон',
            self::DESTINATION_MODEM => 'Модем'
        ];
    }

    /**
     * @return array
     */
    public static function optionItems()
    {
        return [
            self::OPTION_TRIP => "Командировачная",
            self::OPTION_ACCOUNTING => "Учитывать перерасход",
            self::OPTION_DIRECTORY => "Отображать в справочнике",
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (in_array($this->scenario, ['create', 'update'])) {
                //$owner = Employee::findByName($this->ownerName)->andWhere(['post' => $this->ownerPost])->one();
                //$this->ownerId = $owner instanceof Employee ? $owner->getPrimaryKey() : null;
                $this->updateHistory();
            }
            if (!empty($this->limit)) {
                $this->limit = (int)$this->limit;
            }
            return true;
        }
        return false;
    }

    /**
     * @param $name
     * @param null $post
     * @param bool $nameLike
     * @return ActiveQuery
     */
    public static function findByOwner($name, $post = null, $nameLike = false)
    {
        $ownerId = [];
        $subQuery = Employee::findByName($name, $nameLike)->andFilterWhere(['post' => $post]);
        foreach ($subQuery->all() as $owner) {
            $ownerId[] = $owner->getPrimaryKey();
        }
        return self::find()->where(['in', 'ownerId', $ownerId]);
    }

    /**
     * @param UploadedFile|string $file
     * @throws \InvalidArgumentException|\RuntimeException
     * @return bool
     */
    public function attachDocument($file)
    {
        $document = new Document();
        if ($file instanceof UploadedFile) {
            $document->filename = $file->name;
            $document->contentType = $file->type;
        } elseif (is_file($file)) {
            $document->filename = basename($file);
            $document->contentType = mime_content_type($file);
        } else throw new  \InvalidArgumentException(__METHOD__ . ": unable attach document '{$file}'.'");
        $document->ownerId = $this->getPrimaryKey();
        $document->file = $file;
        if ($document->save()) {
            return true;
        } else {
            throw new \RuntimeException(__METHOD__ . ": unable attach document '{$document->filename}': " . implode(", ", array_keys($document->getErrors())) . " is invalid");
        }
    }

    /**
     * @return array
     */
    public function updateHistory()
    {
        if ($this->isAttributeChanged('ownerId')) {
            $history = $this->history;
            $historyCount = count($history);
            if ($historyCount && !array_key_exists('returnDate', $history[$historyCount - 1])) {
                $history[$historyCount - 1]['returnDate'] = new MongoDate(time());
            }
            if (!is_null($this->ownerId)) {
                $history[] = [
                    'ownerId' => $this->ownerId,
                    'rentDate' => new MongoDate(time())
                ];
            }
            $this->history = $history;
        }
        return $this->history;
    }

    /**
     * @param $param
     * @return array
     */
    static public function ownerList($param)
    {
        $list['results'] = (new Query())
            ->select(['e.id', 'concat_ws(" ", e.last_name, e.first_name, e.middle_name) as name', 'e.post', 'b.branch_name as division'])
            ->from(['e' => Employee::tableName()])
            ->leftJoin('branches b', 'e.branch = b.id')
            ->where(['like', 'last_name', $param])->all();

        return $list;
    }
}