<?php

use yii\helpers\Html;

?>
<div class="image-update">

    <h3 class="text-center">Редактирование изображения</h3>

    <?= $this->render('_form', [
        'model' => $model,
        'returnRoute' => $returnRoute
    ]) ?>

</div>
