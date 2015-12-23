<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class DropdownMenuWidgetModel extends DynamicBlockModel
{
    // public $beginTemplate;
    // public $itemTemplate;
    // public $endTemplate;

    public $links;

    public function loadDefaults()
    {
        // $this->beginTemplate = '<ul>';
        // $this->itemTemplate = '<li><a href="{*url*}">{*label*}</a></li>';
        // $this->endTemplate = '</ul>';
        $this->links = [];
    }


    public function attributeLabels()
    {
        return [
            // 'beginTemplate' => 'Начало меню',
            // 'itemTemplate' => 'Элемент меню',
            // 'endTemplate' => 'Конец меню',
            'links' => 'Ссылки',
        ];
    }


    /* возвращает массив с именами переменных для шаблона*/
    public static function templateVars()
    {
        return [
            'label' => ['Текст', 'Текст ссылки'],
            'url'   => ['URL', 'Адрес страницы'],
        ];
    }

    public function beforeSave()
    {
        // $search = $replace = [];
        // foreach (self::templateVars() as $tv => $conf) {
        //     $search[] = '{'.$conf[0].'}';
        //     $replace[] = '{*'.$tv.'*}';
        // }
        // $this->itemTemplate = str_replace($search, $replace, $this->itemTemplate);
    }

    public function afterLoadConfig()
    {
        // $search = $replace = [];
        // foreach (self::templateVars() as $tv => $conf) {
        //     $search[] = '{*'.$tv.'*}';
        //     $replace[] = '{'.$conf[0].'}';
        // }
        // $this->itemTemplate = str_replace($search, $replace, $this->itemTemplate);
    }

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            $this->links = [];
            for ($i=0; $i < count($data['title']); $i++) { 
                $this->links[$i] = ['title' => $data['title'][$i], 'url' => $data['url'][$i] ];
            }
            return true;
        };
        return false;
    }
}

