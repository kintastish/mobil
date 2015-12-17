<?php

use yii\helpers\Html;


$this->params['description'] = $model->description;
$this->params['keywords'] = $model->keywords;

if ($model->category->alias != '0') {
	$this->params['breadcrumbs'][] = ['label' => $model->category->title, 'url' => ['page/index', 'id' => $model->category_id]];
	$this->params['breadcrumbs'][] = $model->title;
}
$this->title = $model->title;
?>

<div>

    <h3><?= Html::encode($model->title) ?></h3>

    <div class="content">
        <?= $model->content ?>
    </div>
</div>