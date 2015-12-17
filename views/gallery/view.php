<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use app\widgets\blueimp\BlueimpAsset;

$this->title = 'Просмотр альбома '.$album->title;
$this->params['breadcrumbs'][] = ['label' => $album->category->title, 'url' => ['gallery/index', 'id' => $album->category_id]];
$this->params['breadcrumbs'][] = 'Альбом: '.$album->title;
?>
<div class="album-index">

    <h3><?= Html::encode($album->title) ?></h3>
    <div id="links">
<?php 
    foreach ($images as $image) {
        $image->attachBehavior('imageBehavior', [
            'class' => '\app\components\ImageBehavior'
        ]);
        $img = Html::img( $image->thumbnailUrl, ['alt' => $image->title]);
        echo Html::a($img, $image->url, ['title' => $image->title]);
    }
?>
    </div>
</div>
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<!-- <div id="blueimp-gallery-carousel" class="blueimp-gallery blueimp-gallery-carousel">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div> -->
<?php 
    BlueimpAsset::register($this);
    $js = <<<JS
document.getElementById('links').onclick = function (event) {
    event = event || window.event;
    var target = event.target || event.srcElement,
        link = target.src ? target.parentNode : target,
        options = {index: link, event: event},
        links = this.getElementsByTagName('a');
    blueimp.Gallery(links, options);
};
JS;

//     $js = <<<JS2
// blueimp.Gallery(
//     document.getElementById('links').getElementsByTagName('a'),
//     {
//         container: '#blueimp-gallery-carousel',
//         carousel: true
//     }
// );
// JS2;
    $this->registerJs($js, \yii\web\View::POS_END);
?>