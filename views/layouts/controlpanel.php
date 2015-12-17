<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
    <div class="container">
        <div class="row">
            <div class="col-md-2 sidebar">
                <?php 
                echo Nav::widget([
                    'items' => [
                        ['label' => 'Главная страница', 'url' => ['page/update', 'id'=>0]],
                        ['label' => 'Структура сайта', 'url' => ['cat/list']],
                        ['label' => 'Страницы', 'url' => ['page/list']],
                        ['label' => 'Альбомы', 'url' => ['gallery/list']],
                        ['label' => 'Блоки', 'url' => ['dynamic-blocks/index']],
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-10">
                <?= $content ?>
            </div>
        </div>
    </div>
    </div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
