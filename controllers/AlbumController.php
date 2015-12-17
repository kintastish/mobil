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
use yii\filters\VerbFilter;

/**
 * GalleryController implements the CRUD actions for Resources model.
 */
class AlbumController extends MController
{
    protected $needAuthActions = ['list', 'list-images', 'create', 'update', 'delete'];

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
        $album = $this->findAlbum($id);
        
        $images = Resources::find()->where(['category_id'=>$id])->all();
        
        return $this->render('index', [
            'album' => $album,
            'images' => $images,
        ]);
    }

    /* Вывод списка всех альбомов галереи $id
    */
    // public function actionList($id)
    // {
    //     $model = $this->findAlbum($id);

    //     $q = File::find()->where([
    //         'attach_table' => Resources::$tableId,
    //         'attach_id' => $id
    //     ]);

    //     $dataProvider = new ActiveDataProvider([
    //         'query' => $q
    //     ]);
    //     return $this->render('list', [
    //         'dataProvider' => $dataProvider,
    //         'album' => $model,
    //     ]);
    // }


/*        if ($id == 0) {
            throw new \yii\web\BadRequestHttpException("Неправильный запрос");
        }
        $q = Resources::find()
            ->where([
                'category_id' => $id
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $q
        ]);

        $uploadModel = new FileUploadModel;
        $uploadModel->id = $id;
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'album' => Categories::findOne($id),
            'uploadModel' => $uploadModel,
        ]);*/

    /**
    * Вывод изображения $id
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findImage($id),
        ]);
    }

    // public function actionCreate($id)
    // {
    //     if ($id == 0) {
    //         throw new \yii\web\BadRequestHttpException("Неправильный запрос");
    //     }
        
    //     $model = new Resources(['scenario' => 'album']);
    //     $model->category_id = $id;

    //     $model->attachBehavior('createStoreDir', [
    //         'class' => StoreDirBehavior::className(),
    //         'handlerName' => 'gallery'
    //     ]);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //          return $this->redirect(['list', 'id' => $model->id]);
    //     } else {
    //         return $this->render('create', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    /**
    * Не разрешаем изменять свойства
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findAlbum($id);
        
    //     if ( $model->isEmpty ) {
    //         return $this->redirect(['album/list', 'id'=>$model->parent_id]);
    //     }
        
    //     $model->attachBehavior('changeStoreDir', [
        
    //         'class' => StoreDirBehavior::className(),
    //         'handlerName' => $this->id
    //     ]);
        
    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['image/list', 'id' => $model->id]);
    //     } else {
    //         return $this->render('update', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    /**
    * TODO
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    protected function findAlbum($id)
    {
        if (($model = Resources::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findImage($id)
    {
        if (($model = Resources::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function baseStorePath()
    {
        return [
            'real' => Yii::getAlias('@webroot/images/gallery/'),
            'web'  => Yii::getAlias('@web/images/gallery/'),
        ];
    }
}
