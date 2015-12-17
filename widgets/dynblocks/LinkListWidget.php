<?php 

namespace app\widgets\dynblocks;

use Yii;
use yii\helpers\Html;
use app\models\Categories;

class LinkListWidget extends DynamicBlock
{
	private $_items;
	private $_ph;	//template placeholders

	public function init()
	{
		parent::init();
		$this->_ph = [];
		$modelClass = $this->_info['modelClass'];
		foreach ($modelClass::templateVars() as $k => $v) {
			$this->_ph[] = '{*'.$k.'*}';
		}
	}

	public function run()
	{
		if ($this->blockId == null) {
			throw new \yii\base\InvalidCallException('Отсутствует обязательный параметр $blockId');
		}
		return $this->_config['blockBeginTemplate'].$this->buildItems().$this->_config['blockEndTemplate'];
	}

	public static function getInfo()
	{
		return [
			'id' => 'link-list',
			'class' => __CLASS__,
			'label' => 'Блок ссылок',
			'description' => 'Позволяет генерировать список ссылок на основе имеющихся страниц сайта. '.
							 'Подходит для вывода ленты новостей, блока навигации по разделу и т.д.'
		];
	}


	private function buildItems()
	{
		$tpl = $this->_config['itemTemplate'];
		$hasParams = (preg_match('/@\d+@/', $tpl) != false);
		Yii::trace('hasParams='.$hasParams);
		$cats = Categories::findAll( array_keys($this->_config['filterCategories']) );
		foreach ($cats as $c) {
			if ( !$c->isEmpty ) {
				foreach ($c->resources as $r) {
					$t = $tpl;
					if ($hasParams) {
						$params = $r->getParams();
						if ($params !== null) {
							$search = $replace = [];
							foreach ($params as $p) {
								$search[] = '@'.$p->type_id.'@';
								$replace[] = $p->value;
							}
							$t = str_replace($search, $replace, $t);
							$t = preg_replace('/@\d+@/', '', $t);	//удаление лишних/не найденных
						}
					}
					$this->_items[] = str_replace($this->_ph, [$r->id, $r->created, $r->alias, $r->title, $r->description, '/'.$r->route], $t);
				}
			}
		}
		return implode('', $this->_items);
	}
}