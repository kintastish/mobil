<?php 
namespace app\components;

use yii\validators\Validator;

class PrettyUrlValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if ( !preg_match('/^([a-z0-9]-?)+$/', $attribute) ) {
            $this->addError($model, $attribute, 'Псевдоним может содержать только буквы, цифры и дефис');
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $re = '/^([a-z0-9]-?)+$/';

        return 
<<<JS
if (value.search($re)) {
    messages.push('Псевдоним может содержать только буквы, цифры и дефис');
}
JS;
    }
}
