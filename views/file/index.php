<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Resources';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="resources-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Resources', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'category_id',
            'created',
            'alias',
            'title',
            // 'description',
            // 'content:ntext',
            // 'keywords',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
