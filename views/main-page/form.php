<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

?>

<div class="main-page-form">

    <?php $form = ActiveForm::begin(['action'=>['save']]); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?php /*echo $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className(), [
        'clientOptions' => [
            'lang' => 'ru',
            'plugins' => ['fontcolor', /*'imagemanager', 'table']
        ]
    ])*/ 
        echo $form->field($model, 'content', [])->widget(
                \app\modules\redactor\widgets\Redactor::className(), [
                'clientOptions' => [
                    'lang' => 'ru',
                    'plugins' => ['fontcolor', 'imagemanager', 'table', 'linkinternal'],
                    'minHeight' => 400,
                    'imageUpload' => Url::to(['/redactor/upload/image', 'table' => 0, 'id' => 0]),
                    'imageManagerJson' => Url::to(['/redactor/upload/image-json', 'table' => 0, 'id' => 0]),
                    'fileUploadParam' => 'files',
                    'imageUploadParam' => 'files',
                    'linkExplorer' => Url::to(['explore/expand'])
                ],
            ])->label(false);
    ?>
    <?php 
/*        echo $form->field($model, 'content')->widget(CKEditor::className(), [
            'options' => ['rows' => 6],
            'preset' => 'custom',
            'clientOptions' => [
                'height' => 200,
                'toolbar' => [
                    [ 'name' => 'clipboard', 'items' => [ 'Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo' ] ],
                    [ 'name' => 'links', 'items' => [ 'Link', 'Unlink'] ],
                    [ 'name' => 'insert', 'items' => [ 'Glyphicons', 'Image', 'Table', 'HorizontalRule', 'SpecialChar', 'Youtube', 'Slideshow' ] ],
                    [ 'name' => 'tools', 'items' => [ 'Maximize' ] ],
                    [ 'name' => 'document', 'items' => [ 'Source' ] ],
                    '/',
                    [ 'name' => 'basicstyles', 'items' => [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat' ] ],
                    [ 'name' => 'paragraph', 'items' => [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] ],
                    [ 'name' => 'styles', 'items' => [ 'Styles', 'Format' ] ]
                ],
                'extraPlugins' => 'colordialog'
            ]
        ]);*/
    ?>

    <?= $form->field($model, 'description')->textArea() ?>

    <?= $form->field($model, 'keywords')->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['control-panel/main'], ['class' => 'btn btn-default']) ?>
    </div>

</div>
