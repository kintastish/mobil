<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\categories */

$this->title = 'Редактирование раздела';
?>
<div class="categories-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
