<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\widgets\translit\transliterator;
use app\widgets\bootstrap\Collapse;


$this->title = 'Редактирование: ' . ' ' . $model->title;
$isMainPage = $model->isMainPage;
?>
<div class="resources-update">

    <h3>Редактирование страницы</h3>
    <p>Раздел: <b><?= $model->category->title ?></b></p>
    <p>Страница: <b><?= $model->title ?></b></p>

	<div class="resources-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
	    <?php 
	    //collapse panels
	    $panel1 = $form->field($model, 'title')->widget(transliterator::className(), [
		        'targetAttribute' => 'alias'
		    ]);
	    if (!$isMainPage) {
	    	$panel1.= "\n".$form->field($model, 'alias')->textInput(['maxlength' => true]);
	    }
	    $panel1.= "\n".$form->field($model, 'description')->textarea(['maxlength' => true, 'rows' => 8]);

	    $panel2 = $form->field($model, 'content', [])->widget(
	    	\app\modules\redactor\widgets\Redactor::className(), [
            'clientOptions' => [
                'lang' => 'ru',
                'plugins' => ['fontcolor', 'imagemanager', 'table', 'linkinternal'],
	            'minHeight' => 400,
	            'imageUpload' => Url::to(['/redactor/upload/image', 'table' => $model::$tableId, 'id' => $model->id]),
	            'imageManagerJson' => Url::to(['/redactor/upload/image-json', 'table' => $model::$tableId, 'id' => $model->id]),
	            'fileUploadParam' => 'files',
	            'imageUploadParam' => 'files',
	            'linkExplorer' => Url::to(['explore/expand'])
	        ],
        ])->label(false);
	    $panel2.= "\n"; //.$form->field($model, 'keywords')->textInput(['maxlength' => true]);

	    $panel3 = $this->render('_images', ['model' => $model, 'files' => $files]);

	    $panel4 = $this->render('_params', ['target' => $model]);
		echo Collapse::widget([
		    'items' => [
		        [
		            'label' => 'Сведения',
		            'content' => $panel1,
		        ],
		        [
		            'label' => 'Текст страницы',
		            'content' => $panel2,
		            'expanded' => true
		        ],
		        [
		            'label' => 'Параметры',
		            'content' => $panel4,
		        ],
		        [
		            'label' => 'Изображения',
		            'content' => $panel3,
		        ],
		    ]
		]);
	    ?>
	    <div class="form-group">
	        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
	        <?= Html::a('Отмена', [$isMainPage ? 'control-panel/main' :'list', 'id'=>$model->category_id], ['class' => 'btn btn-default']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>
</div>