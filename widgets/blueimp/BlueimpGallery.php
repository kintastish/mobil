<?php
namespace app\widgets\blueimp;

use yii\base\Widget;
use yii\helpers\Html;


class BlueimpGallery extends Widget
{
    const LIGHTBOX_MODE = 1;
    const CAROUSEL_MODE = 2;
    public $items = [];
    public $mode = self::LIGHTBOX_MODE;
    public $options = [];
    
    private $_images;
    private $_opts;

    public function init()
    {
        parent::init();
        $this->_images = \yii\helpers\Json::encode($this->items);
    }

    public function run()
    {
        //return Html::encode($this->message);
        //return $this->getView();
        $this->setOptions();
        $this->renderSnippet();
        $this->registerAsset();
        $this->registerJs();
    }

    private function setOptions()
    {
        if ($this->mode == self::CAROUSEL_MODE) {
            $this->options['carousel'] = true;
            $this->options['container'] = '#blueimp-gallery-carousel';
        }
    }   

    private function registerAsset()
    {
        BlueimpAsset::register($this->getView());
    }

    private function renderSnippet()
    {
        if ($this->mode == self::LIGHTBOX_MODE) {
            echo '<div id="blueimp-gallery" class="blueimp-gallery">
                    <div class="slides"></div>
                    <h3 class="title"></h3>
                    <a class="prev">‹</a>
                    <a class="next">›</a>
                    <a class="close">×</a>
                    <a class="play-pause"></a>
                    <ol class="indicator"></ol>
                </div>';
        }
        else {
            echo '<!-- @ --><div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div id="blueimp-gallery-carousel" class="blueimp-gallery blueimp-gallery-carousel">
                            <div class="slides"></div>
                            <h3 class="title"></h3>
                            <a class="prev">‹</a>
                            <a class="next">›</a>
                            <a class="play-pause"></a>
                            <ol class="indicator"></ol>
                        </div>
                    </div>
                </div><!-- @ -->';
        }
    }

    private function registerJs()
    {
        $js = <<<JS
document.getElementById('links').onclick = function (event) {
    event = event || window.event;
    var target = event.target || event.srcElement,
        link = target.src ? target.parentNode : target,
        options = {index: link, event: event},
        links = this.getElementsByTagName('a');
    blueimp.Gallery($this->_images, options);
};
JS;
        $js2 = <<<JS2
blueimp.Gallery($this->_images,
    {
        container: '#blueimp-gallery-carousel',
        carousel: true
    }
);
JS2;
        $script = 'js'.$this->mode;
        $this->getView()->registerJs($$script, \yii\web\View::POS_END);
    }
}