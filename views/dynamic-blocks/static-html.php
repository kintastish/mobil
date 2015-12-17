<h4>Меню</h4>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="dynblock-static-html">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'Html')->textArea(['rows' => 10]) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>