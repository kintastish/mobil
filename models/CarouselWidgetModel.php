<?php
namespace app\models;

use Yii;
use app\models\Categories;

class CarouselWidgetModel extends DynamicBlockModel
{
    public $album;
    public $controls;
    public $interval;
    public $headerTemplate;
    public $showHeader;

    public function loadDefaults()
    {
        $this->album = null;
        $this->controls = true;
        $this->interval = 3;
        $this->headerTemplate = '<h4>{*title*}</h4>';
        $this->showHeader = true;
    }


    public function attributeLabels()
    {
        return [
            'album' => 'Альбом, изображения которого будут использованы для слайд-шоу',
            'controls' => 'Отображать стрелки "вперед-назад"',
            'interval' => 'Интервал между сменой изображений (секунды)',
            'headerTemplate' => 'Шаблон заголовка',
            'showHeader' => 'Показывать название изображения'
        ];
    }


    /* возвращает массив с именами переменных для шаблона*/
    public static function templateVars()
    {
        return [
            'title' => ['Название', 'Название изображения'],
        ];
    }

    public function getAlbumList()
    {
        $list = [];
        $cats = Categories::findByHandler('gallery');
        foreach ($cats as $c) {
            $res = $c->resources;
            if (count($res)) {
                foreach ($res as $r) {
                    $list[$r->id] = $c->title.' -> '.$r->title;
                }
            }
        }
        return $list;
    }

    public function beforeSave()
    {
        $search = $replace = [];
        foreach (self::templateVars() as $tv => $conf) {
            $search[] = '{'.$conf[0].'}';
            $replace[] = '{*'.$tv.'*}';
        }
        $this->headerTemplate = str_replace($search, $replace, $this->headerTemplate);
    }

    public function afterLoadConfig()
    {
        $search = $replace = [];
        foreach (self::templateVars() as $tv => $conf) {
            $search[] = '{*'.$tv.'*}';
            $replace[] = '{'.$conf[0].'}';
        }
        $this->headerTemplate = str_replace($search, $replace, $this->headerTemplate);
    }
}

