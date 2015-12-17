<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="dynblock-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'widgetId')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'blockId')->textInput(['maxlength' => true])->label('Идентификатор') ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true])->label('Комментарий') ?>

    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
