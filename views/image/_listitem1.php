<?php 
use yii\helpers\Html;
use yii\helpers\Url;

$model->attachBehavior('imageBehavior', [
	'class' => '\app\components\ImageBehavior'
]);
mb_internal_encoding('UTF-8');
?>
<div class="row">
	<div class="col-md-3">
		<img src="<?= $model->thumbnailUrl ?>" class="img-thumbnail">
	</div>
	<div class="col-md-7">
		<div class="row">
			<?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['image/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a('Открыть в оригинальном размере', $model->imageUrl, [
				'class' => 'btn btn-default pull-right',
				'target' => '_blank'
			]) ?>
		</div>
		<div class="row">&nbsp;</div>
		<dl class="dl-horizontal">
			<dt><b>Название:</b></dt>
			<dd><?= $model->title ?></dd>
			<dt><b>Псевдоним:</b></tv>
			<dd><?= $model->alias ?></dd>
			<dt><b>Описание:</b></dt>
			<dd><?= mb_substr($model->description, 0, 100) . (mb_strlen($model->description) > 100 ? '...' : '') ?></dd>
			<dt><b>URL изображения:</b></dt>
			<dd><?= $model->imageUrl ?></dd>
		</dl>
	</div>
</div>