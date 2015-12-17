<?php

namespace app\widgets\ckeditor;

class CKEditorAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/widgets/ckeditor/assets';
    public $depends = ['yii\web\JqueryAsset'];

    public function init()
    {
        $this->js[]  = 'js/blueimp-gallery.min.js';
        $this->css[] = 'css/blueimp-gallery.min.css';
    }

}