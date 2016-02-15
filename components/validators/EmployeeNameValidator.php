<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 05.02.15
 * Time: 10:17
 */

namespace app\components\validators;

use app\modules\directory\models\Employee;
use yii\validators\Validator;

class EmployeeNameValidator extends Validator
{
    public $postAttribute;

    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = trim(preg_replace("/ +/", " ", $model->$attribute));
        $query = Employee::findByName($model->$attribute);

        $postAttribute = $this->postAttribute;
        if ($postAttribute !== null) {
            $model->$postAttribute = trim(preg_replace("/ +/", " ", $model->$postAttribute));
            $query->andWhere(['post' => $model->$postAttribute]);
        }

        if (!$query->count()) {
            $this->addError($model, $attribute, "Сотрудник «{$model->$attribute}»" . ($postAttribute !== null ? " с должностью «{$model->$postAttribute}»" : "") . " не найден.");
        }
    }
}