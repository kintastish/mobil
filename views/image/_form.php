<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ImageBehavior;

$model->attachBehavior('ImageBehavior', [
    'class' => ImageBehavior::className()
]);
?>

<p class="text-center">
<?= Html::img($model->thumbnailUrl, [
	//'width' => 400,
    //'class' => 'img-rounded'
]) ?>
</p>
<div class="image-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', $returnRoute, ['class' => 'btn btn-default']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-trash"></i>', ['image/delete', 'id'=>$model->id], [
            'class' => 'btn btn-danger pull-right',
            'title' => 'Удалить',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
