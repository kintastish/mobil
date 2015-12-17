<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gallery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['list', 'id'=>$model->category_id], ['class' => 'btn btn-default']) ?>
    </div>
    <?php 
        $titleFieldId = Html::getInputId($model, 'title');
        $aliasFieldId = Html::getInputId($model, 'alias');
        $url = Url::to(['transliterate']);
        $js = <<<JS
$('#$titleFieldId').change( function() {
    var title = $('#$titleFieldId').val();
    $.get( '$url', {'t':title}, function(data, textStatus){
        var d = eval(data);
        $('#$aliasFieldId').val(d.tr);
    } );
});
JS;
        $this->registerJs($js); ?>

    <?php ActiveForm::end(); ?>

</div>
