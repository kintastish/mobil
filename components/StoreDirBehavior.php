<?php 
namespace app\components;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;
use app\models\Categories;
use app\models\Resources;
//use ImageBehavior;

class StoreDirBehavior extends Behavior
{
    public $base = '@webroot/images/gallery/';

    public $handlerName = '';

    private $_oldName = null;
    private $_newName = null;
    private $_parentDir;
    private $_parentRoute;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'createStoreDir',
            // ActiveRecord::EVENT_BEFORE_UPDATE => 'prepareRename',
            // ActiveRecord::EVENT_AFTER_UPDATE => 'renameStoreDir',
        ];
    }

    public function createStoreDir($event)
    {
        if ($this->getHandler() == $this->handlerName) {
            $path = $this->createHierarchyPath();
            FileHelper::createDirectory( Yii::getAlias($this->base . $path) );
        }
    }



        // TODO а может ну его нафиг это переименование?
    public function prepareRename($event)
    {
        if ( $this->owner->isAttributeChanged('alias') ) {
            $this->_oldName = $this->owner->getOldAttribute('alias');
            $this->_newName = $this->owner->alias;
        }
    }

    public function renameStoreDir($event)
    {
        if ($this->_oldName !== null && $this->_newName !== null) {
            $this->_parentRoute = $this->owner->parent->getRoute();
            $this->_parentDir = Yii::getAlias($this->base.$this->_parentRoute);
            $old = $this->_parentDir.$this->_oldName;
            $new = $this->_parentDir.$this->_newName;
            if (rename($old, $new)) {
                //$this->changeAlbumImagesPath();   //Пока уберем эту функцию
            }
        }
    }

    // определяем обработчик
    private function getHandler()
    {
        $o = $this->owner;
        if ($o instanceof Resources) {
            return $o->category->handler;
        }
        elseif ($o instanceof Categories) {
            return $o->handler;
        }
        else {
            throw new InvalidCallException("Неправильный тип объекта");
        }        
    }

    private function changeAlbumImagesPath()
    {
        $images = $this->owner->resources;
        foreach ($images as $img) {
            $img->attachBehavior('image', [
                'class' => ImageBehavior::className()
            ]);
            $alias = $this->owner->alias;
            $img->path = $this->_parentDir.$alias.'/';
            $img->webroute = Yii::getAlias(str_replace('@webroot', '@web', $this->base)).$this->_parentRoute.$alias.'/';
            $img->save();
        }
    }

    private function createHierarchyPath()
    {
        $o = $this->owner;

        if ($o instanceof Resources) {
            $path = $o->alias;
            $cat_id = $o->category_id;
        }
        elseif ($o instanceof Categories) {
            $path = '';
            $cat_id = $o->id;
        }
        else {
            throw new InvalidCallException("Неправильный тип объекта");
        }

        $parents = Categories::getHierarchy($cat_id);
        foreach ($parents as $p) {
            $path = $p->alias.'/'.$path;
        }
                
        return $path;
    }
}