<?php

namespace app\controllers;

use Yii;
use app\models\Resources;
use app\models\Categories;
use app\models\Files;
use app\models\FileUploadModel;
use app\components\StoreDirBehavior;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;

/**
 * GalleryController implements the CRUD actions for Resources model.
 */
class GalleryController extends MController
{
    protected $needAuthActions = ['list', 'all', 'new-album', 'manage', 'upload'];

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

    /**********************************************************************
     * Пользовательская часть
     **********************************************************************/
     
    /*
    *  Вывод списка альбомов
    */
    public function actionIndex($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Resources::find()->where(['category_id' => $id]),
            'pagination' => [
                'pageSize' => 20,
            ],        
        ]);


        $model = $this->findGallery($id);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    /*
    * Просмотр альбома
    */
    public function actionView($id)
    {
        $album = $this->findAlbum($id);

        return $this->render('view', [
            'album' => $album,
            'images' => $album->files
        ]);
    }

    
    /**********************************************************************
     * Административная часть
     *********************************************************************
     */

    /* Вывод списка альбомов галереи
    */
    public function actionList($id=0)
    {
        $q = Resources::find()->joinWith('category')
            ->where(['categories.handler' => $this->id]);
        $view = 'list0';
        if ($id > 0) {
            $q->andWhere(['category_id' => $id]);
            $view = 'list';
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $q,
            'pagination' => false
        ]);

        return $this->render($view, [
            'category' => Categories::findOne($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /* Вывод списка всех галерей
    */
    public function actionAll()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Categories::find()
                ->where(['handler' => $this->id]),
            'pagination' => false
        ]);

        return $this->render('all', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
    *   Создание нового альбома
    */
    public function actionNewAlbum($id)
    {
        if ($id == 0) {
            throw new BadRequestHttpException("Неправильный запрос");
        }
        
        $model = new Resources(['scenario' => 'album']);
        $model->category_id = $id;

        $model->attachBehavior('createStoreDir', [
            'class' => StoreDirBehavior::className(),
            'handlerName' => $this->id
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             return $this->redirect(['list', 'id' => $model->id]);
        } else {
            return $this->render('new-album', [
                'model' => $model,
            ]);
        }        
    }

    /*
    *   Управление содержимым альбома
    */
    public function actionManage($id)
    {
        if ($id <= 0) {
            throw new BadRequestHttpException("Неправильный запрос");
        }
        $album = $this->findAlbum($id);

        $q = Files::find()->where([
            'attach_table' => Resources::$tableId,
            'attach_id' => $id
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $q
        ]);

        $uploadModel = new FileUploadModel;
        $uploadModel->id = $id;
        $uploadModel->tableId = Resources::$tableId;
        return $this->render('manage', [
            'dataProvider' => $dataProvider,
            'album' => $album,
            'uploadModel' => $uploadModel,
        ]);        
    }

    /*
    *   Загрузка изображений
    */
    // TODO нужны ли одинаковые переходы в случае успешного и ошибочного завершения загрузки??
    public function actionUpload($id)
    {
        if (Yii::$app->request->isPost) {
            $model = new FileUploadModel;
            $model->id = $id;
            $model->tableId = Resources::$tableId;
            $model->files = UploadedFile::getInstances($model, 'files');
            if ( $model->upload() ) {
                return $this->redirect(['manage', 'id' => $id]);
            }
        }

        return $this->redirect(['manage', 'id' => $id]);
    }

    /**
    * TODO удаление альбома
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    /*  */

    protected function findGallery($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Ресурс не найден');
        }
    }

    protected function findModel($id)
    {
        throw new NotFoundHttpException('findModel - Метод не используется');
    }

    protected function findAlbum($id)
    {
        if (($model = Resources::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Ресурс не найден');
        }
    }

    protected function findImage($id)
    {
        if (($model = Files::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Ресурс не найден');
        }
    }

    /*
    *   Возвращает путь к базовому каталогу галереи
    */
    public static function baseStorePath()
    {
        return [
            'real' => Yii::getAlias('@webroot/images/gallery/'),
            'web'  => Yii::getAlias('@web/images/gallery/'),
        ];
    }
}
