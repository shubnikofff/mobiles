<?php

namespace app\modules\directory\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for collection "employee".
 *
 * @property string $last_name
 * @property string $first_name
 * @property string $middle_name
 * @property string $fullName
 * @property string $post
 */
class Employee extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employees';
    }


    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'last_name',
            'first_name',
            'middle_name',
            'organic_number',
            'post'
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'last_name' => 'Фамилия',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'post' => 'Должность',
            'fullName' => 'Имя сотрудника'
        ];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return implode(' ', [$this->last_name, $this->first_name, $this->middle_name]);
    }

    /**
     * Build query with full name condition
     * @param mixed $name
     * @param bool $like
     * @return ActiveQuery
     */
    static function findByName($name, $like = false)
    {
        if ($like) {
            $name = "%{$name}%";
        }
        $concatWS = new Expression("concat_ws(' ', last_name, first_name, middle_name) like '{$name}'");
        return self::find()->where($concatWS);
    }
}