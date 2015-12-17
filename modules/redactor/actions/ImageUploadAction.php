<?php

namespace app\modules\redactor\actions;

use Yii;
use app\models\FileUploadModel;
use yii\web\UploadedFile;

class ImageUploadAction extends \yii\base\Action
{
    public function run($table, $id)
    {
        if (Yii::$app->request->isPost) {
            $model = new FileUploadModel;
            $model->id = $id;
            $model->tableId = $table;
            $model->files[] = UploadedFile::getInstanceByName('files');
            if ( $model->upload() ) {
                return $model->getLink();
            }
            else {
                return ['error' => 'Не удалось загрузить файл'];
            }
        }
    }
}