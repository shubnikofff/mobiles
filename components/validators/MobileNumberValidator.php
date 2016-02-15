<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 10.04.15
 * Time: 13:16
 */

namespace app\components\validators;


use app\modules\mobile\models\Number;
use yii\validators\ExistValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\Validator;

/**
 * Class MobileNumberValidator
 * @package app\components\validators
 */
class MobileNumberValidator extends Validator  {
    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $regexpValidator = new RegularExpressionValidator(['pattern' => '/^9[0-9]{9}$/']);
        if(!$regexpValidator->validate($model->$attribute)) {
            $this->addError($model, $attribute, '{attribute} имеет неверный формат');
            return;
        }

        $existValidator =  new ExistValidator(['targetClass' => Number::className(), 'targetAttribute' => 'number']);
        if(!$existValidator->validate($model->$attribute)) {
            $this->addError($model, $attribute, "{attribute} не существует");
        }
    }

}