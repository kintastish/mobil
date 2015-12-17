<?php

namespace app\controllers;

use Yii;
use app\models\Resources;
use app\models\Categories;
use app\models\Files;
use app\components\StoreDirBehavior;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ExploreController extends Controller
{
    public $defaultAction = 'init';

    private $_items = [];

    public function actionInit()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->getChilds(0);
        return $this->_items;
    }


    public function actionExpand($node = 0)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $this->getChilds($node);
        return $this->_items;
    }


    private function getChilds($item_id)
    {
        $node = true;
        $childs = Categories::findAll(['parent_id' => $item_id]);
        Yii::trace('Categories count: '.count($childs));
        if ( !count($childs) ) {
            $node = false;
            $childs = Resources::findAll(['category_id' => $item_id]);
            Yii::trace('Resources count: '.count($childs));
        }
        if ( !count($childs) ) {
            $_items = [];
            return;
        }
        foreach ($childs as $item) {
            $this->_items[] = [
                'id' => $item->id,
                'title' => $item->title,
                'url' => '/'.$item->route,
                'node' => $node
            ];
        }
    }
}
