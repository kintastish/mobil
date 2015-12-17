<?php
namespace app\models;

use Yii;

class StaticHtmlWidgetModel extends DynamicBlockModel
{
    public $Html;

    public function loadDefaults()
    {
        $this->Html = '';
    }


    public function attributeLabels()
    {
        return [
            'Html' => 'Код HTML',
        ];
    }


    /* возвращает массив с именами переменных для шаблона*/
    public static function templateVars()
    {
        return [
        ];
    }
}

