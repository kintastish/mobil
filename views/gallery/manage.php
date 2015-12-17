<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $album->title.' > Изображения';

?>

<h3>Управление изображениями</h3>
<p class="text-info">Название галереи: <?php echo Html::a($album->category->title, ['gallery/list', 'id'=>$album->category_id]); ?></p>
<p class="text-info">Альбом: <?php echo $album->title ?></p>
<p>
    <?= $this->render('_uploadform', [
        'model' => $uploadModel,
    ]) ?>
</p>
<div class="gallery-manage">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_image',
        'separator' => '<p></p>',
    ]); ?>
</div>
