<?php
namespace app\widgets\translit;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;


class transliterator extends \yii\widgets\InputWidget
{
    public $route = 'transliterate/do';
    public $targetAttribute = '';

    public $sourceId = '';
    public $targetId = '';

    public $options = ['class' => 'form-control'];

    public function init()
    {
        parent::init();
        $this->setOptions();
    }

    public function run()
    {
        $this->registerJs();
        if ($this->hasModel()) {
            echo Html::activeInput('text', $this->model, $this->attribute, $this->options);
        } else {
            echo Html::input('text', $this->name, $this->value, $this->options);
        }
    }

    private function setOptions()
    {
        if ($this->route == '') {
            throw new \yii\base\InvalidConfigException("Не указан URL для транслитерации");
        }
        if ($this->sourceId == '') {
            if ($this->hasModel()) {
                $this->sourceId = Html::getInputId($this->model, $this->attribute);
            }
            else {
                throw new \yii\base\InvalidConfigException("Должна быть указана модель или ID поля");
            }
        }
        if ($this->targetId == '') {
            $this->targetId = $this->sourceId;
            if ($this->targetAttribute != '') {
                $this->targetId = Html::getInputId($this->model, $this->targetAttribute);
            }
        }
    }   

    private function registerJs()
    {
        $view = $this->getView();
        $url = Url::to([$this->route]);
        $src = $this->sourceId;
        $tr  = $this->targetId;
        $js = <<<JS
userInput = false;
$('#$src').change( function() {
    var src = $('#$src').val();
    $.get( '$url', {'t':src}, function(data, textStatus){
        var d = eval(data);
        $('#$tr').val(d.tr);
    } );
});
$('#$tr').change( function() {
    if (!userInput) {
        userInput = true;
        $('#$src').off('change');
    }
});
JS;
        //$('#$src').siblings('label').append('<span class="label label-info">tr</span>');
        // var p = $('#$src').parent();
        // var d = $('<span class="input-container"></span>');
        // p.append(d);
        // d.append($('#$src'));
        // $('#$src').animate({width: '95%'}, 500);
        // $('<span class="label label-info" style="width:5%; position:absolute;left:5px">tr</span>').insertAfter('#$src');
        $view->registerJs($js);
    }
}