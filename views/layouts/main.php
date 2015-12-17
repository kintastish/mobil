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
    <?= $this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['description']]); ?>
    <?= $this->registerMetaTag(['name' => 'description', 'content' => $this->params['keywords']]); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <div class="header">
            <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <span class="logo"><a href="/"><img src="/css/logo.jpg"><span class="brand">Мобиль</span></a></span>
                </div>
                <div class="col-md-3">
                    <div>Производство</div>
                    <ul>
                        <li>автокомпонентов</li>
                        <li>штампов и пресс-форм</li>
                        <li>нестандартного оборудования</li>
                        <li>мини-тракторов</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <p><strong><span class="text-danger">Тел./факс</span> <span class="text-primary"> (8464) 34-22-04, 34-57-79</span></strong></p>
                    <p><strong><span class="text-danger">E-mail:</span> <span class="text-primary"><a href="mailto:mobil.syzran@yandex.ru">mobil.syzran@yandex.ru</a></span></strong></p>
                </div>
                <div class="col-md-3">
                    <img src="/css/16949.jpg">
                </div>
            </div>
            </div>
        </div>
        <nav id="w0" class="navbar navbar-default navbar-static-top" role="navigation">
            <div class="container">
                <div id="w0-collapse" class="collapse navbar-collapse">
                    {*mainmenu*}
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                <?php 
                echo Breadcrumbs::widget([
                    //'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
                    'links' => $this->params['breadcrumbs']
                ]); ?>
                    <?= $content ?>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-right">&copy; <col>ООО "Мобиль"</col> <?= date('Y') ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
{*yametrica*}
</body>
</html>
<?php $this->endPage() ?>
