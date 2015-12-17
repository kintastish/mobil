<?php

namespace app\modules\redactor\actions;

use Yii;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use app\models\Files;
use app\components\ImageBehavior;

class ImageManagerJsonAction extends \yii\base\Action
{
    public function init()
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException(403, 'This action allow only ajaxRequest');
        }
    }

    public function run1($table, $id)
    {
        $filesPath = FileHelper::findFiles(Yii::$app->controller->module->getSaveDir(), [
            'recursive' => true,
            'only' => $onlyExtensions
        ]);
        if (is_array($filesPath) && count($filesPath)) {
            $result = [];
            foreach ($filesPath as $filePath) {
                $url = Yii::$app->controller->module->getUrl(pathinfo($filePath, PATHINFO_BASENAME));
                $result[] = ['thumb' => $url, 'image' => $url, 'title' => pathinfo($filePath, PATHINFO_FILENAME)];
            }
            return $result;
        }
    }
    
    //TODO Позже сделать нормальный обозреватель файлов
    //пока сделаем выбор из того, что загружено - используем таблицу files
    public function run($table, $id)
    {
/*        $onlyExtensions = array_map( function ($ext) { return '*.'.$ext; },
            Yii::$app->controller->module->imageAllowExtensions
        );
        
        $filesPath = FileHelper::findFiles(Yii::$app->controller->module->getSaveDir(), [
            'recursive' => true,
            'only' => $onlyExtensions
        ]);*/

        $files = Files::getAttachedFiles($table, $id);
        $res = [];
        foreach ($files as $file) {
            $file->attachBehavior('image', ['class' => ImageBehavior::className()]);
            $res[] = [
                'thumb' => $file->thumbnailUrl,
                'image' => $file->url,
                'title' => $file->title
            ];
        }
        return $res;
    }
}
