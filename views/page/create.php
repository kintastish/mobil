<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\widgets\translit\transliterator;


$this->title = $model->category->title.' > Новая веб-страница';
?>
<div class="resources-create">

    <h3>Раздел: <b><?= $model->category->title ?></b></h3>
    <h4>Новая веб-страница</h4>

	<div class="resources-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

	    <?= $form->field($model, 'category_id')->hiddenInput()->label(false) ?>

	    <?= $form->field($model, 'title')->widget(transliterator::className(), [
	        'targetAttribute' => 'alias'
	    ]); ?>

	    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 8]) ?>

	    <div class="form-group">
	        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
	        <?= Html::a('Отмена', ['list', 'id'=>$model->category_id], ['class' => 'btn btn-default']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
