<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\categories */

$this->title = 'Новый раздел';
?>
<div class="categories-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
