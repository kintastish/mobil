<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class LinkListWidgetModel extends DynamicBlockModel
{
    public $blockBeginTemplate;
    public $itemTemplate;
    public $blockEndTemplate;
    public $countMax;

    public $filterCategories = [];

    public function loadDefaults()
    {
        $this->blockBeginTemplate = '<div>';
        $this->itemTemplate = '<div></div>';
        $this->blockEndTemplate = '</div>';
        $this->countMax = 10;
    }

    /* */
    public function getFilterConfig()
    {
        return [
            'category' => [
                'label' => 'Раздел',
                'name' => null,
                'compare' => ['=', '<>'],
                'value' => ArrayHelper::map($this->getCategoryList(), 'id', 'title')
            ],
            'sort' => [
                'label' => 'Сортировка',
                'value' => ['new' => 'Сначала новые', 'old' => 'Сначала старые']
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'blockBeginTemplate' => 'Начало списка',
            'itemTemplate' => 'Элемент списка',
            'blockEndTemplate' => 'Конец списка',
            'countMax' => 'Макс. кол-во элементов',
        ];
    }


    /* возвращает массив с именами переменных для шаблона*/
    public static function templateVars()
    {
        return [
            'id'            => ['ID', 'Идентификатор ресурса'],
            'created'       => ['ДатаСоздания', 'Дата создания'],
            'alias'         => ['Псевдоним', 'Псевдоним'],
            'title'         => ['Заголовок', 'Заголовок страницы'],
            'description'   => ['Описание', 'Текст описания'],
            'url'           => ['Ссылка', 'Адрес ссылки на страницу']
        ];
    }

    public function getCategoryList($id = 0, $l = 0)
    {
        $cat = Categories::findAll(['parent_id' => $id]);
        $list = [];
        $level = $l;
        foreach ($cat as $c) {
            //$title = $t.$c->title;
            $t = $this->getCategoryList($c->id, $level+1);
            $list[] = [
                'id' => $c->id,
                'title' => $c->title,
                'level' => $level,
                'parent' => $id,
                'child_count' => count($t)
            ];
            $list = array_merge($list, $t);
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
        $params = ArrayHelper::map(\app\models\ParamTypes::getAllParams(), 'id', 'name');
        foreach ($params as $id => $name) {
            $search[] = '@'.$name.'@';
            $replace[] = '@'.$id.'@';
        }        
        $this->itemTemplate = str_replace($search, $replace, $this->itemTemplate);
    }

    public function afterLoadConfig()
    {
        $search = $replace = [];
        foreach (self::templateVars() as $tv => $conf) {
            $search[] = '{*'.$tv.'*}';
            $replace[] = '{'.$conf[0].'}';
        }
        $params = ArrayHelper::map(\app\models\ParamTypes::getAllParams(), 'id', 'name');
        foreach ($params as $id => $name) {
            $search[] = '@'.$id.'@';
            $replace[] = '@'.$name.'@';
        }        
        $this->itemTemplate = str_replace($search, $replace, $this->itemTemplate);
    }

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            $this->filterCategories = [];
            foreach ($data as $p => $v) {
                if (strpos($p, '_fc') === 0) {
                    $id = str_replace('_fc', '', $p);
                    $this->filterCategories[$id] = $v;
                }
            }
            return true;
        };
        return false;
    }
}

