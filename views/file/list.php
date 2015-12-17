<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $category->title.' > Файлы';

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

<h3>Файлы</h3>
<p class="text-info">Раздел: <?php echo $category->title ?></p>
<p>
    <?= $this->render('_uploadform', [
        'model' => $uploadModel,
    ]) ?>
</p>
<div class="file-list">
     ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_listitem'
    ]);
</div>
