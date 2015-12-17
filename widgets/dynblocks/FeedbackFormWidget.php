<?php 

namespace app\widgets\dynblocks;

use Yii;
use yii\helpers\Html;
use yii\captcha\Captcha;

class FeedbackFormWidget extends DynamicBlock
{
	private $_action;

	public function init()
	{
		parent::init();
		$this->_action = ['/feedback'];
	}

	public function run()
	{
		if ($this->blockId == null) {
			throw new \yii\base\InvalidCallException('Отсутствует обязательный параметр $blockId');
		}
		return $this->buildForm();
	}

	public static function getInfo()
	{
		return [
			'id' => 'feedback-form',
			'class' => __CLASS__,
			'label' => 'Форма обратной связи',
			'description' => 'Настраиваемая форма обратной связи.'
		];
	}


	private function buildForm()
	{
		$id = $this->_config['formId'];
		$opts = [];
		if ($id != '') {
			$opts['id'] = $id;
		}
		$form = Html::beginForm($this->_action, 'post', $opts);
		$form.= Html::hiddenInput('formid', $this->blockId);
		$form.= $this->renderFields();
		$form.= $this->renderCaptcha();
		$form.= $this->renderNote();
		$form.= Html::tag('div', Html::submitButton('Отправить'));
		$form.= Html::endForm();
		$form.= Html::tag('div', '', ['id'=>'feedback-message']);
		$this->registerScript();
		return $form;
	}

	private function renderFields()
	{
		$rows = '';
		if ( count($this->_config['fieldName']) ) {
			foreach ($this->_config['fieldName'] as $ind => $n) {
				$search = [];
				$replace = [];
				$t = $this->_config['fieldType'][$ind];
				$r = $this->_config['fieldRequired'][$ind];
				//$opt = $r ? ['required'=>true] : [];
				$name = 'f'.$ind;
				$label = Html::label($n).($r ? $this->_config['tplMarker'] : '');
				$search[] = '{*label*}';
				$replace[] = $label;
				if ($t == 'input') {
					$opt['maxlength'] = 50;
					$tpl = 'tplInput';
					$field = Html::textInput($name, null, $opt);
				}
				elseif ($t == 'textarea') {
					$opt['rows'] = 4;
					$opt['maxlength'] = 500;
					$tpl = 'tplArea';
					$field = Html::textarea($name, '', $opt);
				}
				if ( (strpos($this->_config[$tpl], '{*label*}') === false) && $r) {
					$field .= $this->_config['tplMarker'];
				}
				$search[] = '{*field*}';
				$replace[] = $field;
				$rows .= str_replace($search, $replace, $this->_config[$tpl])."\n";
			}
		}
		return $rows;
	}
	private function renderNote()
	{
		$note = $this->_config['note'];
		return str_replace('{*marker*}', $this->_config['tplMarker'], $note);
	}

	private function renderCaptcha()
	{
		if ( $this->_config['useCaptcha'] ) {
			return Html::tag('div', Html::label('Введите символы с картинки')).
			Html::tag('div', 
				Captcha::widget([
					'name' => 'captcha',
					'options' => ['required' => true],
					//'captchaAction' => 'captcha'
				])
			);
		}
		return '';
	}

	private function registerScript()
	{
		$view = $this->getView();
		$formId = $this->_config['formId'];
		$js = <<<submit
var f = $('#$formId');
$(f).on('submit', function (e) {
	var url = $(f).attr('action');
	var data = $(this).serialize();
	$.post(url, data, function (data) {
		var msg = $('#feedback-message');
		msg.empty();
		var d = eval(data);
		var s = '';
		if (d.errors != undefined) {
			for (var i in d.errors) {
				s += '<p><b>' + d.errors[i][0] + '</b></p>';
			}
			$(s).appendTo(msg);
		}
		else {
			$f(f).hide(300);
			$('<p>Ваше сообщение отправлено</p>').appendTo(msg);
		}
	});
	e.preventDefault();
})
submit;
		$view->registerJs($js, \yii\web\View::POS_END);
	}
}