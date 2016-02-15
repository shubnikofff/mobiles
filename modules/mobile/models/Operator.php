<?php

namespace app\modules\mobile\models;

use Yii;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for collection "mobile.operator".
 *
 * @property \MongoId|string $_id
 * @property mixed $id
 * @property string $name
 * @property string $contact
 * @property string $contract
 */
class Operator extends ActiveRecord
{
    /**
     * @return string
     */
    public static function collectionName()
    {
        return 'mobile.operator';
    }

    /**
     * @return string
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'contact',
            'contract',
        ];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'contact', 'contract'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'contact' => 'Номер договора',
            'contract' => 'Контакты',
        ];
    }

    /**
     * @return array
     */
    public static function items() {
        $items = [];
        foreach (self::find()->select(['_id','name'])->asArray()->all() as $data) {
            $items[(string)$data['_id']] = $data['name'];
        }
        return $items;
    }
}
