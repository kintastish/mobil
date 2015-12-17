<h4>Карусель</h4>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use app\widgets\bootstrap\Collapse;
?>

<div class="dynblock-carousel">
    <?php $form = ActiveForm::begin(); ?>
    <?php $this->beginBlock('panel1'); ?>
    <?php echo $form->field($model, 'album')->dropDownList($model->getAlbumList()); ?>
    <?php echo $form->field($model, 'interval')->textInput(); ?>
    <?php echo $form->field($model, 'controls')->checkbox(); ?>
    <?php echo $form->field($model, 'showHeader')->checkbox()->hint('Если этот флажок сброшен, шаблон заголовка использоваться не будет.'); ?>
    <?php echo $form->field($model, 'headerTemplate')->textInput()->hint('<b>{Название}</b> - название изображения в альбоме'); ?>
    <?php $this->endBlock(); ?>
    <?php 
    echo Collapse::widget([
        'items' => [
            [
                'label' => 'Настройка слайд-шоу',
                'content' => $this->blocks['panel1'],
                'expanded' => true
            ],
        ]
    ]);
    ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
 
</div>