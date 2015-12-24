<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class DropdownMenuWidgetModel extends DynamicBlockModel
{
    public $items;
    public $appearance;

    public function loadDefaults()
    {
        $this->items = [];
        $this->appearance = 1;
    }


    public function attributeLabels()
    {
        return [
            'items' => 'Ссылки',
            'appearance' => 'Внешний вид пунктов меню верхнего уровня'
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

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            $this->items = [];
            for ($i=0; $i < count($data['title']); $i++) { 
                if (intval($data['id'][$i]) != 0) {
                    $id = intval($data['id'][$i]);
                    $this->items[$id] = ['label' => $data['title'][$i], 'url' => $data['url'][$i] ];
                }
                elseif (intval($data['parent'][$i]) != 0) {
                    $id = intval($data['parent'][$i]);
                    $this->items[$id]['items'][] = ['label' => $data['title'][$i], 'url' => $data['url'][$i]];
//                    $this->items[$id]['url'] = '#';
                }
            }
            Yii::trace(VarDumper::dumpAsString($this->items));
            Yii::trace(VarDumper::dumpAsString($data));
            return true;
        };
        return false;
    }
}

