<?php 

namespace app\widgets\dynblocks;

use Yii;
use yii\bootstrap\Nav;

class DropdownMenuWidget extends DynamicBlock
{
	public function run()
	{
		if ($this->blockId == null) {
			throw new \yii\base\InvalidCallException('Отсутствует обязательный параметр $blockId');
		}
		return $this->renderBlock();
	}

	public static function getInfo()
	{
		return [
			'id' => 'menu',
			'class' => __CLASS__,
			'label' => 'Выпадающее меню',
			'description' => 'Отрисовка меню с выпадающими вложенными элементами'
		];
	}

	private function renderBlock()
	{
		return Nav::widget([
			'items' => $this->_config['items'],
			'options' => ['class' => $this->_config['appearance']],
			'dropDownCaret' => false
		]);
	}
}