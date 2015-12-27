<?php 

namespace app\widgets\dynblocks;

use Yii;
use yii\helpers\Html;
use app\models\Files;
use yii\bootstrap\Carousel;

class CarouselWidget extends DynamicBlock
{
	private $carouselClassId = 'slick-carousel-widget';
	
	public function run()
	{
		if ($this->blockId == null) {
			throw new \yii\base\InvalidCallException('Отсутствует обязательный параметр $blockId');
		}
		$this->registerScripts();
		return $this->renderBlock();
	}

	public static function getInfo()
	{
		return [
			'id' => 'carousel',
			'class' => __CLASS__,
			'label' => 'Карусель',
			'description' => 'Вывод набора изображений в виде зацикленного слайд-шоу. '
							.'В качестве источника изображений используется указанный пользователем альбом галереи.'
		];
	}


	private function renderBlock()
	{
		// $config['items'] = $this->getItems();
		// $config['clientOptions'] = ['interval' => intval($this->_config['interval']) * 1000];
		// if (!$this->_config['controls']) {
		// 	$config['controls'] = false;
		// }
		// return Carousel::widget($config);
		$output = Html::beginTag('div', ['class' => $this->carouselClassId]);
		$output .= $this->renderItems();
		$output .= Html::endTag('div');
		return $output;
	}

	private function renderItems()
	{
		$output = '';
		$images = Files::getAttachedFiles(\app\models\Resources::$tableId, $this->_config['album']);
		foreach ($images as $im) {
			$output .= Html::tag('div', Html::img($im->url));
		}
		return $output;
	}

	private function getItems()
	{
		$res = [];
		$images = Files::getAttachedFiles(\app\models\Resources::$tableId, $this->_config['album']);
		foreach ($images as $im) {
			$cfg['content'] = '<img src="'.$im->url.'"/>';
			// if ($this->_config['showHeader']) {
			//  	$cfg['caption'] = str_replace('{*title*}', $im->title, $this->_config['headerTemplate']);
			//  } 
			$res[] = $cfg;
		}
		return $res;
	}

	private function registerScripts()
	{
		SlickAsset::register($this->view);
		$this->view->registerJs($this->getInitScript(), \yii\web\View::POS_END);
	}

	private function getInitScript()
	{
		$script = '$(document).ready(function(){';
		$script.= "\n".'$(".'.$this->carouselClassId.'").slick({';
		$script.= "\n".'autoplaySpeed: '.(intval($this->_config['interval']) * 1000).',';
		$script.= "\n".'autoplay: true,';
		$script.= "\n".'centerMode: true,';
		$script.= "\n".'slidesToShow: '.$this->_config['slidesToShow'].',';
		$script.= "\n".'slidesToScroll: '.$this->_config['slidesToScroll'].',';
		$script.= "\n".($this->_config['variableWidth'] == '1' ? 'variableWidth: true,' : '');
		$script.= "\n".($this->_config['controls'] == '1' ? 'arrows: true' : 'arrows: false');
		$script.= "\n".'});});';

		return $script;
	}
}