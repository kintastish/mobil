<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['page/index', 'id' => $model->category_id]];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="page-index">

    <h3><?= Html::encode($model->title) ?></h3>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_listitem',
        'emptyText' => 'Раздел пока не заполнен',
        'summary' => ''
    ]); ?>

</div>
