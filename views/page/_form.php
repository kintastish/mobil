<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\widgets\translit\transliterator;

?>

<div class="resources-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'offset' => 'col-sm-offset-2',
                'label' => 'col-sm-2',
                'wrapper' => 'col-sm-10',
                'error' => '',
                'hint' => 'col-sm-3',
        ]]
    ]); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'category_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'title')->widget(transliterator::className(), [
        'targetAttribute' => 'alias'
    ]); ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'content', [
            'horizontalCssClasses' => [
                'wrapper' => 'col-sm-10',
                'error' => '',
                'hint' => 'col-sm-3',
            ],
            'template' => '{input}{error}{hint}'
        ])->widget(\app\modules\redactor\widgets\Redactor::className(), [
            'clientOptions' => [
                'lang' => 'ru',
                'plugins' => ['fontcolor', 'imagemanager', 'table']
            ]
        ]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['list', 'id'=>$model->category_id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
