<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use app\widgets\translit\transliterator;
?>

<div class="categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'parent_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'title')->widget(transliterator::className(), [
        'targetAttribute' => 'alias'
    ]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?php
    if ($model->isEmpty || $model->isNewRecord) {
        echo $form->field($model, 'handler')->dropDownList(
        	app\components\TempData::$contentTypes);
    }
    else {
        echo $form->field($model, 'handler')->hiddenInput()->label(false);
    }?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['list', 'id' => $model->parent_id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
 

</div>
