<?php

namespace app\controllers;

use Yii;
use app\models\Resources;
use app\models\Categories;
use app\models\ParamTypes;
use app\models\ResourceParam;
use app\components\ParamReplacementBehavior;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


/**
 * PageController implements the CRUD actions for Resources model.
 */
class PageController extends MController
{
    protected $needAuthActions = ['list', 'create', 'update', 'delete', 'test'];
    public static $handlerType = MController::HANDLE_RES;
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
    * TODO
     */
    public function actionIndex($id)
    {
        $category = Categories::findOne($id);

        $dataProvider = new ActiveDataProvider([
            'query' => Resources::find()->where(['category_id' => $id]),
            'pagination' => [
                'pageSize' => 20,
            ],            
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $category,
        ]);
    }

    /**
     */
    public function actionView($id=0)
    {
        $model = $this->findModel($id);
        $model->attachBehavior('paramReplacement', [
            'class' => ParamReplacementBehavior::className(),
            'field' => 'content'
        ]);
        $model->replaceParamIdByValue();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
    ** Вывод списка страниц. Если задан $id категории - списка страниц категории
    */
    public function actionList($id = 0)
    {
        $q = Resources::find();
        if ($id > 0) {
            $q->where(['category_id' => $id]);
        }
        else {
            $q->joinWith('category')
                ->where('categories.handler=:hdl AND categories.alias<>"0"', ['hdl' => $this->id]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $q,
            'pagination' => false
        ]);

        return $this->render('list', [
            'category' => Categories::findOne($id),
            'dataProvider' => $dataProvider,
            'category_id' => $id,
        ]);
    }


    /**
    *   Создание новой страницы в категории $id.
     */
    public function actionCreate($id)
    {
        $model = new Resources(['scenario' => 'page_create']);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            $model->category_id = $id;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->attachBehavior('paramReplacement', [
            'class' => ParamReplacementBehavior::className(),
            'field' => 'content'
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([ $model->isMainPage ? 'control-panel/main':'list', 'id' => $model->category_id]);
        } else {
            $model->replaceParamIdByName();
            return $this->render('update', [
                'model' => $model,
                'files' => $model->files,
            ]);
        }
    }

    /** TODO доделать
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /*
    * Поиск модели страницы. $id=0 - поиск модели главной страницы
     */
    protected function findModel($id)
    {
        if ($id != 0) {
            if (($model = Resources::findOne($id)) !== null) {
                return $model;
            }
        } 
        else {
            if (($model = Categories::findByAlias('0', 0)->resources[0]) !== null) {
                return $model;
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*
    *   Возвращает путь к базовому каталогу страниц
    */
    public static function baseStorePath()
    {
        return [
            'real' => Yii::getAlias('@webroot/content/'),
            'web'  => Yii::getAlias('@web/content/'),
        ];
    }

    public function actionTest()
    {
        echo \yii\helpers\Html::a('test', ['page/view', 'id' => 14]);
    }
}
