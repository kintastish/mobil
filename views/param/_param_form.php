<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$model = new \app\models\ParamTypes;
?>
<div>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

    <?php ActiveForm::end(); ?>
</div>

//TODO
//	1. форма для новой записи в param_types
//
