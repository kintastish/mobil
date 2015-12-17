<?php 
use yii\helpers\Html;
use yii\helpers\Url;

$model->attachBehavior('imageBehavior', [
	'class' => '\app\components\ImageBehavior'
]);
?>
<p><?= Html::a(
	Html::img(
    	$model->thumbnailUrl, [
    		'class' => 'img-thumbnail',
    		'alt' => $model->title
    ]),
	[
		'image/view',
		'id' => $model->id
	])
?></p>