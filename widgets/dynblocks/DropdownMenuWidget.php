<?php 

namespace app\widgets\dynblocks;

use Yii;
use yii\helpers\Html;
use app\models\Categories;

class DropdownMenuWidget extends DynamicBlock
{
	public function run()
	{
		if ($this->blockId == null) {
			throw new \yii\base\InvalidCallException('Отсутствует обязательный параметр $blockId');
		}
		return $this->_config['beginTemplate'].$this->buildItems().$this->_config['endTemplate'];
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


	private function buildItems()
	{
		$tpl = $this->_config['itemTemplate'];
		$s = '';
		foreach ($this->_config['links'] as $l) {
			$s .= str_replace(['{*label*}', '{*url*}'], [$l['title'], $l['url']], $tpl);
		}
		return $s;
	}
}