<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $gallery->title.' > Список альбомов';

$gridColumns = [
    'id',
    [
        'attribute' => 'title',
        'content' => function ($model, $key, $index, $column) {
            return Html::a($model->title, ['image/list', 'id'=>$model->id]);
        },
    ],
    'alias',
];
?>

<h3>Альбомы в галерее</h3>
<p class="text-info">Галерея: <?php echo $gallery->title ?></p>
<div class="albums-list">
<p>
<?php echo Html::a('<i class="glyphicon glyphicon-plus"></i> Новый альбом', ['create', 'id'=>$gallery->id], ['class' => 'btn btn-primary']); ?>
</p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
    ]); ?>

</div>
