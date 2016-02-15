<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 09.04.15
 * Time: 9:49
 */

namespace app\modules\mobile\models;

use app\modules\directory\models\Employee;
use app\components\validators\MobileNumberValidator;
use app\components\validators\EmployeeNameValidator;
use yii\mongodb\ActiveRecord;
use MongoDate;

/**
 * Class Trip - модель командировачных номеров
 *
 * @package app\modules\mobile\models
 * @property string $status
 * @property string $statusName
 * @property \MongoId $numberId
 * @property Number $number
 * @property \MongoId $employeeId
 * @property Employee $employee
 * @property array $duration
 * @property array $numberPossession
 * @property boolean $complete
 * @property string $destination
 */
class Trip extends ActiveRecord
{

    const STATUS_COMPLETE = 'complete';
    const STATUS_INCOMPLETE = 'incomplete';
    const STATUS_EXPIRED = 'expired';
    const TIME_TO_RETURN_NUMBER = 259200; // 3 суток

    public $mobileNumber;
    public $employeeName;
    public $employeePost;
    public $beginDate;
    public $endDate;
    public $rentNumberDate;
    public $returnNumberDate;

    public static function collectionName()
    {
        return 'mobile.trip';
    }

    public function attributes()
    {
        return [
            '_id',
            'numberId',
            'employeeId',
            'duration',
            'numberPossession',
            'complete',
            'destination'
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['edit'] = ['mobileNumber', 'employeeName', 'employeePost', 'beginDate', 'endDate', 'rentNumberDate', 'destination'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['mobileNumber', 'employeeName', 'beginDate', 'endDate', 'rentNumberDate', 'destination'], 'required'],
            ['mobileNumber', MobileNumberValidator::className()],
            ['mobileNumber', function ($attribute) {
                $number = Number::findOne(['number' => $this->$attribute]);
                if (!$number->isTrip) {
                    $this->addError($attribute, "Номер не является командировачным");
                    return;
                }
                $trip = self::findOne(['numberId'=>$number->getPrimaryKey(),'complete'=>false]);
                if($trip !== null && $this->getPrimaryKey() != $trip->getPrimaryKey()) {
                    $this->addError($attribute,"Номер уже выдан в командировку");
                    return;
                }
            }],
            ['employeeName', EmployeeNameValidator::className(), 'postAttribute' => 'employeePost'],
            [['beginDate', 'endDate', 'rentNumberDate'], 'date', 'format' => 'dd.mm.yyyy'],
            ['beginDate', function ($attribute) {
                if (strtotime($this->$attribute) > strtotime($this->endDate)) {
                    $this->addError($attribute, "Дата начала больше даты конца");
                }
            }],
            ['rentNumberDate', function ($attribute) {
                if (strtotime($this->$attribute) > strtotime($this->beginDate)) {
                    $this->addError($attribute, "Дата выдачи номера больше даты начала командировки");
                }
            }],
            [['employeePost', 'destination'], 'safe']
        ];
    }

    public function init()
    {
        $this->numberId = null;
        $this->employeeId = null;
        $this->duration = [
            'from' => null,
            'to' => null
        ];
        $this->numberPossession = [
            'from' => null,
            'to' => null
        ];
        $this->complete = false;
        $this->destination = "";
        parent::init();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'mobileNumber' => 'Номер',
            'employeeName' => 'Имя сотрудника',
            'employeePost' => 'Должность сотрудника',
            'statusName' => 'Статус',
            'beginDate' => 'Начало командировки',
            'endDate' => 'Конец командировки',
            'rentNumberDate' => 'Номер выдан',
            'returnNumberDate' => 'Номер сдан',
            'destination' => 'Пункт назначения',
            'complete' => 'Завершено'
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getNumber()
    {
        return $this->hasOne(Number::className(), ['_id' => 'numberId']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employeeId']);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        $allowedTime = $this->duration['to']->sec + self::TIME_TO_RETURN_NUMBER;
        if ($this->complete) {
            if ($allowedTime - $this->numberPossession['to']->sec < 0) {
                return self::STATUS_EXPIRED;
            } else {
                return self::STATUS_COMPLETE;
            }
        } else {
            if ($allowedTime - time() < 0) {
                return self::STATUS_EXPIRED;
            } else {
                return self::STATUS_INCOMPLETE;
            }
        }
    }

    public function getStatusName()
    {
        $name = "";
        switch ($this->getStatus()) {
            case self::STATUS_COMPLETE :
                $name = "Номер сдан вовремя";
                break;
            case self::STATUS_EXPIRED :
                $name = "Просрочено";
                break;
            case self::STATUS_INCOMPLETE :
                $name = "Номер выдан";
                break;
        }
        return $name;
    }

    public function complete() {
        $possession = $this->numberPossession;
        $possession['to'] = new MongoDate();
        $this->numberPossession = $possession;
        $this->complete = true;
        return $this->save(false);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            switch ($this->getScenario()) {
                case 'edit' :
                    $this->numberId = Number::findOne(['number' => $this->mobileNumber])->getPrimaryKey();
                    $this->employeeId = Employee::findByName($this->employeeName)->andWhere(['post' => $this->employeePost])->one()->getPrimaryKey();
                    $this->duration = [
                        'from' => new MongoDate(strtotime($this->beginDate)),
                        'to' => new MongoDate(strtotime($this->endDate))
                    ];
                    $this->numberPossession = ['from' => new MongoDate(strtotime($this->rentNumberDate))];
                    break;
                case 'complete' :
                    break;
            }
            return true;
        } else {
            return false;
        }
    }


}