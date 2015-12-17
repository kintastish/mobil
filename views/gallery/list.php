<?php

use yii\helpers\Html;
use yii\grid\GridView;

$gridColumns = [
    'id',
    [
        'attribute' => 'title',
        'label' => 'Название',
        'content' => function ($model, $key, $index, $column) {
            return Html::a($model->title, ['manage', 'id'=>$model->id]);
        },
    ],
    'alias',
    [
        'content' => function ($model, $key, $index, $column) {
            return $model->route;
        }
    ]
];
?>
<div class="gallery-list">

    <h3>Альбомы в галерее</h3>
    <p>Название галереи: <b><?= $category->title ?></b></p>
    <p>
        <?php echo Html::a('<i class="glyphicon glyphicon-plus"></i> Новый альбом', ['new-album', 'id'=>$category->id], ['class' => 'btn btn-primary']); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
    ]); ?>

</div>
