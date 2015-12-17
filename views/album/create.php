<?php

use yii\helpers\Html;


$this->title = 'Новый альбом';
?>
<div class="album-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
