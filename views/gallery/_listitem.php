<?php 
use yii\helpers\Html;
use yii\helpers\Url;

$detail_url = Html::a('Посмотреть', ['gallery/view', 'id'=>$model->id]);
$max_count = min(4, count($model->files));
$img_list = '';
for ($i=0; $i < $max_count; $i++) { 
	$r = $model->files[$i];
	$r->attachBehavior('imageBehavior', [
        'class' => '\app\components\ImageBehavior'
    ]);
	$img_list .= Html::img($r->thumbnailUrl, ['width'=>60]);
}
?>
<div class="row">
    <div class="col-md-9 col-md-offset-1">
        <p class="lead"><?= $model->title ?></p>
        <p><?= $img_list.' '.$detail_url ?></p>
    </div>
</div>