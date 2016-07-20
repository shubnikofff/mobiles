<?php
/**
 * mobiles
 * Created: 19.07.16 13:35
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\modules\mobile\models;

use yii\data\BaseDataProvider;

/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ReportMaker
 */
interface ReportMaker
{
    /**
     * @param $reportId
     * @return BaseDataProvider
     */
    public function getDataProvider($reportId);

    /**
     * @return array
     */
    public function getColumns();

    /**
     * @param $operator
     * @param $timestamp
     * @return mixed
     */
    public function getReportHeader($operator, $timestamp);
}