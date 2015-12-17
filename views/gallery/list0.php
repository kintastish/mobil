<?php

use yii\helpers\Html;
use yii\grid\GridView;

$gridColumns = [
    'id',
    'categoryTitle' => [
        'label' => 'Название галереи',
        'content' => function($model, $key, $index, $column) {
            return Html::a( $model->category->title, ['gallery/list', 'id'=>$model->category_id] );
        }
    ],
    [
        'attribute' => 'title',
        'label' => 'Название',
        'content' => function ($model, $key, $index, $column) {
            return Html::a($model->title, ['manage', 'id'=>$model->id]);
        },
    ],
    'alias',
    [
        'label' => 'Адрес',
        'content' => function ($model, $key, $index, $column) {
            return '/'.$model->route;
        }
    ]
];
?>
<div class="gallery-list">

    <h3>Список галерей сайта</h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
    ]); ?>

</div>
