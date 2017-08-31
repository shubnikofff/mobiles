<?php
/**
 * mobiles
 * Created: 30.08.17 14:45
 * @copyright Copyright (c) 2017 OSKR NIAEP
 */

namespace app\commands;
use app\modules\mobile\models\Document;
use app\modules\mobile\models\Number;
use yii\console\Controller;


/**
 * Class GridfsController
 *
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 */

class GridfsController extends Controller
{
    public function actionIndex()
    {

        foreach (Number::findAll(['number' => ['$ne' => '']]) as $number) {
            foreach (Document::findAll(['ownerId' => $number->_id]) as $key => $document) {
                $name = explode('.', $document->filename);
                $document->writeFile('docdump/'.$number->number.'_'.$key.'.'.array_pop($name));
            }
        }

    }
}