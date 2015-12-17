<?php 

namespace app\components;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;

/**
* Замена параметров в сохраняемой записи @@параметр/id параметра
*/
class ParamReplacementBehavior extends Behavior
{
    public $field;

	private $_output = [];
    private $_params = [];

    public function init()
    {
        parent::init();
        $this->_params = ArrayHelper::map(\app\models\ParamTypes::getAllParams(), 'id', 'name');
        if ($this->field == '') {
            throw new \yii\base\InvalidConfigException("Не задан параметр $field");
        }
    }

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'replaceParamNameById'
        ];
    }

    public function replaceParamNameById($event)
    {
        $search = [];
        $replace = [];
        foreach ($this->_params as $id => $name) {
            $search[] = '@'.$name.'@';
            $replace[] = '@'.$id.'@';
        }
        $o = $this->owner;
        $f = $this->field;
        $o->$f = str_replace($search, $replace, $o->$f);
    }

    public function replaceParamIdByName()
    {
        $search = [];
        $replace = [];
        foreach ($this->_params as $id => $name) {
            $search[] = '@'.$id.'@';
            $replace[] = '@'.$name.'@';
        }
        $o = $this->owner;
        $f = $this->field;
        $o->$f = str_replace($search, $replace, $o->$f);
    }

    public function replaceParamIdByValue()
    {
        $search = [];
        $replace = [];
        $o = $this->owner;
        $params = $o->getParams();
        if ($params !== null) {
            foreach ($params as $p) {
                $search[] = '@'.$p->type_id.'@';
                $replace[] = $p->value;
            }
        }
        $f = $this->field;
        $o->$f = str_replace($search, $replace, $o->$f);
    }

}