<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 26.03.15
 * Time: 9:16
 */

namespace app\modules\mobile\assets;

use yii\web\AssetBundle;

class NumberAsset extends AssetBundle{
    public $sourcePath = '@app/modules/mobile/views/number/js/';

    public $js = [
        'mobile.number.index.js'
    ];
}