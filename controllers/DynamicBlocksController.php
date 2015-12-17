<?php

namespace app\controllers;

use Yii;
use app\widgets\dynblocks\DynamicBlock;
use app\models\DynamicBlockModel;
use app\helpers\DynBlockHelper;

class DynamicBlocksController extends \yii\web\Controller
{
    public $layout = 'controlpanel';

    public function actionIndex()
    {
        $widgets = DynBlockHelper::widgetList();
        $blocks = DynBlockHelper::blockList();
        return $this->render('index', [
            'blocks' => $blocks,
            'widgets' => $widgets
        ]);
    }

    public function actionCreate($id)
    {
        $model = new DynamicBlockModel();
        $model->widgetId = $id;
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                $model->createBlock();
                return $this->redirect(['config', 'id'=>$model->blockId]);
            }
        }
        return $this->render('create', ['model'=>$model]);        
    }


    public function actionConfig($id)
    {
        $model = DynamicBlockModel::loadBlockConfigModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->saveConfig();
            return $this->redirect(['config', 'id'=>$id]);
        };
        return $this->render($model->widgetId, [
            'model' => $model,
        ]);
    }

    public function actionTest()
    {
        //$config = new ConfigModel(['blockName'=>'linklist2']);
/*        $config->attributes = [
            'blockBeginTemplate' => 'div',
            'itemTemplate' => 'item',
            'blockEndTemplate' => '/div',
            'filterParams' => [
                ['param' => 'category', 'compare' => '=', 'value' => '7'],
                ['param' => 'order', 'value' => 'new'],
            ],
        ];*/
        //var_dump($config::getBlockList());
/*        $id = 'linklist2';
        $config = new ConfigModel(['blockName' => $id]);
        $config->blockBeginTemplate = '1111';
        var_dump($config->attributes);*/
/*        $c = DynamicBlockModel::readConfig('linklist');
        if ($c != null) {
            echo $c['__block']['widgetClass'];
            echo DynBlockHelper::widgetIdByClassName($c['__block']['widgetClass']);
        }*/
        var_dump(preg_match('/@\d+@/', '<div>{Описание}<b>@1@</b></div>'));
        return $this->render('blank');
     
    }



    private function loadBlockWidget($id)
    {
        $class = DynamicBlock::getWidgetClassById($id, true);
        $w = new $class;
        return $w;
    }

    private function loadWidgetModel($id)
    {
        $w = $this->loadBlockWidget($id);
        $modelClass = $w->getWidgetModelClassName();
        return new $modelClass;
    }
}
