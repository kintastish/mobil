<?php

namespace app\controllers;

use Yii;
use app\models\Resources;
use app\models\Categories;
use app\models\Files;
use app\models\FileUploadModel;
use app\components\StoreDirBehavior;
use app\components\ImageBehavior;

use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;


class ImageController extends MController
{
    protected $needAuthActions = ['edit', 'delete', 'mark', 'ajax-upload'];


    public function actionEdit($id)
    {
        $model = $this->findImage($id);
        $model->attachBehavior('ImageBehavior', [
            'class' => ImageBehavior::className()
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           return $this->redirect($this->getReturnRoute($model));
        } else {
            return $this->render('edit', [
                'model' => $model,
                'returnRoute' => $this->getReturnRoute($model)
            ]);
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findImage($id);
        $model->attachBehavior('ImageBehavior', [
            'class' => ImageBehavior::className()
        ]);
        $model->delete();
        return $this->redirect($this->getReturnRoute($model));
    }

    public function actionMark($id, $mark=0)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Files::findOne($id);
        if ($mark == 1) {
            Files::updateAll(['mark' => 0], [
                'attach_table' => $model->attach_table,
                'attach_id' => $model->attach_id,
                'mark' => 1
            ]);
        }
        $model->mark = $mark;
        $model->save(false);
        return ['id' => $id];
    }

    public function actionAjaxDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findImage($id);
        $model->attachBehavior('ImageBehavior', [
            'class' => ImageBehavior::className()
        ]);
        $model->delete();
        return ['id' => $model->id];
    }

    public function actionAjaxUpload($table, $id)
    {
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new FileUploadModel;
            $model->id = $id;
            $model->tableId = $table;
            $model->files[] = UploadedFile::getInstanceByName('files');
            $zz = rand(1, 10);
            if ( $model->upload() ) {
                return $this->getUploadedFilesInfo($model->uploadedIds);
            }
            else {
                return ['error' => $model->files[0]->baseName];
                $errors = [];
                foreach ($model->errors as $attr => $e) {
                    $errors = array_merge($e, $errors);
                }
                Yii::trace('Ошибка загрузки файла '. \yii\helpers\VarDumper::dumpAsString($model->errors));
                return ['error' => implode(', ', $errors)];
            }
        }
    }

    private function getUploadedFilesInfo($ids)
    {
        $res = [];
        foreach ($ids as $id) {
            $f = Files::findOne($id);
            $f->attachBehavior('ImageBehavior', ['class'=>ImageBehavior::className()]);
            $res[] = [
                'id' => $f->id,
                'img' => $f->url,
                'thumb' => $f->thumbnailUrl
            ];
        }
        return $res;
    }

    protected function getReturnRoute($model)
    {
        return ['gallery/manage', 'id' => $model->attach_id];
    }


    protected function findImage($id)
    {
        if (($model = Files::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Ресурс не найден.');
        }
    }

}
