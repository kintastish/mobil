<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = $model->title;

//$this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['page/index', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = $model->title;

?>
<div class="gallery-index">

    <h3><?= Html::encode($model->title) ?></h3>
    <h4>Список альбомов галереи</h4>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_listitem',
        'emptyText' => 'Галерея пока пуста',
        'summary' => ''
    ]); ?>

</div>