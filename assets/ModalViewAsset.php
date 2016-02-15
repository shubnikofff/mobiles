<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 16.04.15
 * Time: 18:27
 */

namespace app\assets;


use yii\web\AssetBundle;

class ModalViewAsset extends AssetBundle{
    public $sourcePath = '@app/components/widgets/assets';

    public $js = [
        'teleport.modalView.js'
    ];

    public $depends = [
        'app\assets\AppAsset',
    ];
}