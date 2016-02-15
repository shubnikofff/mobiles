<?php
/**
 * Created by PhpStorm.
 * User: bill
 * period: 12.03.15
 * Time: 12:51
 */

namespace app\modules\mobile\models;

use yii\base\ErrorException;

class MTSXML extends OperatorXML
{

    public function getContract()
    {
        return $this->b['an']->__toString();
    }

    public function getYear()
    {
        return date('Y', strtotime($this->b['bd']));
    }

    public function getMonth()
    {
        return date('n', strtotime($this->b['bd']));
    }

    public function getItems()
    {
        $items = [];
        foreach ($this->rp->pss->ps as $item) {
            $items[] = [
                'number' => substr($item['m'], 1),
                'expenditure' => round(floatval(str_replace(',', '.', $item['a'])), 2)
            ];
        }
        return $items;
    }

    public function validate()
    {
        try {
            if ($this->b['an'] === null || $this->b['bd'] === null) {
                return false;
            }
            foreach ($this->rp->pss->ps as $item) {
                if ($item['m'] === null || $item['a'] === null) {
                    return false;
                }
            }
        } catch (ErrorException $e) {
            return false;
        }
        return true;
    }
}