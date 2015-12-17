<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class MController extends Controller
{
    const HANDLE_RES = 1;
    const HANDLE_CAT = 2;

    public static $handlerType = self::HANDLE_CAT;
    
    protected $needAuthActions = [];

    protected $defaultViewItemAction = 'view';
    protected $defaultViewListAction = 'index';

    //TODO init()
    public function init()
    {
        //$this->handleType = self::HANDLE_CAT;

        // $this->attachBehavior( 'access', [
        //     'class' => AccessControl::className(),
        //     'only' => $this->needAuthActions,
        //     'rules' => [
        //         // allow authenticated users
        //         [
        //             'allow' => true,
        //             'roles' => ['@'],
        //         ],
        //         // everything else is denied by default
        //     ],                
        // ]);
    }

    public function beforeAction($action)
    {
        if (in_array($action->id, $this->needAuthActions)) {

            $this->layout = 'controlpanel';
        }
        else {
            $this->getView()->attachBehavior('renderDynBlocks', [
                'class' => \app\components\DynamicBlockBehavior::className()
            ]);
        }
        return parent::beforeAction($action);
    }

    public function getConfig()
    {
        
    }

    public static function baseStorePath()
    {
        throw new \yii\base\InvalidConfigException('В контроллере должен быть переопределен метод baseStorePath()');
    }
}
