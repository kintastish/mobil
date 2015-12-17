<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\widgets\bootstrap\Collapse;
?>

<div class="dynblock-form">
    <?php echo Html::beginForm(); 
    $labels = $model->attributeLabels();
    ?>
    
    <?php $this->beginBlock('panel1'); ?>
    <div class="row form-group">
        <div class="col-md-4">
            <?= Html::activeLabel( $model, 'formId') ?>
            <?= Html::activeTextInput($model, 'formId', ['class'=>'form-control']) ?>
        </div>
        <div class="col-md-4 col-md-offset-4">
            Идентификатор формы в HTML-коде для использования в CSS или Javascript. Для нормального функционирования поле должно быть заполнено.
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-8">
            <?= Html::activeLabel( $model, 'tplInput') ?>
            <?= Html::activeTextarea($model, 'tplInput', ['class'=>'form-control', 'rows'=>2]) ?>
        </div>
        <div class="col-md-4">
            <div>Переменные для шаблона</div>
            <div><b>{Поле}</b> - поле ввода</div>
            <div><b>{Метка}</b> - метка (заголовок) поля ввода</div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-8">
            <?= Html::activeLabel( $model, 'tplArea') ?>
            <?= Html::activeTextarea($model, 'tplArea', ['class'=>'form-control', 'rows'=>2]) ?>
        </div>
        <div class="col-md-4">
            <div>Переменные для шаблона</div>
            <div><b>{Поле}</b> - поле ввода</div>
            <div><b>{Метка}</b> - метка (заголовок) поля ввода</div>
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-4">
            <?= Html::activeLabel( $model, 'tplMarker') ?>
            <?= Html::activeTextInput($model, 'tplMarker', ['class'=>'form-control']) ?>
        </div>
        <div class="col-md-4 col-md-offset-4">
            Маркер, для обозначения обязательного поля. Выводится после метки поля.
            Есть ее в шаблоне поля нет, то после поля.
        </div>
    </div>
    <div class="row form-group">
        <div class="col-md-8">
            <?= Html::activeLabel( $model, 'note') ?>
            <?= Html::activeTextInput($model, 'note', ['class'=>'form-control']) ?>
        </div>
        <div class="col-md-4">
            Шаблон примечания формы. Переменная <b>{Маркер}</b> используется для наглядного
            отображения пользователю знака, обозначающего обязательное поле.
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('panel2'); ?>
    <div class="row form-group">
        <div class="col-md-5">
            <?= Html::activeInput('email', $model, 'email', ['class' => 'form-control', 'placeholder' => 'E-mail получателя']) ?>
        </div>
        <div class="col-md-5 col-offset-2">
            <?= Html::activeTextInput($model, 'subject', ['class' => 'form-control', 'placeholder' => 'Тема сообщения']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <?= Html::activeCheckbox($model, 'useCaptcha') ?>
        </div>
    </div>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('panel3') ?>
    <div class="row">
        <div class="col-md-4">
            <?= Html::textInput('fname', null, ['class'=>'form-control', 'placeholder'=>'Поле', 'maxlength' => 50]) ?>
        </div>
        <div class="col-md-3">
            <?php $options = ['input' => 'Строка', 'textarea' => 'Текст']; ?>
            <?= Html::dropDownList('ftype', null, $options, ['class'=>'form-control', 'prompt' => 'Выберите вид поля']) ?>
        </div>
        <div class="col-md-4">
            <?= Html::checkbox('frequired') ?><label>Обязательно для заполнения</label>
        </div>
        <a id="add-field-btn" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i></a>
    </div>
    <div id="form-fields">
        <div class="row"><div class="col-md-12">&nbsp;</div></div>
        <?php 
        $template = '<div class="row">'
                    .'<div class="col-md-4">'.Html::activeHiddenInput($model, 'fieldName[]',     ['value' => '{name}']).'{name}</div>'
                    .'<div class="col-md-3">'.Html::activeHiddenInput($model, 'fieldType[]',     ['value' => '{type}']).'{type_str}</div>'
                    .'<div class="col-md-4">'.Html::activeHiddenInput($model, 'fieldRequired[]', ['value' => '{req}']).'{req_str}</div>'
                    .'<div class="col-md-1"><a href="#" class="remove-btn"><i class="glyphicon glyphicon-remove text-danger"></i></a></div>'
                    .'</div>';
        if (count($model->fieldName)) {
            $search = ['{name}', '{type}', '{type_str}', '{req}', '{req_str}'];
            foreach ($model->fieldName as $ind => $value) {
                $replace = [
                    $value,
                    $model->fieldType[$ind],
                    $options[$model->fieldType[$ind]],
                    $model->fieldRequired[$ind],
                    ($model->fieldRequired[$ind] ? 'Обязательное поле' : '')
                ];
                echo str_replace($search, $replace, $template);
            }
        }
        ?>
    </div>
    <?php $this->endBlock(); ?>
    <?php 
    echo Collapse::widget([
        'items' => [
            [
                'label' => 'Настройка шаблона',
                'content' => $this->blocks['panel1'],
            ],
            [
                'label' => 'Параметры сообщения',
                'content' => $this->blocks['panel2'],
            ],
            [
                'label' => 'Поля формы',
                'content' => $this->blocks['panel3'],
                'expanded' => true
            ],
        ]
    ]);
    ?>
    <div class="form-group">
        <?= Html::hiddenInput('test', '{test}') ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?= Html::endForm(); ?>
 
</div>
<?php 
$template = "'$template'";
$jsAddField = <<<jsAddField
var input_name = $('input[name=fname]');
var select_type = $('select[name=ftype]');
var chb_req = $('input:checkbox[name=frequired]');
var ff = $('#form-fields');
var tpl = $template;
$('#add-field-btn').click(function(){
    var name = $(input_name).val();
    var type = $(select_type).val();
    var req = $(chb_req).prop('checked');

    if (name != "" && type != "") {
        var type_str = $('option[value=' + type + ']').text();
        var row = tpl.replace(/{name}/g, name).replace('{type}', type).replace('{type_str}', type_str).replace('{req}', req).replace('{req_str}', (req ? 'Обязательное поле' : ''));
        $(row).appendTo(ff);
        $(input_name).val('');
        $(select_type).val('');
        $(chb_req).prop('checked', false);
    }
});
$('body').on('click', 'a.remove-btn', function(){
    $(this).parent().parent().fadeOut(300, function(){
        $(this).remove();
    });
});
jsAddField;
$this->registerJs($jsAddField, \yii\web\View::POS_END);
?>