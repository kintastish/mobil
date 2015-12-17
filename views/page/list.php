<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$showCreatePageBtn = false;
$this->title = 'Веб-страницы';
$info = '';
$gridColumns = [
    'id',
    'categoryTitle' => [
        'label' => 'Название раздела',
        'content' => function($model, $key, $index, $column) {
            return Html::a( $model->category->title, ['page/list', 'id'=>$model->category_id] );
        }
    ],
    'title',
    'alias',

    ['class' => 'yii\grid\ActionColumn'],
];

if ($category !== null) {
    $showCreatePageBtn = true;
    $info = '<p class ="text-info">Текущий раздел: '.$category->title.'</p>';
    unset( $gridColumns['categoryTitle'] );
}

?>
<div class="page-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php if ($category !== null) { ?>
        <p class ="text-warning">Текущий раздел: <b><?= $category->title ?></b></p>
    <?php } ?>
    <p>
        <?php 
        if ($showCreatePageBtn) {
            echo Html::a('<i class="glyphicon glyphicon-plus"></i> Создать страницу в разделе', ['create', 'id'=>$category_id], ['class' => 'btn btn-primary']);
        }
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumns,
    ]); ?>

</div>
