<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Структура сайта';
//$this->params['breadcrumbs'][] = ['label' => 'Главная страница', 'url' => ['#Сделать ссылку на настройку главной страницы сайта']];

$addCategoryBtn = Html::a('Создать раздел', ['create'], ['class' => 'btn btn-primary']);

if ($model !== null) {
    $this->params['breadcrumbs'][] = $model->title;
    $addCategoryBtn = Html::a('Создать раздел', ['create', 'cat_id' => $model->id], ['class' => 'btn btn-primary']);
}
?>
<div class="categories-list">

    <h3><?= Html::encode($this->title) ?></h3>
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>
 
    <p>
        <?= $addCategoryBtn ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'title',
                'content' => function ($model, $key, $index, $column) {
                    return Html::a($model->title, [$model->handler.'/list', 'id'=>$model->id]);
                },
            ],
            'alias',
            [
                'attribute' => 'handler',
                'content' => function ($model, $key, $index, $column){
                    return \app\components\TempData::$contentTypes[$model->handler];
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                 'buttons' => [
                    // 'view' => function($url, $model, $key) {
                    //     return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', ['page/list', 'cat'=>$model->id], ['title'=>'Просмотр содержимого']);
                    // },
                    'update' => function($url, $model, $key) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['cat/update', 'id'=>$model->id], ['title'=>'Редактировать']);
                    },
                    'delete' => function($url, $model, $key) {
                        $r = '#';
                        $title = 'Удаление допустимо только для пустого контейнера';
                        if ($model->isEmpty) {
                            $r = ['cat/delete', 'id'=>$model->id];
                            $title = 'Удалить';
                        }
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', $r, ['title'=>$title]);
                    },
                ],
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>

</div>
