<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 21.01.15
 * Time: 16:28
 */

namespace app\modules\mobile\models;

use Yii;
use yii\mongodb\file\ActiveRecord;
use app\modules\mobile\models\Number;

/**
 * Class Document
 * @package app\modules\mobile\models
 * @property string $_id MongoId
 * @property array $filename
 * @property string $uploadDate
 * @property string $length
 * @property string $chunkSize
 * @property string $md5
 * @property array $file
 * @property string $newFileContent
 * @property \MongoId|string $ownerId
 * @property string $contentType
 */
class Document extends ActiveRecord{

    public static function collectionName()
    {
        return 'mobile';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(),
            [
                'ownerId',
                'contentType'
            ]
        );
    }

    public function attributeLabels()
    {
        return [
            'filename' => "Название документа"
        ];
    }


    public function rules()
    {
        return array_merge(parent::rules(),
            [
                [['ownerId', 'contentType'],'required'],
                ['ownerId', 'exist', 'targetClass' => Number::className(), 'targetAttribute' => '_id'],
                [['file'], 'file'],
            ]
        );
    }


}