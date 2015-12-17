<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

class FeedbackFormWidgetModel extends DynamicBlockModel
{
    public $formId;
    public $tplInput;
    public $tplArea;
    public $tplMarker;
    public $note;
    public $email;
    public $subject;
    public $fieldName;
    public $fieldRequired;
    public $fieldType;
    public $useCaptcha;

    public function loadDefaults()
    {
        $this->formId = $this->blockId;
        $this->tplInput = '<div>{Метка} {Поле}</div>';
        $this->tplArea = '<div>{Метка} {Поле}</div>';
        $this->tplMarker = '*';
        $this->note = '<i>Поля, отмеченные {Маркер}, обязательны для заполнения</i>';
        $this->email = '';
        $this->subject = '';
        $this->fieldName = [];
        $this->fieldRequired = [];
        $this->fieldType = [];
        $this->useCaptcha = true;
    }

    public function attributeLabels()
    {
        return [
            'formId' => 'HTML идентификатор формы',
            'tplInput' => 'Шаблон однострочного поля',
            'tplArea' => 'Шаблон текстового поля',
            'tplMarker' => 'Шаблон маркера обязательного поля',
            'note' => 'Примечание',
            'email' => 'E-mail получателя',
            'subject' => 'Тема письма',
            'fieldName' => 'Поле',
            'fieldType' => 'Вид поля',
            'fieldRequired' => 'Обязательное поле',
            'useCaptcha' => 'Использовать защиту от спама',
        ];
    }


    /* возвращает массив с именами переменных для шаблона*/
    public static function templateVars()
    {
        return [
            'field'      => ['Поле', 'Поле формы'],
            'label'      => ['Метка', 'Метка поля'],
            'marker'     => ['Маркер', 'Маркер обязательного поля'],
        ];
    }

    public function beforeSave()
    {
        foreach ($this->fieldRequired as $ind => $value) {
            $this->fieldRequired[$ind] = ($value == 'true' || $value == '1');
        }
        $search = $replace = [];
        foreach (self::templateVars() as $tv => $conf) {
            $search[] = '{'.$conf[0].'}';
            $replace[] = '{*'.$tv.'*}';
        }
        $this->tplInput = str_replace($search, $replace, $this->tplInput);
        $this->tplArea = str_replace($search, $replace, $this->tplArea);
        $this->note = str_replace($search, $replace, $this->note);
        $this->useCaptcha = ($this->useCaptcha == 1);
    }

    public function afterLoadConfig()
    {
        $search = $replace = [];
        foreach (self::templateVars() as $tv => $conf) {
            $search[] = '{*'.$tv.'*}';
            $replace[] = '{'.$conf[0].'}';
        }
        $this->tplInput = str_replace($search, $replace, $this->tplInput);
        $this->tplArea = str_replace($search, $replace, $this->tplArea);
        $this->note = str_replace($search, $replace, $this->note);
        $this->useCaptcha = $this->useCaptcha ? 1 : 0;
    }
}

