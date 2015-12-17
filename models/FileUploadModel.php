<?php 
namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use app\components\ImageBehavior;

class FileUploadModel extends Model
{
    public $id;
    public $files;
    public $tableId;

    private $_mime;
    private $_fn;
    private $_url;
    private $_uploadedIds = [];

    public function rules()
    {
        return [
            [['id', 'tableId'], 'required'],
            [['id', 'tableId'], 'integer'],
            [['files'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 5],
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) { 
            $path = $this->getStoreInfo();
            $store_to = $path['real'].$path['route'];
            if (!file_exists($store_to)) {
                FileHelper::createDirectory($store_to);
            }
            foreach ($this->files as $f) {
                $image = $this->isImageFile($f);
                $new_fn = $this->newFileName($f->baseName, $f->extension, $image);

                if ( $f->saveAs($store_to . $new_fn) ) {
                    $model = new Files;
                    if ($image) {
                        $model->attachBehavior('imageFileBehavior', [
                            'class' => ImageBehavior::className()
                        ]);
                    }
                    $model->filename = $new_fn;
                    $model->base_dir = $path['real'];
                    $model->base_url = $path['web'];
                    $model->path = $path['route'];
                    $model->title = $f->baseName;
                    $model->alias = str_replace('.'.$f->extension, '', $new_fn);
                    $model->attach_table = $this->tableId;
                    $model->attach_id = $this->id;
                    if ($model->save()) {
                        $this->_uploadedIds[] = $model->id;
                    };
                    $this->_fn = $new_fn;
                    $this->_url = $path['web'].$path['route'].$new_fn;
                }
                //TODO обработка ошибки сохранения файла
            }

            return true;
        }
        return false;
    }

    public function getLink()
    {
        return [
            'filelink' => $this->_url,
            'filename' => $this->_fn
        ];
    }

    public function getUploadedIds()
    {
        return $this->_uploadedIds;
    }

    private function newFileName($name, $ext, $image)
    {
        if ($image) {
            return str_replace('.', '', microtime(1)).'.'.$ext;
        }
        return $name.$ext;
    }

    private function getStoreInfo()
    {
        $path = [];

        switch ($this->tableId) {
            case Resources::$tableId:
                $model = Resources::findOne($this->id);
                $handler = $model->category->handler;
                $route = $model->route;
                break;
            case Categories::$tableId:
                $model = Categories::findOne($this->id);
                $handler = $model->handler;
                $route = $model->route;
                break;
            default:
                throw new InvalidConfigException('FileUploadModel - неизвестный ID таблицы');
                break;
        }

        $contrClass = Yii::$app->controllerNamespace.'\\'.ucfirst($handler).'Controller';
        $path = $contrClass::baseStorePath();
        $path['route'] = $route;
        return $path;
    }

    private function isImageFile($f)
    {
        $mime = \yii\helpers\FileHelper::getMimeType($f->tempName);
        return (array_search($mime, ['image/jpeg', 'image/png']) !== false);
    }
}