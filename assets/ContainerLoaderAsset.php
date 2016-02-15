<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 15.04.15
 * Time: 17:36
 */

namespace app\assets;


use yii\web\AssetBundle;

class ContainerLoaderAsset extends AssetBundle{
    public $sourcePath = '@app/components/widgets/assets';

    public $js = [
        'teleport.containerLoader.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}