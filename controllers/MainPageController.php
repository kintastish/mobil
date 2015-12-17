<?php

namespace app\controllers;

use Yii;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\TemplateModel;

class MainPageController extends MController
{
    protected $needAuthActions = ['edit', 'save'];
    public static $handlerType = MController::HANDLE_RES;
    
    public function actionEdit()
    {
        $model = new TemplateModel(['view' => '@app/views/site/index']);

        return $this->render('form', [
            'model' => $model
        ]);
    }

    public function actionSave()
    {
        $model = new TemplateModel(['view' => '@app/views/site/index']);

        $model->load(Yii::$app->request->post());
        return $this->render('test', ['model' => $model]);
    }

}
