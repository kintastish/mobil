<?php 
namespace app\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use yii\base\NotSupportedException;
use app\models\Categories;
use app\models\Resources;


class ImageBehavior extends Behavior
{
    private $_format = '';
  
    //ресурсы изображений
    private $_orig;     //оригинальное изображение
    private $_tn;       //эскиз

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'createThumbnail',
            ActiveRecord::EVENT_AFTER_UPDATE => 'createThumbnail',
//            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteImageFiles',
        ];
    }

    public function getThumbnailUrl()
    {
        $o = $this->owner;
        return $o->base_url.$o->path.'t/'.$o->filename;
    }

    public function getThumbnailPath()
    {
        $o = $this->owner;
        return $o->base_dir.$o->path.'t/'.$o->filename;
    }

    public function createThumbnail($event)
    {
        $this->loadImage();
        if ($this->_orig) {
            list($w, $h) = getimagesize($this->owner->getPath());
            $tnSize = $this->calcThumbnailSize($w, $h);
            $this->_tn = imagecreatetruecolor($tnSize['w'], $tnSize['h']);
            imagecopyresampled( $this->_tn, $this->_orig, 0, 0, 0, 0, $tnSize['w'], $tnSize['h'], $w, $h );
            $this->saveImage();
        }
        //TODO обработка ошибок
    }

    public function deleteImageFiles()
    {
        $tn = $this->getThumbnailPath();
        $im = $this->owner->getPath();
        unlink($tn);
        unlink($im);
    }
    
    private function getImageFormat($fn)
    {
        $f = FileHelper::getMimeType($fn);
        if ($f === null) {
            throw new NotSupportedException("ImageBehavior - Неизвестный тип файла");
        }
        $this->_format = str_replace('image/', '', $f);
    }
    

    private function loadImage()
    {
        $f = $this->owner->getPath();
        $this->getImageFormat($f);
        switch ($this->_format) {
            case 'jpeg':
                $this->_orig = imagecreatefromjpeg($f);
                break;
            case 'png':
                $this->_orig = imagecreatefrompng($f);
                break;
            default:
                throw new NotSupportedException("ImageBehavior - Неподдерживаемый тип файла");
                break;
        }
    }

    private function saveImage()
    {
        $o = $this->owner;
        $dir = $o->base_dir.$o->path.'t/';
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        switch ($this->_format) {
            case 'jpeg':
                imagejpeg($this->_tn, $this->getThumbnailPath());
                break;
            case 'png':
                imagepng($this->_tn, $this->getThumbnailPath());
                break;
            default:
                break;
        }
        
    }
    
    /*
    *   $w и $h - размеры оригинального изображения
    *   Возвращает массив с размерностью превьюшки
    */
    private function calcThumbnailSize($w, $h)
    {
        $maxSize = \app\components\TempData::$thumbDimension;
        $imgRatio = $w/$h;
        $tnRatio = $maxSize['width']/$maxSize['height'];
            //изображение "узкое" - подгоняем по высоте
        if ($imgRatio < $tnRatio) { 
            $r = $maxSize['height'] / $h;
        }
        else {
            $r = $maxSize['width'] / $w;                //изображение "широкое" - подгоняем по ширине
        }
        return [
            'w' => ceil($w * $r),
            'h' => ceil($h * $r)
        ];
    }
}