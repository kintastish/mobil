<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use app\widgets\translit\transliterator;

?>

<div class="album-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'category_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'title')->widget(transliterator::className(), [
        'targetAttribute' => 'alias'
    ]); ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['gallery/list', 'id'=>$model->category_id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
