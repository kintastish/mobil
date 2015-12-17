<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Json;
use app\models\ResourceParam;

class ParamController extends \yii\web\Controller
{
    
    public function actionAdd()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            // $data = Json::decode(Yii::$app->request->post());
            $data = Yii::$app->request->post();
            $model = new ResourceParam;
            $model->table_id = $data['table'];
            $model->item_id = $data['item'];
            $model->type_id = $data['param'];
            $model->value = $data['val'];
            if ($model->save()) {
            // if ($model->validate()) {
                $res = $model->toArray();
                $res['id'] = rand(1, 10);
                $res['param_name'] = $model->type->comment;
                return $res;
            }
            return [];
        }
        throw new \yii\web\NotFoundHttpException;
    }

    public function actionRemove($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (($model = ResourceParam::findOne($id)) !== null) {
            $model->delete();
            return ['id' => $id];
        }
        return [];
    }

    public function actionEdit()
    {
        return $this->render('edit');
    }

    public function actionList()
    {
        return $this->render('list');
    }

}
