<?php
namespace app\widgets\dynblocks;

use yii\web\AssetBundle;

class SlickAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/dynblocks/slick/';
    public $css = [
        "slick.css",
        'mobil-slick-theme.css',
    ];
    public $js = [
        //'slick.js',
        'slick.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
