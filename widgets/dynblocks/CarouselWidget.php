<?php 

namespace app\widgets\dynblocks;

use Yii;
use yii\helpers\Html;
use app\models\Files;
use yii\bootstrap\Carousel;

class CarouselWidget extends DynamicBlock
{
	public function run()
	{
		if ($this->blockId == null) {
			throw new \yii\base\InvalidCallException('Отсутствует обязательный параметр $blockId');
		}
		//return '!---@---!';
		return $this->renderBlock();
	}

	public static function getInfo()
	{
		return [
			'id' => 'static',
			'class' => __CLASS__,
			'label' => 'Карусель',
			'description' => 'Вывод набора изображений в виде зацикленного слайд-шоу. '
							.'В качестве источника изображений используется указанный пользователем альбом галереи.'
		];
	}


	private function renderBlock()
	{
		// return \app\widgets\blueimp\BlueimpGallery::widget(['items'=>$items, 'mode'=>2]);
		$config['items'] = $this->getItems();
		$config['clientOptions'] = ['interval' => intval($this->_config['interval']) * 1000];
		if (!$this->_config['controls']) {
			$config['controls'] = false;
		}
		return Carousel::widget($config);
	}

	private function getItems()
	{
		$res = [];
		$images = Files::getAttachedFiles(\app\models\Resources::$tableId, $this->_config['album']);
		foreach ($images as $im) {
			$cfg['content'] = '<img src="'.$im->url.'"/>';
			if ($this->_config['showHeader']) {
			 	$cfg['caption'] = str_replace('{*title*}', $im->title, $this->_config['headerTemplate']);
			 } 
			$res[] = $cfg;
		}
		return $res;
	}
}