<?php 
use yii\helpers\Html;
use yii\helpers\Url;

$detail_url = Html::a('Подробнее', ['page/view', 'id'=>$model->id]);
?>
<div class="row">
	<div class="col-md-9 col-md-offset-1">
		<p class="lead"><?= $model->title ?></p>
		<p><?= $model->description ?></p>
		<p><?= $detail_url ?></p>
	</div>
</div>