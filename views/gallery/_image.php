<?php 
use yii\helpers\Html;
use yii\helpers\Url;

mb_internal_encoding('UTF-8');
$model->attachBehavior('imageFile', [
	'class' => \app\components\ImageBehavior::className()
]);
?>
<div class="row">
	<div class="col-md-3">
		<img src="<?= $model->thumbnailUrl ?>" class="img-thumbnail">
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-1">
			<p>
			<?= Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['image/edit', 'id' => $model->id], [
				'class' => 'btn btn-primary',
				'title' => 'Редактировать'
			]) ?>
			</p>
			<p>
			<?= Html::a('<i class="glyphicon glyphicon-search"></i>', $model->url, [
				'class' => 'btn btn-info',
				'target' => '_blank',
				'title' => 'Просмотреть в полном размере'
			]) ?>
			</p>
			<p>
			<?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['image/delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'title' => 'Удалить'
			]) ?>
			</p>
			</div>
			<div class="col-md-11">
				<table class="table">
					<tr>
						<td><b>Название:</b></td><td><?= $model->title ?></td>
					</tr>
					<tr>
						<td><b>Псевдоним:</b></td><td><?= $model->alias ?></td>
					</tr>
					<tr>
						<td><b>Описание:</b></td><td><?= mb_substr($model->description, 0, 100) . (mb_strlen($model->description) > 100 ? '...' : '') ?></td>
					</tr>
					<tr>
						<td><b>URL изображения:</b></td><td><a href="<?= $model->url ?>"><?= $model->url ?></a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>