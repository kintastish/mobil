<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;
?>

<?php $form = ActiveForm::begin([
	'options' => ['enctype' => 'multipart/form-data'],
	'action' => Url::to(['upload', 'id' => $model->id]),
]) ?>

    <?= $form->field($model, 'files[]')->widget(FileInput::classname(), [
    	'options' => [
    		'accept' => 'image/*',
    		'multiple' => true,
    	],
	    'pluginOptions' => [
	        'maxFileCount' => 5,
	        'browseClass' => 'btn btn-success',
	        'uploadClass' => 'btn btn-info',
	        'removeClass' => 'btn btn-danger',
	        'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> ',
	    ],
	])->label(false) ?>

<?php ActiveForm::end() ?>