<?php

namespace app\controllers;

class ControlPanelController extends \yii\web\Controller
{
    public $defaultAction = 'main';
    public $layout = 'controlpanel';

    
    public function actionMain()
    {
        return $this->render('main');
    }

    public function actionSettings()
    {
    	return $this->render('settings');
    }

    public function actionSaveSettings()
    {
    }
}
