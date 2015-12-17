<?php 

namespace app\widgets\dynblocks;

use Yii;
use yii\helpers\Html;

class StaticHtmlWidget extends DynamicBlock
{
	public function run()
	{
		if ($this->blockId == null) {
			throw new \yii\base\InvalidCallException('Отсутствует обязательный параметр $blockId');
		}
		return $this->_config['Html'];
	}

	public static function getInfo()
	{
		return [
			'id' => 'static',
			'class' => __CLASS__,
			'label' => 'Статический HTML',
			'description' => 'Предназначен для вывода произвольного HTML-кода. '
							.'Может быть использован для вывода баннеров, информеров или карт со сторонних сервисов.'
		];
	}
}