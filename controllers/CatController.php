<?php

namespace app\controllers;

use Yii;
use app\models\Categories;
use app\components\StoreDirBehavior;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CatController implements the CRUD actions for Categories model.
 */
class CatController extends MController
{
    protected $needAuthActions = ['list', 'create', 'update', 'delete'];

    public function actions()
    {
        return [
            'transliterate' => [
                'class' => 'app\components\TransliterateAction',
            ]
        ];
    }
    /**
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     */
    public function actionView($id)
    {
        // $cat = $this->findModel($id);
        // if ( (count($cat->subcategories) == 1) && ($cat->show_single)) {//считаем только подразделы, т.к. обращения к ресурсам из этого контроллера не производится
        //     //TODO изменить действие в зависимости от типа ресурса на который идет переадресация
        //     return $this->redirect([$cat->handler.'/index', 'id'=>$cat->id]);

        // }
        return $this->render('view', [
            'model' => $cat,
        ]);
    }

    public function actionList($id = 0)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Categories::find()
                ->where(['parent_id' => $id])
                ->andWhere(['<>', 'alias', '0']) ,
        ]);
        $model = Categories::findOne($id);
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /** Создание нового раздела
     */
    public function actionCreate($cat_id = 0)
    {
        $model = new Categories();

        $model->attachBehavior('createStoreDir', [
            'class' => StoreDirBehavior::className(),
            'handlerName' => 'gallery'
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([$model->handler.'/list',
                'id' => $model->id
            ]);
        } else {
            $model->parent_id = $cat_id;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->isEmpty) {
            $model->delete();
        }
        return $this->redirect(['list', 'id'=>$model->parent_id]);
    }

    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
