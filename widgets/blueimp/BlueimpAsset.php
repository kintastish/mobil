<?php

namespace app\widgets\blueimp;

class BlueimpAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/widgets/blueimp/assets';
    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        $this->js[]  = 'js/blueimp-gallery.min.js';
        $this->css[] = 'css/blueimp-gallery.min.css';
    }

}