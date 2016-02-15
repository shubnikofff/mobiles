<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 23.04.15
 * Time: 9:47
 */

namespace app\modules\mobile\models;

use SimpleXMLElement;

abstract class OperatorXML extends SimpleXMLElement
{

    /**
     * @return string
     */
    abstract public function getContract();

    /**
     * @return string
     */
    abstract public function getYear();

    /**
     * @return string
     */
    abstract public function getMonth();

    /**
     * @return array
     */
    abstract public function getItems();

    /**
     * @return bool
     */
    abstract function validate();

    /**
     * @return string
     */
    static public function className()
    {
        return get_called_class();
    }

}