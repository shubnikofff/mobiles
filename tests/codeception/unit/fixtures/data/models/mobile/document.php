<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 06.03.15
 * Time: 13:20
 */
$file =  Yii::getAlias('@data') . '/file2.pdf';
return [
    'file1' => [
        'file' => $file,
        'ownerId' => 'number1',
        'contentType' => mime_content_type($file)
    ]
];